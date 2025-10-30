<?php

namespace Tests\Feature;

use App\Mail\OrderConfirmationMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure guest cart session structure matches controller expectations
        Session::start();
    }

    public function test_guest_checkout_creates_order_and_sends_confirmation_link(): void
    {
        Storage::fake('public');
        Mail::fake();

        $product = Product::create([
            'name' => 'Test Product',
            'sku' => 'TP-001',
            'price' => 25.00,
            'stock' => 10,
            'category' => 'cap',
            'image' => 'placeholder.jpg',
            'description' => 'Guest checkout test product.',
        ]);

        $this->withSession([
            'cart' => [
                $product->id => [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response = $this->post(route('cart.storeOrder'), [
            'name' => 'Guest Tester',
            'email' => 'guest@example.com',
            'phone' => '1234567890',
            'address' => '123 Test Street',
            'receipt' => UploadedFile::fake()->create('receipt.jpg', 200, 'image/jpeg'),
        ]);

        $order = Order::first();
        $this->assertNotNull($order, 'Order was not persisted');
        $this->assertNull($order->user_id, 'Guest checkout should not associate a user');
        $this->assertSame('Guest Tester', $order->guest_name);
        $this->assertSame('guest@example.com', $order->guest_email);

        $expectedUrl = URL::signedRoute('order.confirmation', ['order' => $order->id]);
        $response->assertRedirect($expectedUrl);

        $this->assertDatabaseCount('order_items', 1);
        $this->assertDatabaseHas('payment_receipts', [
            'order_id' => $order->id,
            'status' => 'pending',
        ]);

        Mail::assertSent(OrderConfirmationMail::class, function (OrderConfirmationMail $mail) use ($order) {
            return $mail->hasTo('guest@example.com')
                && $mail->order->is($order);
        });
    }

    public function test_authenticated_checkout_requires_ownership_for_confirmation(): void
    {
        Storage::fake('public');
        Mail::fake();

        $user = User::factory()->create();
        $product = Product::create([
            'name' => 'Member Product',
            'sku' => 'MP-001',
            'price' => 40.00,
            'stock' => 5,
            'category' => 'cap',
            'image' => 'placeholder.jpg',
            'description' => 'Authenticated checkout test product.',
        ]);

        $this->actingAs($user);

        $this->withSession([
            'cart' => [
                $product->id => [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ],
            ],
        ]);

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->post(route('cart.storeOrder'), [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '5555555555',
            'address' => '456 Member Lane',
            'receipt' => UploadedFile::fake()->create('receipt.png', 200, 'image/png'),
        ]);

        $order = Order::first();
        $this->assertNotNull($order, 'Order was not persisted');
        $this->assertEquals($user->id, $order->user_id);
        $this->assertNull($order->guest_name);

        $expectedUrl = URL::signedRoute('order.confirmation', ['order' => $order->id]);
        $response->assertRedirect($expectedUrl);

        // Ensure authenticated user can view confirmation
        $confirmResponse = $this->get($expectedUrl);
        $confirmResponse->assertOk()->assertViewIs('confirmation');

        Mail::assertSent(OrderConfirmationMail::class, function (OrderConfirmationMail $mail) use ($user, $order) {
            return $mail->hasTo($user->email) && $mail->order->is($order);
        });
    }
}

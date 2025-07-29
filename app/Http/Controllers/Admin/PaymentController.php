<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentReceipt;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $receipts = PaymentReceipt::with('order.user')->paginate(10);
        Log::info('Admin payment receipts loaded', ['count' => $receipts->count()]);
        return view('admin.payments.index', compact('receipts'));
    }

    public function show(PaymentReceipt $receipt)
    {
        $receipt->load('order.user');
        Log::info('Viewing payment receipt', ['receipt_id' => $receipt->id]);
        return view('admin.payments.show', compact('receipt'));
    }

    public function verify(Request $request, PaymentReceipt $receipt)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();
            $receipt->update([
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            $orderStatus = $request->status === 'verified' ? 'verified' : 'cancelled';
            $receipt->order->update(['status' => $orderStatus]);

            Log::info('Payment receipt updated', $receipt->toArray());
            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment receipt updated successfully.',
                    'redirect' => route('admin.payments.index'),
                ]);
            }

            return redirect()->route('admin.payments.index')->with('success', 'Payment receipt updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment receipt update failed', ['message' => $e->getMessage()]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update receipt: ' . $e->getMessage(),
                ], 500);
            }
            return back()->with('error', 'Failed to update receipt: ' . $e->getMessage());
        }
    }
}
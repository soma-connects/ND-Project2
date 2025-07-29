<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cart;

class CleanInvalidCarts extends Command
{
    protected $signature = 'carts:clean';
    protected $description = 'Remove cart items with missing products';

    public function handle()
    {
        $deleted = Cart::whereNotNull('product_id')
            ->whereDoesntHave('product')
            ->delete();

        $this->info("Deleted $deleted invalid cart items.");
    }
}
<?php

namespace App\Listeners;

use App\Events\ProductPurchased;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateProductQuantity
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ProductPurchased  $event
     * @return void
     */
    public function handle(ProductPurchased $event)
    {
        // Access the purchased product from the event
        $product = $event->product;

        // Perform logic to update the product quantity after purchase
        $product->quantity -= 1; // For example, deduct one from the quantity
        $product->save();
    }
}

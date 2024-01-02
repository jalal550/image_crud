<?php

namespace App\Http\Controllers;
use App\Events\ProductPurchased;
use App\Models\Product;


use Illuminate\Http\Request;

class ProductPurchasedController extends Controller
{
    public function purchaseProduct($productId)
    {
        $product = Product::find($productId);

        // Perform the purchase logic...

        // Dispatch the ProductPurchased event when the product is purchased
        event(new ProductPurchased($product));

        // Return response or redirect
    }
}

<?php

namespace App\Jobs;

use App\Models\Product;
use App\Mail\NewProductCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewProductEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function handle()
    {
        // Logic to send email
        $user = $this->product->user; // Assuming there's a relationship between product and user
        $email = $user->email;

        Mail::to($email)->send(new NewProductCreated($this->product));
    }
}

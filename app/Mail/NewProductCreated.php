<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewProductCreated extends Mailable
{


    use Queueable, SerializesModels;

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function build()
    {
        return $this->subject('New Product Created')
            ->markdown('backend.email.new_product_created')
            ->with(['product' => $this->product]);
    }
}

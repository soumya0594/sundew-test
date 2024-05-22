<?php
namespace App\Services;

use App\Contracts\PaymentInterface;

class Stripe implements PaymentInterface
{
    public function charge($amount, $discount, $tax)
    {
        return ($amount * $tax / 100) - $discount;
    }
}

<?php
namespace App\Services;

use App\Contracts\PaymentInterface;

class PayPal implements PaymentInterface
{
    public function charge($amount, $discount, $tax)
    {
        return ($amount - $discount) * ($tax / 100);
    }
}

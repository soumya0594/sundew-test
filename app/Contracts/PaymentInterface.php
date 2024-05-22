<?php

namespace App\Contracts;

interface PaymentInterface
{
    public function charge(int $amount, int $discount, float $tax);
}

<?php

namespace App\Services\Payment;

interface PaymentGatewayInterface
{
    public function processPayment(float $amount, array $metadata): array;
    public function verifyPayment(string $transactionRef): array;
    public function processRefund(string $transactionRef, float $amount): array;
}

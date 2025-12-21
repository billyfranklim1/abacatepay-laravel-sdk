<?php

use Billyfranklim\AbacatePay\Clients\PixQrCodeClient;
use Billyfranklim\AbacatePay\Resources\PixQrCode;

test('pode criar QR Code PIX', function () {
    $mockClient = getCreatePixQrCodeResponseClient();
    $pixClient = new PixQrCodeClient('test_token', $mockClient);

    $pixQrCode = $pixClient->create([
        'amount' => 10000,
        'expires_in' => 3600,
        'description' => 'Pagamento via PIX'
    ]);

    expect($pixQrCode)
        ->toBeInstanceOf(PixQrCode::class)
        ->and($pixQrCode->id)
        ->toBe('pix_123')
        ->and($pixQrCode->amount)
        ->toBe(10000);
});

test('pode verificar status do QR Code PIX', function () {
    $mockClient = getCreatePixQrCodeResponseClient();
    $pixClient = new PixQrCodeClient('test_token', $mockClient);

    $pixQrCode = $pixClient->check('pix_123');

    expect($pixQrCode)
        ->toBeInstanceOf(PixQrCode::class);
});

test('pode simular pagamento do QR Code PIX', function () {
    $mockClient = getCreatePixQrCodeResponseClient();
    $pixClient = new PixQrCodeClient('test_token', $mockClient);

    $pixQrCode = $pixClient->simulatePayment('pix_123', ['test' => 'metadata']);

    expect($pixQrCode)
        ->toBeInstanceOf(PixQrCode::class);
});


<?php

use Billyfranklim\AbacatePay\Clients\CouponClient;
use Billyfranklim\AbacatePay\Enums\Coupon\DiscountKind;
use Billyfranklim\AbacatePay\Enums\Coupon\Status;
use Billyfranklim\AbacatePay\Exceptions\ApiException;
use Billyfranklim\AbacatePay\Resources\Coupon;

test('pode listar cupons', function () {
    $mockClient = getListCouponsResponseClient();
    $couponClient = new CouponClient('test_token', $mockClient);

    $coupons = $couponClient->list();

    expect($coupons)
        ->toBeArray()
        ->not->toBeEmpty()
        ->and($coupons[0])
        ->toBeInstanceOf(Coupon::class)
        ->and($coupons[0]->id)
        ->toBe('coupon_123')
        ->and($coupons[0]->code)
        ->toBe('DESCONTO10')
        ->and($coupons[0]->discount_kind)
        ->toBe(DiscountKind::PERCENTAGE)
        ->and($coupons[0]->status)
        ->toBe(Status::ACTIVE);
});

test('pode criar um cupom', function () {
    $mockClient = getCreateCouponResponseClient();
    $couponClient = new CouponClient('test_token', $mockClient);

    $coupon = new Coupon([
        'code' => 'DESCONTO10',
        'discount_kind' => DiscountKind::PERCENTAGE,
        'discount' => 10,
        'max_redeems' => 100,
        'notes' => 'Desconto de 10% para novos clientes'
    ]);

    $createdCoupon = $couponClient->create($coupon);

    expect($createdCoupon)
        ->toBeInstanceOf(Coupon::class)
        ->and($createdCoupon->id)
        ->toBe('coupon_123')
        ->and($createdCoupon->code)
        ->toBe('DESCONTO10')
        ->and($createdCoupon->discount_kind)
        ->toBe(DiscountKind::PERCENTAGE)
        ->and($createdCoupon->discount)
        ->toBe(10)
        ->and($createdCoupon->max_redeems)
        ->toBe(100)
        ->and($createdCoupon->status)
        ->toBe(Status::ACTIVE);
});

test('lança exceção quando a API retorna erro', function () {
    $mockClient = createErrorResponseClient(400, 'Invalid request');
    $couponClient = new CouponClient('test_token', $mockClient);

    expect(fn() => $couponClient->list())
        ->toThrow(ApiException::class, 'AbacatePay API Error');
});



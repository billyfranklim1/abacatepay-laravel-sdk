# AbacatePay Laravel SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/billyfranklim/abacatepay-laravel-sdk.svg?style=flat-square)](https://packagist.org/packages/billyfranklim/abacatepay-laravel-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/billyfranklim/abacatepay-laravel-sdk.svg?style=flat-square)](https://packagist.org/packages/billyfranklim/abacatepay-laravel-sdk)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen)](https://packagist.org/packages/billyfranklim/abacatepay-laravel-sdk)

SDK Laravel oficial para integração com a API do AbacatePay. Aceite pagamentos em segundos com uma integração simples.

## Requisitos

- PHP 8.1 ou superior
- Laravel 11 ou superior
- Token da API AbacatePay

## Instalação

Você pode instalar o pacote via Composer:

```bash
composer require billyfranklim/abacatepay-laravel-sdk
```

## Configuração

Publique o arquivo de configuração:

```bash
php artisan vendor:publish --tag="abacatepay-config"
```

Adicione seu token da API no arquivo `.env`:

```env
ABACATEPAY_TOKEN=seu_token_aqui
```

Opcionalmente, você pode configurar a URL base da API:

```env
ABACATEPAY_BASE_URI=https://api.abacatepay.com/v1
```

## Uso Rápido

```php
use Billyfranklim\AbacatePay\Facades\AbacatePay;

// O SDK já está configurado automaticamente via Service Provider
$abacate = AbacatePay::billing();
```

## Criando um Pagamento

### Pagamento Único

```php
use Billyfranklim\AbacatePay\Facades\AbacatePay;
use Billyfranklim\AbacatePay\Resources\Billing;
use Billyfranklim\AbacatePay\Resources\Billing\Product;
use Billyfranklim\AbacatePay\Resources\Billing\Metadata as BillingMetadata;
use Billyfranklim\AbacatePay\Enums\Billing\Frequencies;
use Billyfranklim\AbacatePay\Enums\Billing\Methods;
use Billyfranklim\AbacatePay\Resources\Customer;
use Billyfranklim\AbacatePay\Resources\Customer\Metadata as CustomerMetadata;

$billing = AbacatePay::billing()->create(new Billing([
    'frequency' => Frequencies::ONE_TIME,
    'methods' => [Methods::PIX],
    'products' => [
        new Product([
            'external_id' => 'PRO-PLAN',
            'name' => 'Pro plan',
            'quantity' => 1,
            'price' => 1000, // Valor em centavos
        ])
    ],
    'metadata' => new BillingMetadata([
        'return_url' => 'https://seusite.com/app',
        'completion_url' => 'https://seusite.com/payment/success'
    ]),
    'customer' => new Customer([
        'metadata' => new CustomerMetadata([
            'name' => 'Nome do Cliente',
            'email' => 'cliente@example.com',
            'cellphone' => '+5511999999999',
            'tax_id' => '09240529020'
        ])
    ])
]));

// Acessar a URL de pagamento
$paymentUrl = $billing->url; // https://abacatepay.com/pay/bill_12345667
```

### Criar Link de Pagamento (Múltiplos Pagamentos)

```php
$billing = AbacatePay::billing()->createLink(new Billing([
    'methods' => [Methods::PIX, Methods::CARD],
    'products' => [
        new Product([
            'name' => 'Assinatura Mensal',
            'quantity' => 1,
            'price' => 5000
        ])
    ],
    'metadata' => new BillingMetadata([
        'return_url' => 'https://seusite.com/app',
        'completion_url' => 'https://seusite.com/payment/success'
    ])
]));
```

### Resposta

```php
// $billing contém:
[
    'id' => 'bill_12345667',
    'url' => 'https://abacatepay.com/pay/bill_12345667', // URL de pagamento para o cliente
    'amount' => 1000,
    'status' => 'PENDING',
    'dev_mode' => true,
    'methods' => ['PIX'],
    'frequency' => 'ONE_TIME',
    'customer' => [
        'id' => 'cust_12345',
        'metadata' => [
            'email' => 'cliente@example.com'
        ]
    ],
    'created_at' => '2024-11-04T18:38:28.573',
    'updated_at' => '2024-11-04T18:38:28.573',
]
```

## Gerenciamento de Clientes

### Criar Cliente

```php
use Billyfranklim\AbacatePay\Resources\Customer;
use Billyfranklim\AbacatePay\Resources\Customer\Metadata as CustomerMetadata;

$customer = AbacatePay::customer()->create(new Customer([
    'metadata' => new CustomerMetadata([
        'name' => 'Maria Santos',
        'cellphone' => '01998765432',
        'email' => 'maria@example.com',
        'tax_id' => '98765432100'
    ])
]));
```

### Listar Clientes

```php
$customers = AbacatePay::customer()->list();

foreach ($customers as $customer) {
    echo $customer->id . ' - ' . $customer->metadata->email;
}
```

## Cupons de Desconto

### Criar Cupom

```php
use Billyfranklim\AbacatePay\Resources\Coupon;
use Billyfranklim\AbacatePay\Enums\Coupon\DiscountKind;

$coupon = AbacatePay::coupon()->create(new Coupon([
    'code' => 'DESCONTO10',
    'discount_kind' => DiscountKind::PERCENTAGE,
    'discount' => 10, // 10% de desconto
    'max_redeems' => 100,
    'notes' => 'Desconto de 10% para novos clientes'
]));

// Ou desconto fixo
$coupon = AbacatePay::coupon()->create(new Coupon([
    'code' => 'DESCONTO50',
    'discount_kind' => DiscountKind::FIXED,
    'discount' => 5000, // R$ 50,00 de desconto
    'max_redeems' => 50
]));
```

### Listar Cupons

```php
$coupons = AbacatePay::coupon()->list();
```

## Saques (Withdrawals)

### Criar Saque

```php
use Billyfranklim\AbacatePay\Resources\Withdrawal;
use Billyfranklim\AbacatePay\Resources\Withdrawal\BankAccount;
use Billyfranklim\AbacatePay\Enums\Withdrawal\AccountType;

$withdrawal = AbacatePay::withdrawal()->create(new Withdrawal([
    'amount' => 10000, // R$ 100,00 em centavos
    'bank_account' => new BankAccount([
        'bank_code' => '001',
        'agency' => '1234',
        'account' => '12345678',
        'account_type' => AccountType::CHECKING,
        'holder_name' => 'João da Silva',
        'holder_document' => '12345678900'
    ])
]));
```

### Buscar Saque por ID

```php
$withdrawal = AbacatePay::withdrawal()->get('withdrawal_123');
```

### Listar Saques

```php
$withdrawals = AbacatePay::withdrawal()->list();
```

## PIX QR Code

### Criar QR Code PIX

```php
$pixQrCode = AbacatePay::pixQrCode()->create([
    'amount' => 10000, // R$ 100,00 em centavos
    'expires_in' => 3600, // Expira em 1 hora (opcional)
    'description' => 'Pagamento via PIX' // (opcional)
]);

// Acessar o QR Code
$qrCode = $pixQrCode->br_code; // Código PIX em formato texto
$qrCodeBase64 = $pixQrCode->br_code_base64; // QR Code em base64 para imagem
```

### Verificar Status do QR Code

```php
$pixQrCode = AbacatePay::pixQrCode()->check('pix_123');

// Verificar status
$status = $pixQrCode->status;
```

### Simular Pagamento (Apenas em Dev Mode)

```php
$pixQrCode = AbacatePay::pixQrCode()->simulatePayment('pix_123', [
    'source' => 'test'
]);
```

## Loja (Store)

### Obter Informações da Loja

```php
$store = AbacatePay::store()->get();

echo $store->name;
echo $store->email;
echo $store->document;
```

## Métodos de Pagamento

Atualmente suportados:

- **PIX** - Sistema de pagamento instantâneo brasileiro
- **CARD** - Pagamento com cartão de crédito/débito

## Formas de Uso

### Via Facade (Recomendado)

```php
use Billyfranklim\AbacatePay\Facades\AbacatePay;

$billing = AbacatePay::billing()->create($billingData);
```

### Via Container

```php
$billingClient = app('abacatepay.billing');
$billings = $billingClient->list();
```

### Via Injeção de Dependência

```php
use Billyfranklim\AbacatePay\AbacatePay;

class PaymentController
{
    public function __construct(
        private AbacatePay $abacatePay
    ) {}

    public function createBilling()
    {
        $billing = $this->abacatePay->billing()->create($billingData);
        return redirect($billing->url);
    }
}
```

## Tratamento de Erros

O pacote lança exceções específicas que você pode capturar:

```php
use Billyfranklim\AbacatePay\Exceptions\ApiException;
use Billyfranklim\AbacatePay\Exceptions\ConfigurationException;

try {
    $billing = AbacatePay::billing()->create($billingData);
} catch (ConfigurationException $e) {
    // Token não configurado
    logger()->error('Token AbacatePay não configurado');
} catch (ApiException $e) {
    // Erro na API
    logger()->error('Erro na API AbacatePay', [
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
}
```

## Recursos

- ✅ Gerenciamento de cobranças (criar, listar, buscar, criar link)
- ✅ Gerenciamento de clientes (criar, listar)
- ✅ Gerenciamento de cupons (criar, listar)
- ✅ Gerenciamento de saques (criar, listar, buscar)
- ✅ PIX QR Code (criar, verificar, simular pagamento)
- ✅ Informações da loja
- ✅ Suporte a múltiplos métodos de pagamento (PIX, CARD)
- ✅ Suporte a cobranças únicas e múltiplas
- ✅ Tratamento de erros robusto
- ✅ Logging integrado
- ✅ Type hints completos
- ✅ Exceções customizadas

## Testes

```bash
composer test
```

## Changelog

Por favor, veja [CHANGELOG](CHANGELOG.md) para mais informações sobre mudanças recentes.

## Contribuindo

Contribuições são bem-vindas! Por favor, abra uma issue ou envie um pull request.

## Segurança

Se você descobrir alguma vulnerabilidade de segurança, por favor envie um email para billyfranklim@gmail.com ao invés de usar o issue tracker.

## Créditos

- [Billyfranklim Avelino Pereira](https://github.com/billyfranklim1)
- [Todos os Contribuidores](../../contributors)

## Licença

A Licença MIT (MIT). Por favor, veja [Arquivo de Licença](LICENSE.md) para mais informações.

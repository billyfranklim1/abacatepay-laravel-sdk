# AbacatePay Laravel SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/billyfranklim/abacatepay-laravel-sdk.svg?style=flat-square)](https://packagist.org/packages/billyfranklim/abacatepay-laravel-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/billyfranklim/abacatepay-laravel-sdk.svg?style=flat-square)](https://packagist.org/packages/billyfranklim/abacatepay-laravel-sdk)

SDK Laravel para integração com a API do AbacatePay. Este pacote fornece uma interface simples e elegante para gerenciar clientes e cobranças através da API do AbacatePay.

## Requisitos

- PHP 8.4 ou superior
- Laravel 11 ou 12
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

## Uso

### Via Facade

```php
use VendorName\AbacatePay\Facades\AbacatePay;
use VendorName\AbacatePay\Resources\Billing;
use VendorName\AbacatePay\Resources\Billing\Product;
use VendorName\AbacatePay\Resources\Billing\Metadata as BillingMetadata;
use VendorName\AbacatePay\Enums\Billing\Frequencies;
use VendorName\AbacatePay\Enums\Billing\Methods;
use VendorName\AbacatePay\Resources\Customer;
use VendorName\AbacatePay\Resources\Customer\Metadata as CustomerMetadata;

// Criar uma cobrança
$billing = AbacatePay::billing()->create(new Billing([
    'frequency' => Frequencies::ONE_TIME,
    'methods' => [Methods::PIX],
    'products' => [
        new Product([
            'external_id' => 'prod_123',
            'name' => 'Produto A',
            'description' => 'Descrição do produto',
            'quantity' => 1,
            'price' => 10000 // em centavos
        ])
    ],
    'metadata' => new BillingMetadata([
        'return_url' => 'https://seusite.com/retorno',
        'completion_url' => 'https://seusite.com/sucesso'
    ]),
    'customer' => new Customer([
        'metadata' => new CustomerMetadata([
            'name' => 'João Silva',
            'cellphone' => '01912341234',
            'email' => 'joao@example.com',
            'tax_id' => '12345678900'
        ])
    ])
]));

// Listar cobranças
$billings = AbacatePay::billing()->list();

// Criar cliente
$customer = AbacatePay::customer()->create(new Customer([
    'metadata' => new CustomerMetadata([
        'name' => 'Maria Santos',
        'cellphone' => '01998765432',
        'email' => 'maria@example.com',
        'tax_id' => '98765432100'
    ])
]));

// Listar clientes
$customers = AbacatePay::customer()->list();
```

### Via Container

```php
use VendorName\AbacatePay\Clients\BillingClient;

$billingClient = app('abacatepay.billing');
$billings = $billingClient->list();
```

### Via Injeção de Dependência

```php
use VendorName\AbacatePay\AbacatePay;

class PaymentController
{
    public function __construct(
        private AbacatePay $abacatePay
    ) {}

    public function createBilling()
    {
        $billing = $this->abacatePay->billing()->create($billingData);
        // ...
    }
}
```

## Tratamento de Erros

O pacote lança exceções específicas que você pode capturar:

```php
use VendorName\AbacatePay\Exceptions\ApiException;
use VendorName\AbacatePay\Exceptions\ConfigurationException;

try {
    $billing = AbacatePay::billing()->create($billingData);
} catch (ConfigurationException $e) {
    // Token não configurado
    logger()->error($e->getMessage());
} catch (ApiException $e) {
    // Erro na API
    logger()->error('Erro na API AbacatePay', [
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
}
```

## Recursos

- ✅ Gerenciamento de cobranças (criar, listar)
- ✅ Gerenciamento de clientes (criar, listar)
- ✅ Suporte a múltiplos métodos de pagamento (PIX)
- ✅ Suporte a cobranças únicas e recorrentes
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

Contribuições são bem-vindas! Por favor, veja [CONTRIBUTING](CONTRIBUTING.md) para detalhes.

## Segurança

Se você descobrir alguma vulnerabilidade de segurança, por favor envie um email para billyfranklim@gmail.com ao invés de usar o issue tracker.

## Créditos

- [Billyfranklim Avelino Pereira](https://github.com/billyfranklim1)
- [Todos os Contribuidores](../../contributors)

## Licença

A Licença MIT (MIT). Por favor, veja [Arquivo de Licença](LICENSE.md) para mais informações.

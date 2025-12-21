# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Adicionado suporte completo para Cupons de Desconto (CouponClient)
- Adicionado suporte completo para Saques (WithdrawalClient)
- Adicionado suporte para informações da Loja (StoreClient)
- Adicionado suporte completo para PIX QR Code (PixQrCodeClient)
- Adicionado método `createLink()` ao BillingClient para criar links de pagamento
- Adicionado enum `MULTIPLE_PAYMENTS` ao Frequencies
- Adicionado enum `CARD` ao Methods
- Adicionados novos enums: DiscountKind, Coupon Status, Withdrawal Status, AccountType
- Adicionados testes de request para validar parâmetros enviados à API
- Adicionados testes completos para todas as novas funcionalidades

### Changed
- Atualizado namespace de `VendorName` para `Billyfranklim` em todo o projeto
- Refatorado BillingClient para usar método `buildRequestData()` compartilhado
- Melhorado tratamento de erros e logging
- Atualizado README com documentação completa de todas as funcionalidades

### Fixed
- Corrigido método `check()` do PixQrCodeClient para retornar `PixQrCode` ao invés de `?array`
- Removidos logs de debug do código de produção
- Corrigido método `simulatePayment()` do PixQrCodeClient para estar alinhado com SDK Node.js

## [1.0.0] - 2024-12-21

### Added
- Implementação inicial do SDK Laravel para AbacatePay
- Suporte para gerenciamento de cobranças (BillingClient)
- Suporte para gerenciamento de clientes (CustomerClient)
- Integração com Laravel Service Provider e Facades
- Sistema de exceções customizadas
- Testes unitários e de integração

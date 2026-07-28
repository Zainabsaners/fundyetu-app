# Support Sphere — M-Changa Inspired Crowdfunding Platform

## Overview

A digital fundraising platform (like M-Changa) built with Laravel, targeting the Kenyan and African market. Allows individuals and organizations to create fundraisers, collect donations via mobile money & cards, and withdraw funds transparently with treasurer oversight.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 11 + PHP 8.2+ |
| Frontend | Livewire (or Inertia + Vue) |
| Database | MySQL 8 / PostgreSQL 15 |
| Queue/Cache | Redis + Laravel Horizon |
| SMS | Africa's Talking |
| Payments | Safaricom M-Pesa (Daraja), Airtel Money, Flutterwave, PayPal |
| USSD | Africa's Talking USSD |
| Storage | AWS S3 / DigitalOcean Spaces |
| Hosting | DigitalOcean / AWS / Kenyan DC (icolo.io) |

---

## Core Features (MVP — Phases 1–3)

### Phase 1 — Foundation
- User system: registration (email + phone), phone OTP, roles (admin, fundraiser, donor)
- Campaign CRUD: create/edit/view with story, images, video, target amount, expiry
- Categories: Medical, Education, Emergency, Business, Funeral, Wedding, Community, Religious, Sports
- Public campaign pages with progress bar, patron list, donation buttons
- Media upload (images, video) to S3

### Phase 2 — Payments & Donations
- Payment gateway abstraction (M-Pesa, Airtel, Flutterwave cards)
- M-Pesa Daraja API: STK Push, C2B callbacks, B2C disbursement
- Donation flow: amount → method → payment → receipt
- Recurring donations via PayPal/Flutterwave subscriptions
- Campaign balance tracking & transaction statements

### Phase 3 — Withdrawals & Verification
- Document upload & KYC verification (admin reviews)
- Verification tiers: unverified/partial/full
- Withdrawal flow: initiate → treasurer approval (M-of-N) → admin review → disbursement
- Fee computation (platform fee, gateway fee, withdrawal fee)
- Disbursement via M-Pesa B2C or bank EFT

---

## Database Schema

```
users
├── id, name, email, phone, password, role, kyc_status, email_verified_at, phone_verified_at

campaigns
├── id, user_id, category_id, title, slug, story, target_amount, raised_amount
├── status (draft/pending_verification/active/paused/completed/cancelled)
├── expiry_date, cover_image, video_url, platform_fee_percent, verified_at

categories
├── id, name, slug, icon, description

campaign_documents
├── id, campaign_id, file_path, type, verified_at, verified_by

campaign_treasurers
├── id, campaign_id, user_id, can_approve_withdrawal

campaign_patrons
├── id, campaign_id, name, photo, message, sort_order

donations
├── id, campaign_id, donor_id (nullable), donor_name, donor_email, donor_phone
├── amount, fee, net_amount, payment_method, payment_ref, status, recurring_id

withdrawals
├── id, campaign_id, amount, fee, net_amount, destination_type, destination_ref
├── status (pending/treasurer_approved/admin_approved/disbursed/rejected)

withdrawal_approvals
├── id, withdrawal_id, treasurer_id, approved_at, notes

transactions
├── id, campaign_id, type (donation/withdrawal/fee/refund), amount, balance_before, balance_after

sms_logs
├── id, campaign_id, recipient, message, cost, status, provider_ref

payment_gateway_logs
├── id, gateway, endpoint, request_payload, response_payload, status, transaction_id
```

---

## Directory Structure

```
app/
├── Enums/
│   ├── CampaignStatus.php
│   ├── DonationStatus.php
│   └── WithdrawalStatus.php
├── Models/
│   ├── User.php
│   ├── Campaign.php
│   ├── Donation.php
│   ├── Withdrawal.php
│   ├── CampaignDocument.php
│   ├── CampaignTreasurer.php
│   ├── CampaignPatron.php
│   ├── Category.php
│   └── Transaction.php
├── Services/
│   ├── Payment/
│   │   ├── PaymentGatewayInterface.php
│   │   ├── MpesaGateway.php
│   │   ├── AirtelGateway.php
│   │   └── FlutterwaveGateway.php
│   ├── SMSService.php
│   ├── WithdrawalService.php
│   └── FeeCalculator.php
├── Jobs/
│   ├── ProcessMpesaCallback.php
│   ├── SendDonationReceipt.php
│   └── DisburseWithdrawal.php
├── Livewire/
│   ├── CampaignWizard.php
│   ├── DonationForm.php
│   └── WithdrawalForm.php
├── Http/
│   ├── Controllers/
│   │   ├── CampaignController.php
│   │   ├── DonationController.php
│   │   ├── WithdrawalController.php
│   │   ├── StatementController.php
│   │   ├── Payment/
│   │   │   ├── MpesaController.php
│   │   │   └── FlutterwaveController.php
│   │   └── Admin/
│   │       ├── CampaignController.php
│   │       └── WithdrawalController.php
resources/
├── views/
│   ├── livewire/
│   ├── campaigns/
│   ├── components/
│   └── layouts/
```

---

## Payment Flow

```
Donor → Campaign Page → Select Amount → Choose Payment Method
  ├─ M-Pesa:    STK Push → donor phone PIN → callback → confirm
  ├─ Airtel:    STK Push → donor phone PIN → callback → confirm
  ├─ Card:      Flutterwave/PayPal checkout → redirect → callback → confirm
  └─ On success: Update campaign balance → log transaction → send receipt
```

## Withdrawal Flow

```
Owner initiates withdrawal
  → Treasurers notified (SMS/email) for approval
  → M-of-N approvals received (default 2 of 3)
  → Admin reviews flagged/high-value withdrawals
  → Admin approves → disbursement via M-Pesa B2C or bank EFT
  → Balance updated → owner notified
```

## Fee Structure

| Fee Type | Rate |
|---|---|
| Platform Fee | Configurable % per campaign (default ~4.25%) |
| M-Pesa Payment | ~1% of donation |
| Card Payment | ~3.5% of donation |
| Withdrawal Fee | Fixed (e.g., KES 30) |
| SMS | Free first 250, then tiered per-SMS cost |

---

## Sprint Plan

| Sprint | Focus | Deliverables |
|---|---|---|
| S1 | Auth + Users | Registration, login, OTP, roles, profiles |
| S2 | Campaigns | CRUD, media upload, categories, public page |
| S3 | M-Pesa Integration | Daraja STK Push, C2B, callbacks, logging |
| S4 | Donations | Donation form, processing, receipts, statement |
| S5 | Verification | Document upload, admin verification, KYC tiers |
| S6 | Withdrawals | Initiation, treasurer approval, admin disbursement |
| S7 | Polish & Testing | Integration tests, edge cases, security review |

---

## Kenyan Market Considerations

- **M-Pesa Daraja API**: Requires Safaricom partnership (register as partner, get credentials)
- **USSD shortcode**: Need short code from Safaricom/Airtel or use Africa's Talking shared shortcode
- **Compliance**: CBK regulations, KYC requirements, Data Protection Act 2019
- **Tax**: Withholding tax on platform fees, 1.5% mobile money transaction tax
- **Hosting**: Consider local hosting (icolo.io, Safaricom Cloud) for M-Pesa callback latency

# Market_GiftCard

Magento 2 module that provides gift card functionality, allowing customers to purchase and redeem gift cards during checkout.

## Overview

The module creates and manages gift cards with unique codes that can be assigned to customers and redeemed against orders. Each gift card tracks its initial and current value, recipient details, and full usage history through the `market_gift_card` and `market_gift_card_usage` tables.

## Installation

### Manual

1. Copy the module to `app/code/Market/GiftCard`
2. Run the following commands:

```bash
bin/magento module:enable Market_GiftCard
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy
bin/magento cache:flush
```

### Via Composer

```bash
composer require market/module-gift-card
bin/magento module:enable Market_GiftCard
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy
bin/magento cache:flush
```

## Database Schema

### `market_gift_card`

Stores gift card records.

| Column | Type | Description |
|---|---|---|
| `id` | int | Primary key |
| `assigned_customer_id` | int | FK to `customer_entity` |
| `code` | varchar(255) | Unique gift card code |
| `status` | int | Card status (active, used, expired, etc.) |
| `initial_value` | decimal(12,4) | Original card value |
| `current_value` | decimal(12,4) | Remaining balance |
| `created_at` | timestamp | Creation date |
| `updated_at` | timestamp | Last updated date |
| `recipient_email` | varchar(255) | Recipient email address |
| `recipient_name` | varchar(255) | Recipient name |

### `market_gift_card_usage`

Tracks usage history per gift card.

| Column | Type | Description |
|---|---|---|
| `id` | int | Primary key |
| `gift_card_id` | int | FK to `market_gift_card` |
| `order_id` | int | FK to `sales_order` |
| `value_change` | decimal(20,6) | Amount used in this transaction |
| `notes` | text | Optional notes |
| `created_at` | timestamp | Usage date |

## Uninstalling

```bash
bin/magento module:uninstall Market_GiftCard
bin/magento setup:upgrade
bin/magento cache:flush
```

## Dependencies

- `Magento_Catalog`
- `Magento_Sales`
- `Magento_Customer`

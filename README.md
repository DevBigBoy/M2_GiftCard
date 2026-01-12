# Market_GiftCard

Magento 2 module that provides gift card functionality, allowing customers to purchase and redeem gift cards during checkout.

## Overview

The module creates and manages gift cards with unique codes that can be assigned to customers and redeemed against orders. Each gift card tracks its initial and current value, recipient details, and full usage history through the `market_gift_card` and `market_gift_card_usage` tables.

A custom `giftcard` product type (extending virtual products) lets merchants sell gift cards through the standard catalog, with support for custom amounts via the `is_custom_allowed` EAV attribute.

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

## Module Structure

```
Market/GiftCard/
├── Api/
│   ├── Data/
│   │   ├── GiftCardInterface.php
│   │   ├── GiftCardSearchResultsInterface.php
│   │   ├── GiftCardUsageInterface.php
│   │   └── GiftCardUsageSearchResultsInterface.php
│   ├── GiftCardRepositoryInterface.php
│   └── GiftCardUsageRepositoryInterface.php
├── Model/
│   ├── Attributes.php
│   ├── GiftCard.php
│   ├── GiftCardRepository.php
│   ├── GiftCardSearchResults.php
│   ├── GiftCardUsage.php
│   ├── GiftCardUsageRepository.php
│   ├── GiftCardUsageSearchResults.php
│   ├── ResourceModel/
│   │   ├── GiftCard.php
│   │   ├── GiftCard/Collection.php
│   │   ├── GiftCardUsage.php
│   │   └── GiftCardUsage/Collection.php
│   └── Type/
│       └── GiftCard.php
├── Setup/
│   └── Patch/Data/
│       ├── ApplyAttributesUpdate.php
│       └── CreateCustomAllowed.php
├── etc/
│   ├── db_schema.xml
│   ├── db_schema_whitelist.json
│   ├── di.xml
│   ├── module.xml
│   └── product_types.xml
├── composer.json
├── README.md
└── registration.php
```

## Database Schema

### `market_gift_card`

Stores gift card records.

| Column | Type | Description |
| --- | --- | --- |
| `id` | int | Primary key |
| `assigned_customer_id` | int | FK to `customer_entity` (CASCADE delete) |
| `code` | varchar(255) | Unique gift card code |
| `status` | int | Card status (active, used, expired, etc.) |
| `initial_value` | decimal(12,4) | Original card value |
| `current_value` | decimal(12,4) | Remaining balance |
| `recipient_email` | varchar(255) | Recipient email address |
| `recipient_name` | varchar(255) | Recipient name |
| `created_at` | timestamp | Creation date |
| `updated_at` | timestamp | Last updated date |

### `market_gift_card_usage`

Tracks usage history per gift card.

| Column | Type | Description |
| --- | --- | --- |
| `id` | int | Primary key |
| `gift_card_id` | int | FK to `market_gift_card` (CASCADE delete) |
| `order_id` | int | FK to `sales_order` (CASCADE delete) |
| `value_change` | decimal(20,6) | Amount used in this transaction |
| `notes` | text | Optional notes |
| `created_at` | timestamp | Usage date |

## Module Architecture

### Product Type

The `giftcard` product type extends `Magento\Catalog\Model\Product\Type\Virtual`, inheriting virtual product behavior (non-shippable, no weight). It is registered in `etc/product_types.xml` with the `SimpleProductPrice` indexer.

### Service Contracts (`Api/Data/`)

| Interface | Description |
| --- | --- |
| `GiftCardInterface` | Defines getters/setters for all gift card fields |
| `GiftCardUsageInterface` | Defines getters/setters for usage record fields |
| `GiftCardSearchResultsInterface` | Search results for gift card queries |
| `GiftCardUsageSearchResultsInterface` | Search results for usage queries |

### Repositories (`Api/`)

| Interface | Description |
| --- | --- |
| `GiftCardRepositoryInterface` | CRUD + `getByCode()` + `getList()` for gift cards |
| `GiftCardUsageRepositoryInterface` | CRUD + `getList()` for usage records |

Both repositories support `SearchCriteriaInterface` for filtered, sorted, and paginated queries.

### Models (`Model/`)

| Class | Description |
| --- | --- |
| `GiftCard` | Implements `GiftCardInterface`, extends `AbstractModel` |
| `GiftCardUsage` | Implements `GiftCardUsageInterface`, extends `AbstractModel` |
| `GiftCardRepository` | Implements `GiftCardRepositoryInterface` |
| `GiftCardUsageRepository` | Implements `GiftCardUsageRepositoryInterface` |
| `Type\GiftCard` | Custom product type extending `Virtual` |
| `Attributes` | Constants for EAV attribute codes |

### Resource Models (`Model/ResourceModel/`)

| Class | Table |
| --- | --- |
| `GiftCard` | `market_gift_card` |
| `GiftCard\Collection` | Collection for gift card records |
| `GiftCardUsage` | `market_gift_card_usage` |
| `GiftCardUsage\Collection` | Collection for usage records |

### Data Patches (`Setup/Patch/Data/`)

| Patch | Description |
| --- | --- |
| `ApplyAttributesUpdate` | Registers the `price` attribute for the `giftcard` product type |
| `CreateCustomAllowed` | Creates the `is_custom_allowed` EAV attribute (boolean, global scope) |

### Dependency Injection (`etc/di.xml`)

All service contract interfaces are mapped to their concrete implementations:

- `GiftCardInterface` → `Model\GiftCard`
- `GiftCardUsageInterface` → `Model\GiftCardUsage`
- `GiftCardRepositoryInterface` → `Model\GiftCardRepository`
- `GiftCardUsageRepositoryInterface` → `Model\GiftCardUsageRepository`
- `GiftCardSearchResultsInterface` → `Model\GiftCardSearchResults`
- `GiftCardUsageSearchResultsInterface` → `Model\GiftCardUsageSearchResults`

## Uninstalling

```bash
bin/magento module:uninstall Market_GiftCard
bin/magento setup:upgrade
bin/magento cache:flush
```

## Dependencies

- `Magento_Catalog`
- `Magento_Sales`

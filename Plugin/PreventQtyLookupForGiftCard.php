<?php

namespace Market\GiftCard\Plugin;

use Magento\CatalogInventory\Model\Quote\Item\QuantityValidator;
use Magento\Framework\Event\Observer;
use Market\GiftCard\Model\Type\GiftCard;

class PreventQtyLookupForGiftCard
{
    public function aroundValidate(
        QuantityValidator $subject,
        callable $proceed,
        Observer $observer
    ) {

        $quoteItem = $observer->getEvent()->getItem();
        if (!$quoteItem ||
            !$quoteItem->getProductId() ||
            !$quoteItem->getQuote()
        ) {
            return;
        }

        $product = $quoteItem->getProduct();

        if ($product->getTypeId() === GiftCard::TYPE_CODE) {
            return;
        }

        $proceed($observer);
    }
}

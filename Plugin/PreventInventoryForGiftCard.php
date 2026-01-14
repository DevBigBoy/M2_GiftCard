<?php

namespace Market\GiftCard\Plugin;

use Magento\InventoryCatalogApi\Model\GetProductTypesBySkusInterface;
use Magento\InventorySales\Model\IsProductSalableForRequestedQtyCondition\IsProductSalableForRequestedQtyConditionChain;
use Magento\InventorySalesApi\Api\Data\ProductSalableResultInterface;
use Magento\InventorySalesApi\Api\Data\ProductSalableResultInterfaceFactory;
use Market\GiftCard\Model\Type\GiftCard;

class PreventInventoryForGiftCard
{
    private ProductSalableResultInterfaceFactory $productSalableResultFactory;
    private GetProductTypesBySkusInterface $getProductTypeBySku;

    public function __construct(
        ProductSalableResultInterfaceFactory $productSalableResultFactory,
        GetProductTypesBySkusInterface   $getProductTypeBySku
    ) {
        $this->productSalableResultFactory = $productSalableResultFactory;
        $this->getProductTypeBySku = $getProductTypeBySku;
    }
    public function aroundExecute(
        IsProductSalableForRequestedQtyConditionChain $subject,
        callable $proceed,
        string $sku,
        ...$args
    ): ProductSalableResultInterface {

        if ($this->getProductTypeBySku->execute([$sku])[$sku]  === GiftCard::TYPE_CODE) {
            return $this->productSalableResultFactory->create(['errors' => []]);
        }

        return $proceed($sku, ...$args);

    }
}

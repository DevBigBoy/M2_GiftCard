<?php

namespace Market\GiftCard\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface GiftCardUsageSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Market\GiftCard\Api\Data\GiftCardUsageInterface[]
     */
    public function getItems();

    /**
     * @param \Market\GiftCard\Api\Data\GiftCardUsageInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

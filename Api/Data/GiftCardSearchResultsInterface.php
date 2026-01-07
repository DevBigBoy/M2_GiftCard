<?php

namespace Market\GiftCard\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface GiftCardSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Market\GiftCard\Api\Data\GiftCardInterface[]
     */
    public function getItems();

    /**
     * @param \Market\GiftCard\Api\Data\GiftCardInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

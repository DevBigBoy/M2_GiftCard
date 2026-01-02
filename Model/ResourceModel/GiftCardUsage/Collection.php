<?php

namespace Market\GiftCard\Model\ResourceModel\GiftCardUsage;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Market\GiftCard\Model\GiftCardUsage as GiftCardUsageModel;
use Market\GiftCard\Model\ResourceModel\GiftCardUsage as GiftCardUsageResourceModel;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(
            GiftCardUsageModel::class,
            GiftCardUsageResourceModel::class
        );
    }
}

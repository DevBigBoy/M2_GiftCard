<?php

namespace Market\GiftCard\Model\ResourceModel\GiftCard;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Market\GiftCard\Model\GiftCard as GiftCardModel;
use Market\GiftCard\Model\ResourceModel\GiftCard as GiftCardResourceModel;

class Collection extends AbstractCollection
{

    protected function _construct()
    {
        $this->_init(
            GiftCardModel::class,
            GiftCardResourceModel::class
        );
    }
}

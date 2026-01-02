<?php

namespace Market\GiftCard\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Market\GiftCard\Api\Data\GiftCardUsageInterface;

class GiftCardUsage extends AbstractDb
{

    protected function _construct()
    {
        $this->_init(
            GiftCardUsageInterface::TABLE_NAME,
            GiftCardUsageInterface::ID
        );
    }
}

<?php

namespace Market\GiftCard\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Market\GiftCard\Api\Data\GiftCardInterface;

class GiftCard extends AbstractDb
{

    protected function _construct()
    {
        $this->_init(
            GiftCardInterface::TABLE_NAME,
            GiftCardInterface::ID
        );
    }
}

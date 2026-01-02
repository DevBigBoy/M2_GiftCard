<?php

namespace Market\GiftCard\Model;

use Magento\Catalog\Model\AbstractModel;
use Market\GiftCard\Api\Data\GiftCardUsageInterface;

class GiftCardUsage extends AbstractModel implements GiftCardUsageInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel\GiftCardUsage::class);
    }


    public function getGiftCardId():int
    {
        return (int)$this->getData(self::GIFT_CARD_ID);
    }
    public function setGiftCardId(int $value): void
    {
        $this->setData(self::GIFT_CARD_ID, $value);
    }

    public function getOrderId():int
    {
        return (int)$this->getData(self::ORDER_ID);
    }
    public function setOrderId(int $value): void
    {
        $this->setData(self::ORDER_ID, $value);
    }

    public function getValueChange(): float
    {
        return (float)$this->getData(self::VALUE_CHANGE);
    }
    public function setValueChange(float $value): void
    {
        $this->setData(self::VALUE_CHANGE, $value);
    }

    public function getNotes(): string
    {
        return (string)$this->getData(self::NOTES);
    }
    public function setNotes(string $value): void
    {
        $this->setData(self::NOTES, $value);
    }

    public function getCreatedAt(): \DateTime
    {
        return new \DateTime($this->getData(self::CREATED_AT));
    }

    public function setCreatedAt(\DateTime $value): void
    {
        $this->setData(self::CREATED_AT, $value->format('Y-m-d H:i:s'));
    }

}

<?php

namespace Market\GiftCard\Api\Data;

interface GiftCardUsageInterface
{

    public const TABLE_NAME = 'market_gift_card_usage';
    public const ID = 'id';
    public const GIFT_CARD_ID = 'gift_card_id';
    public const ORDER_ID = 'order_id';
    public const VALUE_CHANGE = 'value_change';
    public const NOTES = 'notes';
    public const CREATED_AT = 'created_at';


    /**
     * @return int
     */
    public function getId();

    /**
     * @param $id
     * @return void
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getGiftCardId():int;

    /**
     * @param int $value
     * @return void
     */
    public function setGiftCardId(int $value): void;

    /**
     * @return int
     */
    public function getOrderId():int;

    /**
     * @param int $value
     * @return void
     */
    public function setOrderId(int $value): void;

    /**
     * @return float
     */
    public function getValueChange(): float;

    /**
     * @param float $value
     * @return void
     */
    public function setValueChange(float $value): void;

    /**
     * @return string
     */
    public function getNotes(): string;

    /**
     * @param string $value
     * @return void
     */
    public function setNotes(string $value): void;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime;

    /**
     * @param \DateTime $value
     * @return void
     */
    public function setCreatedAt(\DateTime $value): void;

}

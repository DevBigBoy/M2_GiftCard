<?php

namespace Market\GiftCard\Api\Data;

interface GiftCardInterface
{
    public const TABLE_NAME = 'market_gift_card';

    public const ID = 'id';
    public const ASSIGNED_CUSTOMER_ID = 'assigned_customer_id';
    public const CODE = 'code';
    public const STATUS = 'status';
    public const INITIAL_VALUE = 'initial_value';
    public const CURRENT_VALUE = 'current_value';
    public const RECIPIENT_EMAIL = 'recipient_email';
    public const RECIPIENT_NAME = 'recipient_name';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';


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
    public function getCustomerId(): int;

    /**
     * @param int $value
     * @return void
     */
    public function setCustomerId(int $value): void;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @param string $value
     * @return void
     */
    public function setCode(string $value): void;

    /**
     * @return int
     */
    public function getStatus(): int;

    /**
     * @param int $value
     * @return void
     */
    public function setStatus(int $value): void;

    /**
     * @return float
     */
    public function getInitialValue(): float;

    /**
     * @param float $value
     * @return void
     */
    public function setInitialValue(float $value): void;

    /**
     * @return float
     */
    public function getCurrentValue(): float;

    /**
     * @param float $value
     * @return void
     */
    public function setCurrentValue(float $value): void;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime;

    /**
     * @param \DateTime $value
     * @return void
     */
    public function setCreatedAt(\DateTime $value): void;

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime;

    /**
     * @param \DateTime $value
     * @return void
     */
    public function setUpdatedAt(\DateTime $value): void;

    /**
     * @param string $value
     * @return void
     */
    public function setRecipientName(string $value): void;

    /**
     * @return string
     */
    public function getRecipientName(): string;

    /**
     * @param string $value
     * @return void
     */
    public function setRecipientEmail(string $value): void;

    /**
     * @return string
     */
    public function getRecipientEmail():string;
}

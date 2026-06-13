<?php

namespace Market\GiftCard\Model\Quote\Total;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Market\GiftCard\Api\GiftCardRepositoryInterface;

class GiftCard extends AbstractTotal
{
    private GiftCardRepositoryInterface $giftCardRepository;

    public function __construct(
        GiftCardRepositoryInterface $giftCardRepository
    ) {
        $this->giftCardRepository = $giftCardRepository;
        $this->setCode('gift_card');
    }

    /**
     * Collect gift card amount and apply it to the address totals.
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ): self {

        parent::collect($quote, $shippingAssignment, $total);

        $giftCardId =  $quote->getExtensionAttributes()->getGiftCardId();

        if (!$giftCardId
        || $shippingAssignment->getShipping()->getAddress()->getAddressType() !== 'shipping') {
            return $this;
        }

        try {
            $giftCard = $this->giftCardRepository->getById($giftCardId);
        } catch (NoSuchEntityException $exception) {
            return $this;
        }

        if ($giftCard->getCurrentValue() <= 0) {
            return $this;
        }

        $giftCardAmount = 0 -  min($giftCard->getCurrentValue(), array_sum($total->getAllTotalAmounts()));
        $giftCardBaseAmount = 0 - min($giftCard->getCurrentValue(), array_sum($total->getAllBaseTotalAmounts()));

        $total->addTotalAmount($this->getCode(), $giftCardAmount);
        $total->addBaseTotalAmount($this->getCode(), $giftCardBaseAmount);

        $total->setData($this->getCode() . '_amount', $giftCardAmount);
        $total->setData('base_' . $this->getCode() . '_amount', $giftCardBaseAmount);

        $quote->setData($this->getCode() . '_amount', $giftCardAmount);
        $quote->setData('base_' . $this->getCode() . '_amount', $giftCardBaseAmount);

        return $this;
    }

    public function fetch(Quote $quote, Total $total): array
    {
        return [
            'code' => $this->getCode(),
            'title' => $this->getLabel(),
            'value' => $total->getData($this->getCode() . '_amount'),
        ];
    }

    public function getLabel(): string
    {
        return __('Gift Card');
    }

    public function getCode(): string
    {
        return 'gift_card';
    }
}

<?php
declare(strict_types=1);

namespace Market\GiftCard\Model;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface as LayoutProcessor;
use Magento\Checkout\Model\Session\Proxy as CheckoutSession;
use Magento\Framework\Exception\NoSuchEntityException;
use Market\GiftCard\Api\GiftCardRepositoryInterface;

class GiftCardCheckoutInitializer implements LayoutProcessor
{
    private CheckoutSession $checkoutSession;
    private GiftCardRepositoryInterface $giftCardRepository;

    public function __construct(
        CheckoutSession $checkoutSession,
        GiftCardRepositoryInterface $giftCardRepository
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->giftCardRepository = $giftCardRepository;
    }

    /**
     * Inject the gift card components into the checkout js layout.
     *
     * @param array $jsLayout
     * @return array
     */
    public function process($jsLayout)
    {
        $quote = $this->checkoutSession->getQuote();

        if (!$quote->getExtensionAttributes() || !$quote->getExtensionAttributes()->getGiftCardId()) {
            return $jsLayout;
        }

        try {
            $giftCard = $this->giftCardRepository->getById($quote->getExtensionAttributes()->getGiftCardId());
        } catch (NoSuchEntityException $e) {
            return $jsLayout;
        }

        $jsLayout["components"]["checkout"]["children"]["sidebar"]["children"]["summary"]
            ["children"]["itemsAfter"]["children"]["giftcard"]["config"]["code"] = $giftCard->getCode();

        $jsLayout["components"]["checkout"]["children"]["sidebar"]["children"]["summary"]
            ["children"]["itemsAfter"]["children"]["giftcard"]["config"]["isApplied"] = 1;

        return $jsLayout;
    }
}

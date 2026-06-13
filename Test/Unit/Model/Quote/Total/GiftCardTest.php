<?php

namespace Market\GiftCard\Test\Unit\Model\Quote\Total;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\CartExtensionInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Api\Data\ShippingInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Address\Total;
use Market\GiftCard\Api\Data\GiftCardInterface;
use Market\GiftCard\Api\GiftCardRepositoryInterface;
use Market\GiftCard\Model\Quote\Total\GiftCard;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GiftCardTest extends TestCase
{
    /** @var GiftCardRepositoryInterface&MockObject */
    private $giftCardRepository;

    /** @var GiftCard */
    private $collector;

    /** @var int */
    private $originalErrorReporting;

    /**
     * This codebase runs on PHP 8.4 while the installed Magento vendor code still uses
     * implicit-nullable parameters (e.g. `Type $x = null`). PHP 8.4 emits E_DEPRECATED for
     * those, and the unit-test bootstrap promotes any reported deprecation to a fatal
     * exception. Mocking or instantiating those legacy classes here would therefore explode
     * on vendor noise unrelated to the code under test, so we drop E_DEPRECATED for the test.
     */
    protected function setUp(): void
    {
        $this->originalErrorReporting = error_reporting();
        error_reporting($this->originalErrorReporting & ~E_DEPRECATED);

        $this->giftCardRepository = $this->getMockBuilder(GiftCardRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->collector = new GiftCard($this->giftCardRepository);
    }

    protected function tearDown(): void
    {
        error_reporting($this->originalErrorReporting);
    }

    private function mockConcrete(string $class, array $realMethods, array $magicMethods = []): MockObject
    {
        $builder = $this->getMockBuilder($class)->disableOriginalConstructor();
        if ($realMethods) {
            $builder->onlyMethods($realMethods);
        }
        if ($magicMethods) {
            $builder->addMethods($magicMethods);
        }
        return $builder->getMock();
    }

    /**
     * Build a quote mock with the given gift_card_id extension attribute.
     */
    private function createQuote(?int $giftCardId): Quote
    {
        $extension = $this->getMockBuilder(CartExtensionInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getGiftCardId'])
            ->getMockForAbstractClass();
        $extension->method('getGiftCardId')->willReturn($giftCardId);

        $quote = $this->mockConcrete(Quote::class, ['getExtensionAttributes']);
        $quote->method('getExtensionAttributes')->willReturn($extension);

        return $quote;
    }

    /**
     * Build a shipping assignment whose address has the given type, seeded with prior totals.
     */
    private function createShippingAssignment(string $addressType): ShippingAssignmentInterface
    {
        $address = $this->mockConcrete(Address::class, [], ['getAddressType']);
        $address->method('getAddressType')->willReturn($addressType);

        $shipping = $this->getMockBuilder(ShippingInterface::class)->getMockForAbstractClass();
        $shipping->method('getAddress')->willReturn($address);

        $assignment = $this->getMockBuilder(ShippingAssignmentInterface::class)->getMockForAbstractClass();
        $assignment->method('getShipping')->willReturn($shipping);

        return $assignment;
    }

    private function createGiftCard(float $currentValue): GiftCardInterface
    {
        $giftCard = $this->getMockBuilder(GiftCardInterface::class)->getMockForAbstractClass();
        $giftCard->method('getCurrentValue')->willReturn($currentValue);

        return $giftCard;
    }

    /**
     * A total seeded with a prior subtotal so the collector has a balance to cap against.
     */
    private function createTotalWithSubtotal(float $subtotal): Total
    {
        $total = new Total();
        $total->addTotalAmount('subtotal', $subtotal);
        $total->addBaseTotalAmount('subtotal', $subtotal);

        return $total;
    }

    public function testGiftCardSmallerThanSubtotalDeductsFullCardValue(): void
    {
        $quote = $this->createQuote(5);
        $assignment = $this->createShippingAssignment('shipping');
        $total = $this->createTotalWithSubtotal(100.0);

        $this->giftCardRepository->expects($this->once())
            ->method('getById')
            ->with(5)
            ->willReturn($this->createGiftCard(30.0));

        $this->collector->collect($quote, $assignment, $total);

        // -30 applied on top of the +100 subtotal => running total 70.
        $this->assertSame(-30.0, $total->getAllTotalAmounts()['gift_card']);
        $this->assertSame(70.0, array_sum($total->getAllTotalAmounts()));
        $this->assertSame(-30.0, $quote->getData('gift_card_amount'));
        $this->assertSame(-30.0, $quote->getData('base_gift_card_amount'));
    }

    public function testGiftCardLargerThanSubtotalIsCappedAtBalance(): void
    {
        $quote = $this->createQuote(7);
        $assignment = $this->createShippingAssignment('shipping');
        $total = $this->createTotalWithSubtotal(40.0);

        $this->giftCardRepository->method('getById')->willReturn($this->createGiftCard(500.0));

        $this->collector->collect($quote, $assignment, $total);

        // Card worth 500 but only 40 owed => deduct 40, never go negative.
        $this->assertSame(-40.0, $total->getAllTotalAmounts()['gift_card']);
        $this->assertSame(0.0, array_sum($total->getAllTotalAmounts()));
    }

    public function testNoGiftCardIdIsNoop(): void
    {
        $quote = $this->createQuote(null);
        $assignment = $this->createShippingAssignment('shipping');
        $total = $this->createTotalWithSubtotal(100.0);

        $this->giftCardRepository->expects($this->never())->method('getById');

        $this->collector->collect($quote, $assignment, $total);

        // parent::collect() seeds gift_card => 0, so the contribution must be zero and the
        // running subtotal untouched. The quote amount must never be set on a no-op.
        $this->assertNoDeduction($total);
        $this->assertNull($quote->getData('gift_card_amount'));
    }

    public function testBillingAddressIsSkipped(): void
    {
        $quote = $this->createQuote(5);
        $assignment = $this->createShippingAssignment('billing');
        $total = $this->createTotalWithSubtotal(100.0);

        $this->giftCardRepository->expects($this->never())->method('getById');

        $this->collector->collect($quote, $assignment, $total);

        $this->assertNoDeduction($total);
    }

    public function testMissingGiftCardIsNoop(): void
    {
        $quote = $this->createQuote(99);
        $assignment = $this->createShippingAssignment('shipping');
        $total = $this->createTotalWithSubtotal(100.0);

        $this->giftCardRepository->method('getById')
            ->willThrowException(new NoSuchEntityException(__('Not found')));

        $this->collector->collect($quote, $assignment, $total);

        $this->assertNoDeduction($total);
    }

    public function testZeroValueGiftCardIsNoop(): void
    {
        $quote = $this->createQuote(5);
        $assignment = $this->createShippingAssignment('shipping');
        $total = $this->createTotalWithSubtotal(100.0);

        $this->giftCardRepository->method('getById')->willReturn($this->createGiftCard(0.0));

        $this->collector->collect($quote, $assignment, $total);

        $this->assertNoDeduction($total);
    }

    /**
     * Assert the collector contributed nothing: gift card line is zero and the seeded
     * subtotal of 100 is left intact.
     */
    private function assertNoDeduction(Total $total): void
    {
        $amounts = $total->getAllTotalAmounts();
        $this->assertSame(0.0, (float)($amounts['gift_card'] ?? 0.0));
        $this->assertSame(100.0, array_sum($amounts));
    }
}

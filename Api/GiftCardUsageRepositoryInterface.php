<?php

namespace Market\GiftCard\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Market\GiftCard\Api\Data\GiftCardUsageInterface;
use Market\GiftCard\Api\Data\GiftCardUsageSearchResultsInterface;

interface GiftCardUsageRepositoryInterface
{
    /**
     * @param int $id
     * @return \Market\GiftCard\Api\Data\GiftCardUsageInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): GiftCardUsageInterface;

    /**
     * @param \Market\GiftCard\Api\Data\GiftCardUsageInterface $giftCardUsage
     * @return \Market\GiftCard\Api\Data\GiftCardUsageInterface
     * @throws CouldNotSaveException
     */
    public function save(GiftCardUsageInterface $giftCardUsage): GiftCardUsageInterface;

    /**
     * @param \Market\GiftCard\Api\Data\GiftCardUsageInterface $giftCardUsage
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(GiftCardUsageInterface $giftCardUsage): bool;

    /**
     * @param int $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $id): bool;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Market\GiftCard\Api\Data\GiftCardUsageSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): GiftCardUsageSearchResultsInterface;
}

<?php

namespace Market\GiftCard\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Market\GiftCard\Api\Data\GiftCardUsageInterface;
use Market\GiftCard\Api\Data\GiftCardUsageSearchResultsInterface;
use Market\GiftCard\Api\Data\GiftCardUsageSearchResultsInterfaceFactory;
use Market\GiftCard\Api\GiftCardUsageRepositoryInterface;
use Market\GiftCard\Model\ResourceModel\GiftCardUsage as GiftCardUsageResource;
use Market\GiftCard\Model\ResourceModel\GiftCardUsage\CollectionFactory;

class GiftCardUsageRepository implements GiftCardUsageRepositoryInterface
{
    /**
     * @var GiftCardUsageResource
     */
    private GiftCardUsageResource $resource;

    /**
     * @var GiftCardUsageFactory
     */
    private GiftCardUsageFactory $giftCardUsageFactory;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var GiftCardUsageSearchResultsInterfaceFactory
     */
    private GiftCardUsageSearchResultsInterfaceFactory $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    public function __construct(
        GiftCardUsageResource $resource,
        GiftCardUsageFactory $giftCardUsageFactory,
        CollectionFactory $collectionFactory,
        GiftCardUsageSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->giftCardUsageFactory = $giftCardUsageFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): GiftCardUsageInterface
    {
        $giftCardUsage = $this->giftCardUsageFactory->create();
        $this->resource->load($giftCardUsage, $id);

        if (!$giftCardUsage->getId()) {
            throw new NoSuchEntityException(__('Gift card usage with ID "%1" does not exist.', $id));
        }

        return $giftCardUsage;
    }

    /**
     * @inheritDoc
     */
    public function save(GiftCardUsageInterface $giftCardUsage): GiftCardUsageInterface
    {
        try {
            $this->resource->save($giftCardUsage);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save gift card usage: %1', $e->getMessage()), $e);
        }

        return $giftCardUsage;
    }

    /**
     * @inheritDoc
     */
    public function delete(GiftCardUsageInterface $giftCardUsage): bool
    {
        try {
            $this->resource->delete($giftCardUsage);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete gift card usage: %1', $e->getMessage()), $e);
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $id): bool
    {
        return $this->delete($this->getById($id));
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): GiftCardUsageSearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}

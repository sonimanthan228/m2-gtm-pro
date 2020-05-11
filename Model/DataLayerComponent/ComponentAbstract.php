<?php

namespace Hatimeria\GtmPro\Model\DataLayerComponent;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\Product;
use Magento\Checkout\Model\Session;
use Magento\Framework\Registry;
use Magento\Framework\App\Request\Http;
use Magento\Catalog\Helper\Product\Configuration;
use Magento\Quote\Model\Quote\Item;
use Magento\Review\Model\ResourceModel\Review\Collection as ReviewCollection;
use Magento\Review\Model\ResourceModel\Review\CollectionFactory as ReviewCollectionFactory;
use Magento\Framework\Session\Generic;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\CatalogSearch\Model\Advanced;
use Magento\Review\Model\ReviewFactory;
use Psr\Log\LoggerInterface;
use Hatimeria\GtmPro\Api\DataLayerComponentInterface;
use Hatimeria\GtmPro\Model\Config;

/**
 * Class ComponentAbstract
 */
abstract class ComponentAbstract implements DataLayerComponentInterface
{
    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var Http
     */
    protected $request;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Configuration
     */
    protected $productHelper;

    /**
     * @var ReviewCollection
     */
    protected $reviewCollection;

    /**
     * @var ReviewCollectionFactory
     */
    protected $reviewsColFactory;

    /**
     * @var ReviewSummaryFactory
     */
    protected $reviewSummaryFactory;

    /**
     * @var Generic
     */
    protected $session;

    /**
     * @var RedirectInterface
     */
    protected $redirect;

    /**
     * @var Advanced
     */
    protected $catalogSearchAdvanced;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ReviewFactory
     */
    protected $reviewFactory;

    /**
     * ComponentAbstract constructor.
     * @param StoreManagerInterface $storeManager
     * @param Session $checkoutSession
     * @param Registry $registry
     * @param Http $request
     * @param Config $config
     * @param Configuration $productHelper
     * @param ReviewCollection $reviewCollection
     * @param ReviewCollectionFactory $reviewCollectionFactory
     * @param ReviewFactory $reviewFactory
     * @param Generic $session
     * @param RedirectInterface $redirect
     * @param Advanced $catalogSearchAdvanced
     * @param LoggerInterface $logger
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Session $checkoutSession,
        Registry $registry,
        Http $request,
        Config $config,
        Configuration $productHelper,
        ReviewCollection $reviewCollection,
        ReviewCollectionFactory $reviewCollectionFactory,
        ReviewFactory $reviewFactory,
        Generic $session,
        RedirectInterface $redirect,
        Advanced $catalogSearchAdvanced,
        LoggerInterface $logger
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->storeManager = $storeManager;
        $this->coreRegistry = $registry;
        $this->request = $request;
        $this->config = $config;
        $this->productHelper = $productHelper;
        $this->reviewsColFactory = $reviewCollectionFactory;
        $this->reviewFactory = $reviewFactory;
        $this->session = $session;
        $this->redirect = $redirect;
        $this->catalogSearchAdvanced = $catalogSearchAdvanced;
        $this->logger = $logger;
    }

    /**
     * @param $eventData
     * @return mixed
     */
    abstract public function getComponentData($eventData);

    /**
     * @return string
     */
    abstract protected function getEventName();

    /**
     * @param $eventData
     * @return array
     */
    public function getData($eventData)
    {
        try {
            if ($data = $this->getComponentData($eventData)) {
                $data['event'] = $this->getEventName();
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $data;
    }

    /**
     * @param Product $product
     * @return string
     */
    protected function getCategoryName(Product $product)
    {
        $categoryCollection = $product->getCategoryCollection()
            ->addAttributeToSelect('name');

        if ($categoryCollection->count() > 0) {
            foreach ($categoryCollection as $category) {
                /** @var Category $category */
                $categories[] = $category->getName();
            }

            return implode('|', $categories);
        }

        return '';
    }

    /**
     * @param Product $product
     * @return string
     */
    protected function getVariant(Item $item)
    {
         //todo consider use Magento\Quote\Model\Cart\Totals\ItemConverter::getFormattedOptionValue()
         // for get Variants with the keys
         // or add Labels from $option['label']
        if ($item->getHasChildren()) {
            $variants = [];
            $options = $this->productHelper->getOptions($item);
            foreach ($options as $option) {
                $variants[$option['label']] = $option['value'];
            }

            return implode('|', $variants);
        }

        return '';
    }

    /**
     * @param $price
     * @return float
     */
    protected function formatPrice($price)
    {
        return (double)number_format($price, 2, '.', '');
    }

    /**
     * @param Product $product
     * @return array|string|null
     */
    protected function getBrand(Product $product)
    {
        $brand = '';
        if ($product->getBrand()) {
            $brand = $product->getAttributeText('brand');
        } elseif ($product->getManufacturer()) {
            $brand = $product->getAttributeText('maunfacturer');
        }

        return $brand;
    }

    /**
     * @param Product $product
     * @return ReviewCollection
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getReviewsCollection(Product $product)
    {
        if (null === $this->reviewCollection) {
            $this->reviewCollection = $this->reviewsColFactory->create()->addStoreFilter(
                $this->storeManager->getStore()->getId()
            )->addStatusFilter(
                \Magento\Review\Model\Review::STATUS_APPROVED
            )->addEntityFilter(
                'product',
                $product->getId()
            )->setDateOrder();
        }
        return $this->reviewCollection;
    }

    /**
     * @param Product $product
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getRatingSummary(Product $product)
    {
        if ($product->getRatingSummary() === null) {
            $this->reviewFactory->create()->getEntitySummary($product, $this->storeManager->getStore()->getId());
        }

        return $product->getRatingSummary();
    }
}

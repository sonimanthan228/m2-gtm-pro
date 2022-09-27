<?php

namespace Hatimeria\GtmPro\Model\DataLayerComponent;

use Hatimeria\GtmPro\Model\Config;
use Magento\Catalog\Helper\Product\Configuration;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Catalog\Model\Product;
use Magento\CatalogSearch\Model\Advanced;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\Session\Generic;
use Magento\Quote\Model\Quote\Item;
use Magento\Quote\Model\QuoteFactory;
use Magento\Review\Model\ReviewFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractComponent
{
    protected StoreManagerInterface $storeManager;
    protected Session $checkoutSession;
    protected Registry $registry;
    protected Http $request;
    protected Config $config;
    protected Configuration $productHelper;
    protected ReviewFactory $reviewFactory;
    protected Generic $session;
    protected RedirectInterface $redirect;
    protected Advanced $catalogSearchAdvanced;
    protected LoggerInterface $logger;
    protected QuoteFactory $quoteFactory;
    protected CategoryCollectionFactory $categoryCollectionFactory;
    protected array $productCategories = [];
    protected array $productsStructure = [];
    protected array $cartItemsStructure = [];

    /**
     * ComponentAbstract constructor.
     * @param StoreManagerInterface $storeManager
     * @param Session $checkoutSession
     * @param Registry $registry
     * @param Http $request
     * @param Config $config
     * @param Configuration $productHelper
     * @param ReviewFactory $reviewFactory
     * @param Generic $session
     * @param RedirectInterface $redirect
     * @param Advanced $catalogSearchAdvanced
     * @param LoggerInterface $logger
     * @param QuoteFactory $quoteFactory
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Session $checkoutSession,
        Registry $registry,
        Http $request,
        Config $config,
        Configuration $productHelper,
        ReviewFactory $reviewFactory,
        Generic $session,
        RedirectInterface $redirect,
        Advanced $catalogSearchAdvanced,
        LoggerInterface $logger,
        QuoteFactory $quoteFactory,
        CategoryCollectionFactory $categoryCollectionFactory
    ) {
        $this->storeManager = $storeManager;
        $this->checkoutSession = $checkoutSession;
        $this->registry = $registry;
        $this->request = $request;
        $this->config = $config;
        $this->productHelper = $productHelper;
        $this->reviewFactory = $reviewFactory;
        $this->session = $session;
        $this->redirect = $redirect;
        $this->catalogSearchAdvanced = $catalogSearchAdvanced;
        $this->logger = $logger;
        $this->quoteFactory = $quoteFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }
    /**
     * @param $eventData
     * @return mixed
     */
    abstract public function getComponentData($eventData): ?array;

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return static::EVENT_NAME;
    }

    /**
     * @param Product $product
     * @return string
     * @throws LocalizedException
     */
    protected function getCategoryName(Product $product)
    {
        $categories = $this->getCategories($product);

        if (!empty($categories)) {
            return implode('|', $categories);
        }

        return '';
    }

    /**
     * @param Product $product
     * @return array
     * @throws LocalizedException
     */
    protected function getCategories(Product $product): array
    {
        if (!array_key_exists($product->getId(), $this->productCategories)) {
            $rootCategoryId = $this->storeManager->getStore()->getRootCategoryId();
            $categoryCollection = $product->getCategoryCollection()
                ->addAttributeToSelect('name')
                ->setOrder('level', 'DESC');

            $categories = [];
            $childCategories = [];
            if ($categoryCollection->count() > 0) {
                foreach ($categoryCollection as $category) {
                    /** @var Category $category */
                    $categoryPath = preg_replace("(.*\/$rootCategoryId\/)", "", $category->getPath());
                    $childCategories = array_merge($childCategories, explode('/', $categoryPath));
                }
            }
            if (!empty($childCategories)) {
                /** @var CategoryCollection $categoryCollection */
                $parentCategoryCollection = $this->categoryCollectionFactory->create();
                $parentCategoryCollection->addIdFilter(array_unique($childCategories));
                $parentCategoryCollection->addAttributeToSelect('name');
                foreach ($categoryCollection as $category) {
                    $categoryPathName = '';
                    foreach ($category->getPathIds() as $categoryId) {
                        if ($categoryId !== $category->getId()
                            && $parentCategory = $parentCategoryCollection->getItemById($categoryId)
                        ) {
                            $categoryPathName .= $parentCategory->getName() . ' > ';
                        }
                    }
                    $categoryPathName .= $category->getName();
                    $categories[] = $this->trimPathName($categoryPathName);
                }
            }
            $this->productCategories[$product->getId()] = $categories;
        }

        return $this->productCategories[$product->getId()];
    }

    /**
     * @param Item $item
     * @return string
     */
    protected function getVariant(Item $item): string
    {
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
    protected function formatPrice($price): float
    {
        return (double)number_format($price, 2, '.', '');
    }

    /**
     * @param Product $product
     * @return array|string|null
     * @throws NoSuchEntityException
     */
    protected function getBrand(Product $product)
    {
        $brand = '';
        $brandAttribute = $this->config->getBrandAttribute();
        if ($brandAttribute && $product->getData($brandAttribute)) {
            $brand = $product->getAttributeText($brandAttribute);
        }

        return $brand;
    }

    /**
     * @param Product $product
     * @return mixed
     * @throws NoSuchEntityException
     */
    protected function getReviewsCount(Product $product)
    {
        if ($product->getReviewsCount() === null) {
            $this->reviewFactory->create()->getEntitySummary($product, $this->storeManager->getStore()->getId());
        }

        return $product->getReviewsCount();
    }

    /**
     * @param Product $product
     * @return mixed
     * @throws NoSuchEntityException
     */
    protected function getRatingSummary(Product $product)
    {
        if ($product->getRatingSummary() === null) {
            $this->reviewFactory->create()->getEntitySummary($product, $this->storeManager->getStore()->getId());
        }

        return $product->getRatingSummary();
    }

    /**
     * @param Product $product
     * @return string
     */
    public function getName(Product $product): string
    {
        $name = strip_tags($product->getName());
        return str_replace("'", "", $name);
    }

    protected function trimPathName(string $categoryPathName)
    {
        if (strlen($categoryPathName) <= 100) {
            return $categoryPathName;
        }

        return trim(
            substr(
                $categoryPathName,
                strpos(
                    $categoryPathName,
                    '>',
                    strlen($categoryPathName) - 100
                ) + 2
            )
        );
    }
}
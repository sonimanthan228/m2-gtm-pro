<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\Product;
use Magento\Checkout\Model\Session;
use Magento\Framework\Registry;
use Magento\Framework\App\Request\Http;
use Magento\Catalog\Helper\Product\Configuration;
use Magento\Quote\Model\Quote\Item;
use Magento\Framework\Session\Generic;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\CatalogSearch\Model\Advanced;
use Magento\Review\Model\ReviewFactory;
use Magento\Quote\Model\QuoteFactory;
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
     * @var QuoteFactory
     */
    protected $quoteFactory;

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
        QuoteFactory $quoteFactory
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->storeManager = $storeManager;
        $this->coreRegistry = $registry;
        $this->request = $request;
        $this->config = $config;
        $this->productHelper = $productHelper;
        $this->reviewFactory = $reviewFactory;
        $this->session = $session;
        $this->redirect = $redirect;
        $this->catalogSearchAdvanced = $catalogSearchAdvanced;
        $this->logger = $logger;
        $this->quoteFactory = $quoteFactory;
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
            $data = [];
            if ($data = $this->getComponentData($eventData)) {
                $data['event'] = $this->getEventName();
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        } catch (\Throwable $e) {
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
     * @param $item
     * @return string
     */
    protected function getVariant(Item $item)
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
        if ($brandAttribute = $this->config->getBrandAttribute()) {
            $brand = $product->getAttributeText($this->config->getBrandAttribute());
        }

        return $brand;
    }

    /**
     * @param Product $product
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
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
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getRatingSummary(Product $product)
    {
        if ($product->getRatingSummary() === null) {
            $this->reviewFactory->create()->getEntitySummary($product, $this->storeManager->getStore()->getId());
        }

        return $product->getRatingSummary();
    }

    /**
     * @param string $name
     * @return string
     */
    protected function processName($name)
    {
        $name = strip_tags($name);
        return str_replace("'", "", $name);
    }
}

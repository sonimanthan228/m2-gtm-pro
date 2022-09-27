<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\UA;

use Hatimeria\GtmPro\Model\Config;
use Magento\Catalog\Helper\Product\Configuration;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\CatalogSearch\Model\Advanced;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\Session\Generic;
use Magento\Quote\Model\QuoteFactory;
use Magento\Review\Model\ReviewFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Design\Theme\ThemeProviderInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Customer\Model\SessionFactory;
use Psr\Log\LoggerInterface;

/**
 * Class CoreData
 */
class CoreData extends ComponentAbstract
{
    const EVENT_NAME = 'dataLayer-initialized';

    protected ProductMetadataInterface $productMetaData;
    protected ScopeConfigInterface $scopeConfig;
    protected ThemeProviderInterface $themeProvider;
    protected GroupRepositoryInterface $groupRepository;
    protected SessionFactory $customerSessionFactory;

    /**
     * CoreData constructor.
     * @param Registry $registry
     * @param StoreManagerInterface $storeManager
     * @param ProductMetadataInterface $productMetadata
     * @param ScopeConfigInterface $scopeConfig
     * @param ThemeProviderInterface $themeProvider
     * @param Http $request
     * @param GroupRepositoryInterface $groupRepository
     * @param SessionFactory $customerSessionFactory
     * @param Session $checkoutSession
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
        Registry $registry,
        StoreManagerInterface $storeManager,
        ProductMetadataInterface $productMetadata,
        ScopeConfigInterface $scopeConfig,
        ThemeProviderInterface $themeProvider,
        Http $request,
        GroupRepositoryInterface $groupRepository,
        SessionFactory $customerSessionFactory,
        Session $checkoutSession,
        Config $config,
        Configuration $productHelper,
        ReviewFactory $reviewFactory,
        Generic $session,
        RedirectInterface $redirect,
        Advanced $catalogSearchAdvanced,
        LoggerInterface $logger,
        QuoteFactory $quoteFactory,
        CategoryCollectionFactory $collectionFactory
    ) {
        $this->productMetaData = $productMetadata;
        $this->scopeConfig = $scopeConfig;
        $this->themeProvider = $themeProvider;
        $this->groupRepository = $groupRepository;
        $this->customerSessionFactory = $customerSessionFactory;
        parent::__construct(
            $storeManager,
            $checkoutSession,
            $registry,
            $request,
            $config,
            $productHelper,
            $reviewFactory,
            $session,
            $redirect,
            $catalogSearchAdvanced,
            $logger,
            $quoteFactory,
            $collectionFactory
        );

    }

    /**
     * @param $eventData
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getComponentData($eventData): ?array
    {
        $data = [];
        $data['siteVersion']  = $this->getSiteVersion();
        $data['pageCategory'] = $this->request->getFullActionName();
        $customerSession = $this->customerSessionFactory->create();
        $data['userId']       =
            $customerSession->isLoggedIn() ? $customerSession->getCustomerId() : 'guest';
        $data['customerType'] =
            $customerSession->isLoggedIn() ?
                $this->getCustomerGroupNameById((int)$customerSession->getCustomerGroupId()) : 'Not Logged In';
        $data['loggedIn']     = $customerSession->isLoggedIn() ? 'Logged In' : 'Not Logged In';

        return $data;
    }

    /**
     * @param Product $product
     * @return string
     * @throws LocalizedException
     */
    protected function getCategoryName(Product $product): string
    {
        $categories = $product->getCategoryCollection()
            ->addAttributeToSelect('name');

        if ($categories->count() > 0) {
            return $categories->getFirstItem()->getData('name');
        }

        return '';
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    protected function getSiteVersion(): string
    {
        return $this->getMagentoVersion(). ' '. $this->getThemeVersion();
    }

    /**
     * @return string
     */
    protected function getMagentoVersion(): string
    {
        return $this->productMetaData->getName()
            . ' ' .$this->productMetaData->getEdition()
            . ' ' . $this->productMetaData->getVersion();
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getThemeVersion(): string
    {
        $themeId = $this->scopeConfig->getValue(
            \Magento\Framework\View\DesignInterface::XML_PATH_THEME_ID,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );

        /** @var $theme \Magento\Framework\View\Design\ThemeInterface */
        $theme = $this->themeProvider->getThemeById($themeId);

        return 'Theme: '.$theme->getThemeTitle();
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return self::EVENT_NAME;
    }

    /**
     * @param int $id
     * @return string
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    protected function getCustomerGroupNameById(int $id): string
    {
        $group = $this->groupRepository->getById($id);
        return  $group->getCode();
    }
}

<?php

namespace Hatimeria\GtmEe\Model\DataLayerComponent;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Design\Theme\ThemeProviderInterface;
use Magento\Customer\Model\Session;
use Hatimeria\GtmEe\Api\DataLayerComponentInterface;

/**
 * Class CoreData
 * @package Hatimeria\GtmEe\Model\DataLayerComponent
 */
class CoreData extends ComponentAbstract implements DataLayerComponentInterface
{
    const EVENT_NAME = 'dataLayer-initialized';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ProductMetadataInterface
     */
    protected $productMetaData;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var ThemeProviderInterface
     */
    protected $themeProvider;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var Http
     */
    protected $request;

    /**
     * CoreData constructor.
     * @param Registry $registry
     * @param StoreManagerInterface $storeManager
     * @param ProductMetadataInterface $productMetadata
     * @param ScopeConfigInterface $scopeConfig
     * @param ThemeProviderInterface $themeProvider
     * @param Session $customerSession
     */
    public function __construct(
        Registry $registry,
        StoreManagerInterface $storeManager,
        ProductMetadataInterface $productMetadata,
        ScopeConfigInterface $scopeConfig,
        ThemeProviderInterface $themeProvider,
        Session $customerSession,
        Http $request
    ) {
        $this->coreRegistry = $registry;
        $this->storeManager = $storeManager;
        $this->productMetaData = $productMetadata;
        $this->scopeConfig = $scopeConfig;
        $this->themeProvider = $themeProvider;
        $this->customerSession = $customerSession;
        $this->request = $request;
    }

    /**
     * @param $eventData
     * @return array
     */
   public function getComponentData($eventData) {
       $data = [];
       $data['siteVersion']  = $this->getSiteVersion();
       $data['pageCategory'] = $this->request->getFullActionName();
       $data['pageTemplate'] = '';
       $data['userId']       = $this->customerSession->isLoggedIn() ? $this->customerSession->getCustomerId() : 'guest';
       $data['customerType'] = $this->customerSession->getData('group');
       $data['loggedIn']     = $this->customerSession->isLoggedIn() ? 'Logged In' : 'Not Logged In';

       return $data;
   }

    /**
     * @param Product $product
     * @return string
     */
    protected function getCategoryName(Product $product)
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
     */
    protected function getSiteVersion()
    {
        return $this->getMagentoVersion(). ' '. $this->getThemeVersion();
    }

    /**
     * @return string
     */
    protected function getMagentoVersion()
    {
        return $this->productMetaData->getName()
            . ' ' .$this->productMetaData->getEdition()
            . ' ' . $this->productMetaData->getVersion();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getThemeVersion()
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
    protected function getEventName()
    {
        return self::EVENT_NAME;
    }
}

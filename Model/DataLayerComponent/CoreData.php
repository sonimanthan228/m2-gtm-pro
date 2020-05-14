<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Design\Theme\ThemeProviderInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Customer\Model\SessionFactory;
use Hatimeria\GtmPro\Api\DataLayerComponentInterface;

/**
 * Class CoreData
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
     * @var Http
     */
    protected $request;

    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * @var SessionFactory
     */
    protected $customerSessionFactory;

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
     */
    public function __construct(
        Registry $registry,
        StoreManagerInterface $storeManager,
        ProductMetadataInterface $productMetadata,
        ScopeConfigInterface $scopeConfig,
        ThemeProviderInterface $themeProvider,
        Http $request,
        GroupRepositoryInterface $groupRepository,
        SessionFactory $customerSessionFactory
    ) {
        $this->coreRegistry = $registry;
        $this->storeManager = $storeManager;
        $this->productMetaData = $productMetadata;
        $this->scopeConfig = $scopeConfig;
        $this->themeProvider = $themeProvider;
        $this->request = $request;
        $this->groupRepository = $groupRepository;
        $this->customerSessionFactory = $customerSessionFactory;
    }

    /**
     * @param $eventData
     * @return array
     */
    public function getComponentData($eventData)
    {

        $data = [];
        $data['siteVersion']  = $this->getSiteVersion();
        $data['pageCategory'] = $this->request->getFullActionName();
        $customerSession = $this->customerSessionFactory->create();
        $data['userId']       =
            $customerSession->isLoggedIn() ? $customerSession->getCustomerId() : 'guest';
        $data['customerType'] =
            $customerSession->isLoggedIn() ?
                $this->getCustomerGroupNameById($customerSession->getCustomerGroupId()) : 'Not Logged In';
        $data['loggedIn']     = $customerSession->isLoggedIn() ? 'Logged In' : 'Not Logged In';

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

    /**
     * @param $id
     * @return string
     */
    protected function getCustomerGroupNameById($id)
    {
        $group = $this->groupRepository->getById($id);
        return  $group->getCode();
    }
}

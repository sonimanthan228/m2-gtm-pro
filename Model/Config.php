<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Config
 */
class Config
{
    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_MODULE_ENABLED
        = 'hatimeria_gtmpro/general/enabled';
    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_CONTAINER_ID
        = 'hatimeria_gtmpro/general/container_id';
    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_VERSION
        = 'hatimeria_gtmpro/general/version';

    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_PRODUCT_IMPRESSION_ENABLED
        = 'hatimeria_gtmpro/event/product_impression_enabled';
    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_PRODUCT_IMPRESSION_TRACK_CLASS
        = 'hatimeria_gtmpro/event/product_impression_track_class';

    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_PRODUCT_CLICK_ENABLED
        = 'hatimeria_gtmpro/event/product_click_enabled';
    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_PRODUCT_CLICK_ELEMENT_CLASS =
        'hatimeria_gtmpro/event/product_click_element_class';
    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_PRODUCT_CLICK_LINK_CLASS
        = 'hatimeria_gtmpro/event/product_click_link_class';

    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_FORM_TRACKING_ENABLED
        = 'hatimeria_gtmpro/event/form_tracking_enabled';
    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_FORM_TRACKING_FORM_DATA
        = 'hatimeria_gtmpro/event/form_tracking_form_data';

    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_TRANSACTION_ENABLED
        = 'hatimeria_gtmpro/event/transactions_enabled';
    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_TRANSACTION_AFFILIATION
        = 'hatimeria_gtmpro/event/transaction_affiliation';

    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_ADD_TO_CART_TRACKING_ENABLED
        = 'hatimeria_gtmpro/event/add_to_cart_tracking_enabled';

    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_SEARCH_TRACKING_ENABLED
        = 'hatimeria_gtmpro/event/search_tracking_enabled';

    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_PRODUCT_VIEW_TRACKING_ENABLED
        = 'hatimeria_gtmpro/event/product_view_tracking_enabled';

    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_ADD_PRODUCT_REVIEW_TRACKING_ENABLED
        = 'hatimeria_gtmpro/event/add_product_review_tracking_enabled';

    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_ADD_TO_WISHLIST_TRACKING_ENABLED
        = 'hatimeria_gtmpro/event/add_to_wishlist_tracking_enabled';

    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_ADD_TO_COMPARE_TRACKING_ENABLED
        = 'hatimeria_gtmpro/event/add_to_compare_tracking_enabled';

    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_CHECKOUT_STEPS_TRACKING_ENABLED
        = 'hatimeria_gtmpro/event/checkout_steps_tracking_enabled';

    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_PROMOTION_TRACKING_ENABLED
        = 'hatimeria_gtmpro/event/promotion_tracking_enabled';

    const XML_CONFIG_PATH_HATIMERIA_GTMPRO_BRAND_ATTRIBUTE
        = 'hatimeria_gtmpro/event/brand_attribute';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var Json
     */
    protected $serializer;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Json $serializer
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Json $serializer,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->serializer = $serializer;
        $this->storeManager = $storeManager;
    }

    /**
     * @return string
     */
    public function getContainerId():string
    {
        return $this->scopeConfig->getValue(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_CONTAINER_ID,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return bool
     */
    public function isModuleEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_MODULE_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }
    public function getVersion(): int
    {
        return (int)$this->scopeConfig->getValue(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_VERSION,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return bool
     */
    public function isProductImpressionTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_PRODUCT_IMPRESSION_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return mixed
     */
    public function getProductImpressionTrackClass()
    {
        return $this->scopeConfig->getValue(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_PRODUCT_IMPRESSION_TRACK_CLASS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return bool
     */
    public function isProductClickTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_PRODUCT_CLICK_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return mixed
     */
    public function getProductClickElementClass()
    {
        return  $this->scopeConfig->getValue(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_PRODUCT_CLICK_ELEMENT_CLASS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return array
     */
    public function getProductClickLinkClasses()
    {
        return explode(
            ',',
            $this->scopeConfig->getValue(
                self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_PRODUCT_CLICK_LINK_CLASS,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $this->storeManager->getStore()->getId()
            )
        );
    }

    /**
     * @return bool
     */
    public function isFormTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_FORM_TRACKING_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return array
     */
    public function getFormTrackingData()
    {
        $serializedData = $this->scopeConfig->getValue(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_FORM_TRACKING_FORM_DATA,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
        $data = empty($serializedData) ? false : $this->serializer->unserialize($serializedData);
        foreach ($data as &$row) {
            $row['form_field_ids'] = explode(',', $row['form_field_ids']);
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function isTransactionTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_TRANSACTION_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return mixed
     */
    public function getTransactionAffiliation()
    {
        return  $this->scopeConfig->getValue(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_TRANSACTION_AFFILIATION,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return bool
     */
    public function isAddToCartTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_ADD_TO_CART_TRACKING_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return bool
     */
    public function isSearchTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_SEARCH_TRACKING_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return bool
     */
    public function isProductViewTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_PRODUCT_VIEW_TRACKING_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return bool
     */
    public function isAddProductReviewTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_ADD_PRODUCT_REVIEW_TRACKING_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return bool
     */
    public function isAddToWishlistTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_ADD_TO_WISHLIST_TRACKING_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return bool
     */
    public function isAddToCompareTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_ADD_TO_COMPARE_TRACKING_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return bool
     */
    public function isCheckoutStepsTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_CHECKOUT_STEPS_TRACKING_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return bool
     */
    public function isPromotionTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_EVENT_PROMOTION_TRACKING_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBrandAttribute()
    {
        return  $this->scopeConfig->getValue(
            self::XML_CONFIG_PATH_HATIMERIA_GTMPRO_BRAND_ATTRIBUTE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }
}

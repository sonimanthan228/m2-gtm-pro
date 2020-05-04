<?php

namespace Hatimeria\GtmEe\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Config
 */
class Config
{
    const XML_CONFIG_PATH_HATIMERIA_GTMEE_MODULE_ENABLED
        = 'hatimeria_gtmee/general/enabled';
    const XML_CONFIG_PATH_HATIMERIA_GTMEE_CONTAINER_ID
        = 'hatimeria_gtmee/general/container_id';

    const XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_PRODUCT_IMPRESSION_ENABLED
        = 'hatimeria_gtmee/event/product_impression_enabled';
    const XML_CONFIG_PATH_HATIMERIA_GTMEE_PRODUCT_IMPRESSION_TRACK_CLASS
        = 'hatimeria_gtmee/event/product_impression_track_class';

    const XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_PRODUCT_CLICK_ENABLED
        = 'hatimeria_gtmee/event/product_click_enabled';
    const XML_CONFIG_PATH_HATIMERIA_GTMEE_PRODUCT_CLICK_ELEMENT_CLASS =
        'hatimeria_gtmee/event/product_click_element_class';
    const XML_CONFIG_PATH_HATIMERIA_GTMEE_PRODUCT_CLICK_LINK_CLASS
        = 'hatimeria_gtmee/event/product_click_link_class';

    const XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_FORM_TRACKING_ENABLED
        = 'hatimeria_gtmee/event/form_tracking_enabled';
    const XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_FORM_TRACKING_FORM_DATA
        = 'hatimeria_gtmee/event/form_tracking_form_data';

    const XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_TRANSACTION_ENABLED
        = 'hatimeria_gtmee/event/transaction';
    const XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_TRANSACTION_AFFILIATION
        = 'hatimeria_gtmee/event/transaction_affiliation';

    const XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_ADD_TO_CART_TRACKING_ENABLED
        = 'hatimeria_gtmee/event/add_to_cart_tracking_enabled';

    const XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_SEARCH_TRACKING_ENABLED
        = 'hatimeria_gtmee/event/search_tracking_enabled';

    const XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_PRODUCT_VIEW_TRACKING_ENABLED
        = 'hatimeria_gtmee/event/product_view_tracking_enabled';

    const XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_ADD_PRODUCT_REVIEW_TRACKING_ENABLED
        = 'hatimeria_gtmee/event/add_product_review_tracking_enabled';

    const XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_ADD_TO_WISHLIST_TRACKING_ENABLED
        = 'hatimeria_gtmee/event/add_to_wishlist_tracking_enabled';

    const XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_ADD_TO_COMPARE_TRACKING_ENABLED
        = 'hatimeria_gtmee/event/add_to_compare_tracking_enabled';

    const XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_CHECKOUT_STEPS_TRACKING_ENABLED
        = 'hatimeria_gtmee/event/checkout_steps_tracking_enabled';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var Json
     */
    protected $serializer;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Json $serializer
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Json $serializer
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->serializer = $serializer;
    }

    /**
     * @return string
     */
    public function getContainerId():string
    {
        return $this->scopeConfig->getValue(self::XML_CONFIG_PATH_HATIMERIA_GTMEE_CONTAINER_ID);
    }

    /**
     * @return bool
     */
    public function isModuleEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_CONFIG_PATH_HATIMERIA_GTMEE_MODULE_ENABLED);
    }

    /**
     * @return bool
     */
    public function isProductImpressionTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_PRODUCT_IMPRESSION_ENABLED);
    }

    /**
     * @return mixed
     */
    public function getProductImpressionTrackClass()
    {
        return $this->scopeConfig->getValue(self::XML_CONFIG_PATH_HATIMERIA_GTMEE_PRODUCT_IMPRESSION_TRACK_CLASS);
    }

    /**
     * @return bool
     */
    public function isProductClickTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_PRODUCT_CLICK_ENABLED);
    }

    /**
     * @return mixed
     */
    public function getProductClickElementClass()
    {
        return  $this->scopeConfig->getValue(self::XML_CONFIG_PATH_HATIMERIA_GTMEE_PRODUCT_CLICK_ELEMENT_CLASS);
    }

    /**
     * @return array
     */
    public function getProductClickLinkClasses()
    {
        return explode(
            ',',
            $this->scopeConfig->getValue(self::XML_CONFIG_PATH_HATIMERIA_GTMEE_PRODUCT_CLICK_LINK_CLASS)
        );
    }

    /**
     * @return bool
     */
    public function isFormTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_FORM_TRACKING_ENABLED);
    }

    /**
     * @return array
     */
    public function getFormTrackingData()
    {
        $serializedData = $this->scopeConfig->getValue(
            self::XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_FORM_TRACKING_FORM_DATA
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
            self::XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_TRANSACTION_ENABLED
        );
    }

    /**
     * @return mixed
     */
    public function getTransactionAffiliation()
    {
        return  $this->scopeConfig->getValue(
            self::XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_TRANSACTION_AFFILIATION
        );
    }

    /**
     * @return bool
     */
    public function isAddToCartTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_ADD_TO_CART_TRACKING_ENABLED
        );
    }

    /**
     * @return bool
     */
    public function isSearchTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_SEARCH_TRACKING_ENABLED
        );
    }

    /**
     * @return bool
     */
    public function isProductViewTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_PRODUCT_VIEW_TRACKING_ENABLED
        );
    }

    /**
     * @return bool
     */
    public function isAddProductReviewTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_ADD_PRODUCT_REVIEW_TRACKING_ENABLED
        );
    }

    /**
     * @return bool
     */
    public function isAddToWishlistTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_ADD_TO_WISHLIST_TRACKING_ENABLED
        );
    }

    /**
     * @return bool
     */
    public function isAddToCompareTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_ADD_TO_COMPARE_TRACKING_ENABLED
        );
    }

    /**
     * @return bool
     */
    public function isCheckoutStepsTrackingEnabled():bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_HATIMERIA_GTMEE_EVENT_CHECKOUT_STEPS_TRACKING_ENABLED
        );
    }
}

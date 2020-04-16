<?php

namespace Hatimeria\GtmEe\Model\DataLayerComponent;

use Hatimeria\GtmEe\Api\DataLayerComponentInterface;

/**
 * Class ReviewAdd
 * @package Hatimeria\GtmEe\Model\DataLayerComponent
 */
class ReviewAdd extends ComponentAbstract implements DataLayerComponentInterface
{

    const EVENT_NAME = 'rate-product';

    /**
     * @return string
     */
    protected function getEventName()
    {
        return self::EVENT_NAME;
    }

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData)
    {
        $data = [];
        if ($this->config->isAddProductReviewTrackingEnabled()
            && $reviewData = json_decode($this->session->getGtmEeAddProductReviewData(), true)) {
            //todo add more review data if needed
            $data['product_id'] = $reviewData['product_id'];
            $this->cleanSessionGtmEeAddProductReviewData();
        }

        return $data;
   }

    /**
     * @return void
     */
   protected function cleanSessionGtmEeAddProductReviewData()
   {
       $this->session->setGtmEeAddProductReviewData(false);
   }

    /**
     * @param $data
     */
    public function processReview($data)
    {
        $data['product_id'] = $this->coreRegistry->registry('product')->getId();
        $this->session->setGtmEeAddProductReviewData(json_encode($data));
    }
}

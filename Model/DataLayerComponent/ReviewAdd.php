<?php

namespace Hatimeria\GtmPro\Model\DataLayerComponent;

use Hatimeria\GtmPro\Api\DataLayerComponentInterface;

/**
 * Class ReviewAdd
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
            && $reviewData = json_decode($this->session->getGtmProAddProductReviewData(), true)) {
            //todo add more review data if needed
            $data['product_id'] = $reviewData['product_id'];
            $this->cleanSessionGtmProAddProductReviewData();
        }

        return $data;
    }

    /**
     * @return void
     */
    protected function cleanSessionGtmProAddProductReviewData()
    {
        $this->session->setGtmProAddProductReviewData(false);
    }

    /**
     * @param $data
     */
    public function processReview($data)
    {
        $data['product_id'] = $this->coreRegistry->registry('product')->getId();
        $this->session->setGtmProAddProductReviewData(json_encode($data));
    }
}

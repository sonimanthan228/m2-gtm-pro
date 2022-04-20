<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

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
        $reviewData = $this->session->getGtmProAddProductReviewData();
        if ($this->config->isAddProductReviewTrackingEnabled()
            && $reviewData ) {
            //todo add more review data if needed
            $reviewData = json_decode($reviewData, true);
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

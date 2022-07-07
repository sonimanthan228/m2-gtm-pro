<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\UA;

/**
 * Class ReviewAdd
 */
class ReviewAdd extends ComponentAbstract
{
    const EVENT_NAME = 'rate-product';
    
    /**
     * @return string
     */
    public function getEventName(): string
    {
        return self::EVENT_NAME;
    }

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData): ?array
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
        $data['product_id'] = $this->registry->registry('product')->getId();
        $this->session->setGtmProAddProductReviewData(json_encode($data));
    }
}

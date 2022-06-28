<?php

namespace Hatimeria\GtmPro\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Version implements ArrayInterface
{
    const UA = 0;
    const GA4 = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            self::UA => 'Universal Analytics',
            self::GA4 => 'Google Analytics 4',
        ];
    }
}
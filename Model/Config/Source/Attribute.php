<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;

/**
 * Class Attribute
 */
class Attribute implements ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    private $attributeCollectionFactory;

    /**
     * @param  \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory
     */
    public function __construct(
        CollectionFactory $attributeCollectionFactory
    ) {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $result = [];
        $result[] = [
            'value' => '',
            'label' => '-NONE-'
        ];

        $attributesCollection = $this->attributeCollectionFactory->create();
        foreach ($attributesCollection as $attribute) {
            $result[] = [
                'value' => $attribute->getAttributeCode(),
                'label' => $attribute->getFrontendLabel()
            ];
        }

        return $result;
    }
}

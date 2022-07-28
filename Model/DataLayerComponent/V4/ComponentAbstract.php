<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\V4;

use Hatimeria\GtmPro\Model\DataLayerComponent\AbstractComponent;
use Magento\Framework\Exception\LocalizedException;
use Magento\Catalog\Model\Product;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Sales\Model\Order\Item as OrderItem;

/**
 * Class ComponentAbstract
 */
abstract class ComponentAbstract extends AbstractComponent
{
    /**
     * @param Product $product
     * @param bool $withQuantity
     * @return array
     * @throws LocalizedException
     */
    public function getProductStructure(Product $product, bool $withQuantity = true): array
    {
        $structureKey = $product->getId() . '_' . (int)$withQuantity;
        if (!isset($this->productsStructure[$structureKey])) {
            $structure = [
                'item_id'    => $product->getId(),
                'item_name'  => $this->getName($product),
                'currency'   => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
                'price'      => $this->formatPrice($product->getFinalPrice()),
                'item_brand' => $this->getBrand($product),
            ];
            $number = '';
            foreach($this->getCategories($product) as $categoryName) {
                $structure['item_category' . $number] = $categoryName;
                $number = $number === '' ? 2 : $number + 1;
            }

            if ($withQuantity) {
                $structure['quantity'] = $product->getQty() ?: 1;
            }

            $this->productsStructure[$structureKey] = $structure;
        }

        return $this->productsStructure[$structureKey];
    }

    /**
     * @param QuoteItem|OrderItem $item
     * @return array
     * @throws LocalizedException
     */
    public function getCartItemStructure($item): array
    {
        $structureKey = $item->getId().'_'.$item->getProductId();
        if (!isset($this->cartItemsStructure[$structureKey])) {
            $product = $item->getProduct();
            $structure = [
                'item_id'      => $item->getProductId(),
                'item_name'    => $this->getName($product),
                'currency'     => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
                'price'        => $this->formatPrice($item->getPriceInclTax() ?? $item->getPrice()),
                'discount'     => $this->formatPrice($item->getDiscountAmount()),
                'quantity'     => (float)($item->getQty() ?: $item->getQtyOrdered()),
                'item_brand'   => $this->getBrand($product),
                'item_variant' => $this->getItemVariant($item)
            ];
            $number = '';
            foreach($this->getCategories($product) as $categoryName) {
                $structure['item_category' . $number] = $categoryName;
                $number = $number === '' ? 2 : $number + 1;
            }

            $this->cartItemsStructure[$structureKey] = $structure;
        }

        return $this->cartItemsStructure[$structureKey];
    }

    protected function getItemVariant($item): ?string
    {
        return $item instanceof QuoteItem ? $this->getQuoteItemVariant($item) : $this->getOrderItemVariant($item);
    }

    protected function getQuoteItemVariant(QuoteItem $item): ?string
    {
        if ($item->getProductType() === 'configurable') {
            $options = $this->productHelper->getOptions($item);

            return ltrim(array_reduce($options, function ($carry, $option) {
                return $carry . ', ' . $option['value'];
            }, ''), ', ');
        }

        return null;
    }

    protected function getOrderItemVariant(OrderItem $item): ?string
    {
        if ($item->getProductType() === 'configurable') {
            $options = $item->getProductOptionByCode('attributes_info');

            return ltrim(array_reduce($options, function ($carry, $option) {
                return $carry . ', ' . $option['value'];
            }, ''), ', ');
        }

        return null;
    }
}

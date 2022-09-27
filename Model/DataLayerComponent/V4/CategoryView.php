<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\V4;

use Hatimeria\GtmPro\Model\Config;
use Magento\Catalog\Helper\Product\Configuration;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\CatalogSearch\Model\Advanced;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\Session\Generic;
use Magento\Quote\Model\QuoteFactory;
use Magento\Review\Model\ReviewFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ProductView
 */
class CategoryView extends ComponentAbstract
{
    const EVENT_NAME = 'view_item_list';

    /**
     * @var Resolver
     */
    protected $catalogLayer;

    public function __construct(
        StoreManagerInterface $storeManager,
        Session $checkoutSession,
        Registry $registry,
        Http $request,
        Config $config,
        Configuration $productHelper,
        ReviewFactory $reviewFactory,
        Generic $session,
        RedirectInterface $redirect,
        Advanced $catalogSearchAdvanced,
        LoggerInterface $logger,
        QuoteFactory $quoteFactory,
        CategoryCollectionFactory $collectionFactory,
        Resolver $layerResolver
    ) {
        parent::__construct(
            $storeManager,
            $checkoutSession,
            $registry,
            $request,
            $config,
            $productHelper,
            $reviewFactory,
            $session,
            $redirect,
            $catalogSearchAdvanced,
            $logger,
            $quoteFactory,
            $collectionFactory
        );
        $this->catalogLayer = $layerResolver->get();
    }

    /**
     * @param $eventData
     * @return array
     * @throws NoSuchEntityException
     */
    public function getComponentData($eventData): ?array
    {
        $data = [];
        if ($this->request->getFullActionName() === 'catalog_category_view'
            && $category = $this->registry->registry('current_category')
        ) {
            /** @var Category $category */
            $data['ecommerce'] = [
                'item_list_name' => 'Listing',
                'items'    => [],
            ];
            $id  = 2;
            foreach ($category->getParentCategories() as $parentCategory) {
                $data['ecommerce']['item_list_name_' . $id++] = $parentCategory->getName();
                if (6 === $id) {
                    break;
                }
            }
            if (6 > $id && $data['ecommerce']['item_list_name_' . ($id - 1)] !== $category->getName()) {
                $data['ecommerce']['item_list_name_' . $id] = $category->getName();
            }

            $i = 1;
            foreach($this->getProductCollection() as $product) {
                $data['ecommerce']['items'][] = array_merge(
                    $this->getProductStructure($product, true),
                    ['index' => $i++]
                );
            }
        }

        return $data;
    }

    protected function getProductCollection(): Collection
    {
        return $this->catalogLayer->getProductCollection();
    }
}

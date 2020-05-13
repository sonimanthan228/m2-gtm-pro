<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Hatimeria\GtmPro\Model\DataLayerSectionService;
use Hatimeria\GtmPro\Model\Config;

/**
 * Cart source
 */
class DataLayerData implements SectionSourceInterface
{
    /**
     * @var DataLayerSectionService 
     */
    protected $dataLayerSectionService;

    /**
     * @var Config
     */
    protected $config;

    /**
     * DataLayerData constructor.
     * @param DataLayerSectionService $dataLayerSectionService
     * @param Config $config
     */
    public function __construct(
        DataLayerSectionService $dataLayerSectionService,
        Config $config
    ) {
        $this->dataLayerSectionService = $dataLayerSectionService;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {
        $data = [];
        if ($this->config->isModuleEnabled()) {
            $data = $this->dataLayerSectionService->getDataLayerComponentData();
        }

        return ['dataLayer' => $data];
    }
}

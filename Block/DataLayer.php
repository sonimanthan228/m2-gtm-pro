<?php

namespace Hatimeria\GtmEe\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Serialize\Serializer\Json;
use Hatimeria\GtmEe\Api\DataLayerServiceInterface;
use Hatimeria\GtmEe\Model\Config;

/**
 * Class DataLayer
 */
class DataLayer extends Template
{
    /**
     * @var Json
     */
    protected $jsonSerializer;

    /**
     * @var DataLayerServiceInterface
     */
    protected $dataLayerService;
    /**
     * @var Config
     */
    protected $config;

    /**
     * DataLayer constructor.
     * @param Template\Context $context
     * @param Json $jsonSerializer
     * @param DataLayerServiceInterface $dataLayerService
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Json $jsonSerializer,
        DataLayerServiceInterface $dataLayerService,
        Config $config,
        array $data
    ) {
        $this->jsonSerializer = $jsonSerializer;
        $this->dataLayerService = $dataLayerService;
        $this->config = $config;
        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getDataLayer()
    {
        return $this->dataLayerService->getDataLayerComponentData();
    }

    protected function _toHtml()
    {
        if ($this->config->isModuleEnabled()) {
            return parent::_toHtml();
        }

        return '';
    }
}

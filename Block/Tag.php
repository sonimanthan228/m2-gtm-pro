<?php

namespace Hatimeria\GtmEe\Block;

use Magento\Framework\View\Element\Template;
use Hatimeria\GtmEe\Model\Config;

/**
 * Class Tag
 */
class Tag extends Template
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Tag constructor.
     * @param Config $config
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Config $config,
        Template\Context $context,
        array $data = []
    ) {
        $this->config = $config;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getContainerId()
    {
        return $this->config->getContainerId();
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->config->isModuleEnabled() && $this->config->getContainerId()) {
            return parent::_toHtml();
        }
        return '';
    }
}

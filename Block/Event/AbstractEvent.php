<?php

namespace Hatimeria\GtmEe\Block\Event;

use Magento\Framework\View\Element\Template;
use Hatimeria\GtmEe\Model\Config;

/**
 * Class Tag
 * @package Hatimeria\GtmEe\Block\Event
 */
abstract class AbstractEvent extends Template
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * AbstractEvent constructor.
     * @param Config $config
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Config $config,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->config->isModuleEnabled() && $this->isEventEnabled()) {
            return parent::_toHtml();
        }

        return '';
    }

    /**
     * @return bool
     */
    abstract function isEventEnabled();
}

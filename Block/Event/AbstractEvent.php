<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Block\Event;

use Magento\Framework\View\Element\Template;
use Hatimeria\GtmPro\Model\Config;

/**
 * Class AbstractEvent
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
    abstract public function isEventEnabled();

    protected function getCacheLifetime()
    {
        return 86400;
    }
}

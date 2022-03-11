<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Block;

use Magento\Framework\View\Element\Template;
use Hatimeria\GtmPro\Model\Config;

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

    protected function getCacheLifetime()
    {
        return 86400;
    }
}

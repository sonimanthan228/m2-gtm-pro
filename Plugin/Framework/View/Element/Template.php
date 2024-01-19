<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

declare(strict_types=1);

namespace Hatimeria\GtmPro\Plugin\Framework\View\Element;

class Template
{

    public function beforeSetTemplate(
        \Magento\Framework\View\Element\Template $subject,
        $template
    ) {
        if (in_array('hyva_default', $layoutHandle = $subject->getLayout()->getUpdate()->getHandles()) && $subject->getHyvaHandle()) {
            $template = str_replace('Hatimeria_GtmPro::', 'Hatimeria_GtmPro::hyva/', $template);
        }

        return [$template];
    }
}

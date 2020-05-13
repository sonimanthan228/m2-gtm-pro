<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Block\Adminhtml\Form\Field\Event;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * Class Ranges
 */
class Form extends AbstractFieldArray
{
    /**
     * Prepare rendering the new field by adding all the needed columns
     */
    protected function _prepareToRender()
    {
        $this->addColumn('form_id', ['label' => __('From Id'), 'class' => 'required-entry']);
        $this->addColumn(
            'form_field_ids',
            ['label' => __('Form Fields IDs (comma separated)'), 'class' => 'required-entry']
        );
        $this->addColumn('event_name', ['label' => __('Event Name'), 'class' => 'required-entry']);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}

<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;

class CustomAttributeTabs extends AbstractFieldArray
{
    protected function _prepareToRender(): void
    {
        $this->addColumn('enabled', [
            'label' => __('Enabled'),
            'class' => 'required-entry',
            'style' => 'width:80px',
        ]);
        $this->addColumn('title', [
            'label' => __('Tab Title'),
            'class' => 'required-entry',
            'style' => 'width:150px',
        ]);
        $this->addColumn('attribute_codes', [
            'label' => __('Attribute Codes (comma-separated)'),
            'class' => 'required-entry',
            'style' => 'width:220px',
        ]);
        $this->addColumn('icon_class', [
            'label' => __('Icon Class'),
            'style' => 'width:140px',
            'comment' => 'e.g. bi-gear',
        ]);
        $this->addColumn('sort_order', [
            'label' => __('Sort Order'),
            'class' => 'required-entry validate-number',
            'style' => 'width:80px',
        ]);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Attribute Tab');
    }

    protected function _toHtml()
    {
        return '<div class="panth-ptabs-fieldarray">' . parent::_toHtml() . '</div>'
            . '<style>.panth-ptabs-fieldarray{width:0;min-width:100%;}'
            . '.panth-ptabs-fieldarray .admin__control-table-wrapper{overflow-x:auto;}'
            . '.panth-ptabs-fieldarray .admin__control-table input.input-text{min-width:80px;max-width:100%;}</style>';
    }

    public function getArrayRows(): array
    {
        $result = [];
        $element = $this->getElement();
        $value = $element->getValue();
        if (!is_array($value)) {
            return $result;
        }
        unset($value['__empty']);
        $prefix = 'attr_';
        foreach ($value as $rowId => $row) {
            $rowId = $prefix . $rowId;
            $row['_id'] = $rowId;
            $rowColumnValues = [];
            foreach ($row as $key => $val) {
                $rowColumnValues[$rowId . '_' . $key] = $val;
            }
            $row['column_values'] = $rowColumnValues;
            $result[$rowId] = new DataObject($row);
            $this->_prepareArrayRow($result[$rowId]);
        }
        return $result;
    }
}

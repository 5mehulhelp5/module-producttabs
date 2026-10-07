<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class TabStyle implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            ['value' => 'horizontal', 'label' => __('Horizontal')],
            ['value' => 'vertical', 'label' => __('Vertical')],
        ];
    }
}

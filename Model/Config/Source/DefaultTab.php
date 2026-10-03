<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class DefaultTab implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            ['value' => 'first', 'label' => __('First Available Tab')],
            ['value' => 'description', 'label' => __('Description')],
            ['value' => 'more_info', 'label' => __('More Information')],
            ['value' => 'reviews', 'label' => __('Reviews')],
        ];
    }
}

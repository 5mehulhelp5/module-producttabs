<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class MobileBehavior implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            ['value' => 'accordion', 'label' => __('Accordion')],
            ['value' => 'scroll', 'label' => __('Horizontal Scroll')],
        ];
    }
}

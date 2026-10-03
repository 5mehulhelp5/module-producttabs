<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class AnimationType implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            ['value' => 'fade', 'label' => __('Fade')],
            ['value' => 'slide', 'label' => __('Slide')],
            ['value' => 'none', 'label' => __('None')],
        ];
    }
}

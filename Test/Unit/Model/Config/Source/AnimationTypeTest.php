<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Test\Unit\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Panth\ProductTabs\Model\Config\Source\AnimationType;
use PHPUnit\Framework\TestCase;

class AnimationTypeTest extends TestCase
{
    public function testToOptionArrayValuesAndLabels(): void
    {
        $source = new AnimationType();
        $this->assertInstanceOf(OptionSourceInterface::class, $source);

        $actual = [];
        foreach ($source->toOptionArray() as $option) {
            $actual[$option['value']] = (string) $option['label'];
        }

        $this->assertSame(['fade' => 'Fade', 'slide' => 'Slide', 'none' => 'None'], $actual);
    }
}

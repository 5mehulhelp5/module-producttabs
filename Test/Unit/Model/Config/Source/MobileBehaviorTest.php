<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Test\Unit\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Panth\ProductTabs\Model\Config\Source\MobileBehavior;
use PHPUnit\Framework\TestCase;

class MobileBehaviorTest extends TestCase
{
    public function testToOptionArrayValuesAndLabels(): void
    {
        $source = new MobileBehavior();
        $this->assertInstanceOf(OptionSourceInterface::class, $source);

        $actual = [];
        foreach ($source->toOptionArray() as $option) {
            $actual[$option['value']] = (string) $option['label'];
        }

        $this->assertSame(['accordion' => 'Accordion', 'scroll' => 'Horizontal Scroll'], $actual);
    }
}

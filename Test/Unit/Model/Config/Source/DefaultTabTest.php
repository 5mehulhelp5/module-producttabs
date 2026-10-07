<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Test\Unit\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Panth\ProductTabs\Model\Config\Source\DefaultTab;
use PHPUnit\Framework\TestCase;

class DefaultTabTest extends TestCase
{
    public function testToOptionArrayValuesAndLabels(): void
    {
        $source = new DefaultTab();
        $this->assertInstanceOf(OptionSourceInterface::class, $source);

        $actual = [];
        foreach ($source->toOptionArray() as $option) {
            $actual[$option['value']] = (string) $option['label'];
        }

        $this->assertSame([
            'first' => 'First Available Tab',
            'description' => 'Description',
            'more_info' => 'More Information',
            'reviews' => 'Reviews',
        ], $actual);
    }
}

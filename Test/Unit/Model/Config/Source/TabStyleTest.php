<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Test\Unit\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Panth\ProductTabs\Model\Config\Source\TabStyle;
use PHPUnit\Framework\TestCase;

class TabStyleTest extends TestCase
{
    private TabStyle $tabStyle;

    protected function setUp(): void
    {
        $this->tabStyle = new TabStyle();
    }

    public function testToOptionArrayReturnsArray(): void
    {
        $result = $this->tabStyle->toOptionArray();

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
    }

    public function testToOptionArrayIncludesHorizontalOption(): void
    {
        $result = $this->tabStyle->toOptionArray();

        $horizontalFound = false;
        foreach ($result as $option) {
            if ($option['value'] === 'horizontal') {
                $horizontalFound = true;
                $this->assertArrayHasKey('label', $option);
                $this->assertEquals('Horizontal', (string) $option['label']);
                break;
            }
        }

        $this->assertTrue($horizontalFound, 'Horizontal option not found in options array');
    }

    public function testToOptionArrayIncludesVerticalOption(): void
    {
        $result = $this->tabStyle->toOptionArray();

        $verticalFound = false;
        foreach ($result as $option) {
            if ($option['value'] === 'vertical') {
                $verticalFound = true;
                $this->assertArrayHasKey('label', $option);
                $this->assertEquals('Vertical', (string) $option['label']);
                break;
            }
        }

        $this->assertTrue($verticalFound, 'Vertical option not found in options array');
    }

    public function testToOptionArrayReturnsExactlyTwoOptions(): void
    {
        $result = $this->tabStyle->toOptionArray();

        $this->assertCount(2, $result);
    }

    public function testToOptionArrayEachOptionHasRequiredKeys(): void
    {
        $result = $this->tabStyle->toOptionArray();

        foreach ($result as $option) {
            $this->assertArrayHasKey('value', $option);
            $this->assertArrayHasKey('label', $option);
            $this->assertIsString($option['value']);
        }
    }

    public function testToOptionArrayValuesAreNotEmpty(): void
    {
        $result = $this->tabStyle->toOptionArray();

        foreach ($result as $option) {
            $this->assertNotEmpty($option['value']);
            $this->assertNotEmpty($option['label']);
        }
    }

    public function testToOptionArrayReturnsConsistentResults(): void
    {
        $result1 = $this->tabStyle->toOptionArray();
        $result2 = $this->tabStyle->toOptionArray();

        $this->assertEquals($result1, $result2);
    }

    public function testToOptionArrayOptionValuesAreCorrect(): void
    {
        $result = $this->tabStyle->toOptionArray();

        $values = array_column($result, 'value');
        $this->assertContains('horizontal', $values);
        $this->assertContains('vertical', $values);
    }

    public function testTabStyleImplementsOptionSourceInterface(): void
    {
        $this->assertInstanceOf(OptionSourceInterface::class, $this->tabStyle);
    }

    public function testToOptionArrayValidForFormConfiguration(): void
    {
        $result = $this->tabStyle->toOptionArray();

        foreach ($result as $option) {
            $this->assertIsArray($option);
            $this->assertArrayHasKey('value', $option);
            $this->assertArrayHasKey('label', $option);
            $this->assertIsString($option['value']);
            $this->assertNotEmpty($option['value']);
            $this->assertTrue(
                is_string($option['label']) ||
                method_exists($option['label'], '__toString')
            );
        }
    }

    public function testToOptionArrayHorizontalOptionIsFirst(): void
    {
        $result = $this->tabStyle->toOptionArray();

        $this->assertEquals('horizontal', $result[0]['value']);
    }

    public function testToOptionArrayVerticalOptionIsSecond(): void
    {
        $result = $this->tabStyle->toOptionArray();

        $this->assertEquals('vertical', $result[1]['value']);
    }
}

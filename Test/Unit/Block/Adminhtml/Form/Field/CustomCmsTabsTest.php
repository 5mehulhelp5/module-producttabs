<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Test\Unit\Block\Adminhtml\Form\Field;

use Magento\Framework\DataObject;
use Panth\ProductTabs\Block\Adminhtml\Form\Field\CustomCmsTabs;
use PHPUnit\Framework\TestCase;

class CustomCmsTabsTest extends TestCase
{
    private function block(): CustomCmsTabs
    {
        return (new \ReflectionClass(CustomCmsTabs::class))->newInstanceWithoutConstructor();
    }

    public function testNonArrayValueYieldsNoRows(): void
    {
        $block = $this->block();
        $block->setData('element', new DataObject(['value' => null]));

        $this->assertSame([], $block->getArrayRows());
    }

    public function testRowsArePrefixedAndExposeColumnValues(): void
    {
        $block = $this->block();
        $block->setData('element', new DataObject(['value' => [
            '_5' => ['title' => 'Shipping', 'block_identifier' => 'shipping-info'],
            '__empty' => '',
        ]]));

        $rows = $block->getArrayRows();

        $this->assertSame(['cms__5'], array_keys($rows));
        $this->assertSame('shipping-info', $rows['cms__5']->getData('block_identifier'));
        $this->assertSame([
            'cms__5_title' => 'Shipping',
            'cms__5_block_identifier' => 'shipping-info',
            'cms__5__id' => 'cms__5',
        ], $rows['cms__5']->getData('column_values'));
    }

    public function testEmptyArrayYieldsNoRows(): void
    {
        $block = $this->block();
        $block->setData('element', new DataObject(['value' => ['__empty' => '']]));

        $this->assertSame([], $block->getArrayRows());
    }

    public function testPrepareToRenderDefinesColumns(): void
    {
        $block = $this->block();
        (new \ReflectionMethod($block, '_prepareToRender'))->invoke($block);

        $columns = $block->getColumns();
        $this->assertSame(['enabled', 'title', 'block_identifier', 'icon_class', 'sort_order'], array_keys($columns));
        $this->assertSame('CMS Block Identifier', (string) $columns['block_identifier']['label']);
        $this->assertSame('required-entry', $columns['block_identifier']['class']);

        $label = (new \ReflectionProperty($block, '_addButtonLabel'))->getValue($block);
        $this->assertSame('Add CMS Block Tab', (string) $label);
        $this->assertFalse((new \ReflectionProperty($block, '_addAfter'))->getValue($block));
    }
}

<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Test\Unit\Block\Adminhtml\Form\Field;

use Magento\Framework\DataObject;
use Panth\ProductTabs\Block\Adminhtml\Form\Field\CustomAttributeTabs;
use PHPUnit\Framework\TestCase;

class CustomAttributeTabsTest extends TestCase
{
    private function block(): CustomAttributeTabs
    {
        return (new \ReflectionClass(CustomAttributeTabs::class))->newInstanceWithoutConstructor();
    }

    public function testNonArrayValueYieldsNoRows(): void
    {
        $block = $this->block();
        $block->setData('element', new DataObject(['value' => 'serialized-string']));

        $this->assertSame([], $block->getArrayRows());
    }

    public function testRowsArePrefixedAndExposeColumnValues(): void
    {
        $block = $this->block();
        $block->setData('element', new DataObject(['value' => [
            '__empty' => '',
            '_17' => ['title' => 'Care', 'attribute_codes' => 'care,wash'],
            '_18' => ['title' => 'Size'],
        ]]));

        $rows = $block->getArrayRows();

        $this->assertSame(['attr__17', 'attr__18'], array_keys($rows));
        $this->assertSame('attr__17', $rows['attr__17']->getData('_id'));
        $this->assertSame('Care', $rows['attr__17']->getData('title'));
        $this->assertSame([
            'attr__17_title' => 'Care',
            'attr__17_attribute_codes' => 'care,wash',
            'attr__17__id' => 'attr__17',
        ], $rows['attr__17']->getData('column_values'));
        $this->assertSame(
            ['attr__18_title' => 'Size', 'attr__18__id' => 'attr__18'],
            $rows['attr__18']->getData('column_values')
        );
    }

    public function testPrepareToRenderDefinesColumns(): void
    {
        $block = $this->block();
        (new \ReflectionMethod($block, '_prepareToRender'))->invoke($block);

        $columns = $block->getColumns();
        $this->assertSame(['enabled', 'title', 'attribute_codes', 'icon_class', 'sort_order'], array_keys($columns));
        $this->assertSame('required-entry validate-number', $columns['sort_order']['class']);
        $this->assertSame('Attribute Codes (comma-separated)', (string) $columns['attribute_codes']['label']);
        $this->assertSame('width:140px', $columns['icon_class']['style']);

        $label = (new \ReflectionProperty($block, '_addButtonLabel'))->getValue($block);
        $addAfter = (new \ReflectionProperty($block, '_addAfter'))->getValue($block);
        $this->assertSame('Add Attribute Tab', (string) $label);
        $this->assertFalse($addAfter);
    }
}

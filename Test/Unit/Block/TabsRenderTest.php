<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Test\Unit\Block;

use Magento\Framework\View\Element\Template\Context;
use Panth\Core\Helper\Theme;
use Panth\ProductTabs\Block\Tabs;
use Panth\ProductTabs\Helper\Data as ConfigHelper;
use PHPUnit\Framework\TestCase;

class TabsRenderTest extends TestCase
{
    public function testDisabledModuleRendersNothing(): void
    {
        $helper = $this->createMock(ConfigHelper::class);
        $helper->expects($this->once())->method('isEnabled')->willReturn(false);

        $block = new Tabs($this->createStub(Context::class), $helper, $this->createStub(Theme::class));
        $method = new \ReflectionMethod($block, '_toHtml');

        $this->assertSame('', $method->invoke($block));
    }

    public function testDataIsPassedToParentBlock(): void
    {
        $block = new Tabs(
            $this->createStub(Context::class),
            $this->createStub(ConfigHelper::class),
            $this->createStub(Theme::class),
            ['custom_flag' => 'yes']
        );

        $this->assertSame('yes', $block->getData('custom_flag'));
    }
}

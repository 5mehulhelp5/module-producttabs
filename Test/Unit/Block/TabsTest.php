<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Test\Unit\Block;

use Magento\Framework\View\Element\Template\Context;
use Panth\ProductTabs\Block\Tabs;
use Panth\ProductTabs\Helper\Data as ConfigHelper;
use Panth\Core\Helper\Theme;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TabsTest extends TestCase
{
    private Tabs $block;

    private $contextMock;

    private $configHelperMock;

    private $themeHelperMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(Context::class);
        $this->configHelperMock = $this->createMock(ConfigHelper::class);
        $this->themeHelperMock = $this->createMock(Theme::class);

        $this->block = new Tabs(
            $this->contextMock,
            $this->configHelperMock,
            $this->themeHelperMock,
            []
        );
    }

    public function testIsEnabledReturnsTrue(): void
    {
        $this->configHelperMock->expects($this->once())
            ->method('isEnabled')
            ->willReturn(true);

        $this->assertTrue($this->block->isEnabled());
    }

    public function testIsEnabledReturnsFalse(): void
    {
        $this->configHelperMock->expects($this->once())
            ->method('isEnabled')
            ->willReturn(false);

        $this->assertFalse($this->block->isEnabled());
    }

    public function testGetTemplateReturnsHyvaTemplate(): void
    {
        $this->themeHelperMock->expects($this->once())
            ->method('isHyva')
            ->willReturn(true);

        $this->assertEquals('Panth_ProductTabs::hyva/tabs.phtml', $this->block->getTemplate());
    }

    public function testGetTemplateReturnsLumaTemplate(): void
    {
        $this->themeHelperMock->expects($this->once())
            ->method('isHyva')
            ->willReturn(false);

        $this->assertEquals('Panth_ProductTabs::tabs.phtml', $this->block->getTemplate());
    }

    public function testGetTabStyle(): void
    {
        $this->configHelperMock->expects($this->once())
            ->method('getTabStyle')
            ->willReturn('vertical');

        $this->assertEquals('vertical', $this->block->getTabStyle());
    }

    public function testGetAnimationType(): void
    {
        $this->configHelperMock->expects($this->once())
            ->method('getAnimationType')
            ->willReturn('slide');

        $this->assertEquals('slide', $this->block->getAnimationType());
    }

    public function testIsFirstTabOpen(): void
    {
        $this->configHelperMock->expects($this->once())
            ->method('isFirstTabOpen')
            ->willReturn(true);

        $this->assertTrue($this->block->isFirstTabOpen());
    }

    public function testIsAccordionOnMobile(): void
    {
        $this->configHelperMock->expects($this->once())
            ->method('isAccordionOnMobile')
            ->willReturn(true);

        $this->assertTrue($this->block->isAccordionOnMobile());
    }
}

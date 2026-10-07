<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Test\Unit\Block\Widget;

use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;
use Panth\Core\Helper\Theme;
use Panth\ProductTabs\Block\Widget\Tabs;
use Panth\ProductTabs\Helper\Data as ConfigHelper;
use Panth\ProductTabs\ViewModel\Config as ConfigViewModel;
use Panth\ProductTabs\ViewModel\Tabs as TabsViewModel;
use PHPUnit\Framework\TestCase;

class TabsTest extends TestCase
{
    private function themeHelper(bool $hyva): Theme
    {
        $theme = $this->createStub(Theme::class);
        $theme->method('isHyva')->willReturn($hyva);
        return $theme;
    }

    public function testInjectsConfigAndTabsViewModels(): void
    {
        $config = $this->createStub(ConfigViewModel::class);
        $tabs = $this->createStub(TabsViewModel::class);

        $widget = new Tabs(
            $this->createStub(Context::class),
            $this->createStub(ConfigHelper::class),
            $this->themeHelper(false),
            $config,
            ['panth_config' => 'overridden'],
            $tabs
        );

        $this->assertInstanceOf(BlockInterface::class, $widget);
        $this->assertSame($config, $widget->getData('panth_config'));
        $this->assertSame($tabs, $widget->getData('view_model'));
        $this->assertSame('Panth_ProductTabs::tabs.phtml', $widget->getTemplate());
    }

    public function testExistingViewModelArgumentIsKept(): void
    {
        $layoutViewModel = $this->createStub(TabsViewModel::class);

        $widget = new Tabs(
            $this->createStub(Context::class),
            $this->createStub(ConfigHelper::class),
            $this->themeHelper(true),
            $this->createStub(ConfigViewModel::class),
            ['view_model' => $layoutViewModel],
            $this->createStub(TabsViewModel::class)
        );

        $this->assertSame($layoutViewModel, $widget->getData('view_model'));
        $this->assertSame('Panth_ProductTabs::hyva/tabs.phtml', $widget->getTemplate());
    }
}

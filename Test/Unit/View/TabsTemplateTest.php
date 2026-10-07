<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Test\Unit\View;

use Magento\Catalog\Block\Product\View\Details;
use Magento\Framework\Escaper;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\LayoutInterface;
use Panth\ProductTabs\ViewModel\Config;
use Panth\ProductTabs\ViewModel\Tabs as TabsViewModel;
use PHPUnit\Framework\TestCase;

class TabsTemplateTest extends TestCase
{
    private const LUMA = 'tabs.phtml';
    private const HYVA = 'hyva/tabs.phtml';

    private array $settings;

    protected function setUp(): void
    {
        $this->settings = [
            'isEnabled' => true,
            'getAnimationType' => 'fade',
            'getMobileBehavior' => 'accordion',
            'isStickyTabs' => false,
            'isLazyLoadReviews' => false,
            'getDefaultTab' => 'first',
            'getTabStyle' => 'horizontal',
            'isAccordionOnMobile' => true,
            'isShowTabIcon' => false,
            'getDescriptionLabel' => 'Description',
            'getMoreInfoLabel' => 'More Information',
            'getReviewsLabel' => 'Reviews',
            'showDescription' => true,
            'showMoreInfo' => true,
            'showReviews' => true,
            'getDescriptionOrder' => 10,
            'getMoreInfoOrder' => 20,
            'getReviewsOrder' => 30,
            'getCustomCmsTabs' => [],
            'getCustomAttributeTabs' => [],
            'isFirstTabOpen' => true,
        ];
    }

    public function testLumaDisabledFallsBackToNativeDetailsTemplate(): void
    {
        $this->settings['isEnabled'] = false;
        $block = $this->createBlock([], [], [], true);
        $block->expects($this->once())->method('getTemplateFile')
            ->with('Magento_Catalog::product/view/details.phtml')
            ->willReturn('/native/details.phtml');
        $block->expects($this->once())->method('fetchView')
            ->with('/native/details.phtml')
            ->willReturn('NATIVE-DETAILS');

        $this->assertSame('NATIVE-DETAILS', $this->render(self::LUMA, $block));
    }

    public function testHyvaDisabledFallsBackToNativeSectionsTemplate(): void
    {
        $this->settings['isEnabled'] = false;
        $block = $this->createBlock([], [], [], true);
        $block->expects($this->once())->method('getTemplateFile')
            ->with('Magento_Catalog::product/view/sections/product-sections.phtml')
            ->willReturn('/native/sections.phtml');
        $block->method('fetchView')->willReturn('NATIVE-SECTIONS');

        $this->assertSame('NATIVE-SECTIONS', $this->render(self::HYVA, $block));
    }

    public function testDisabledWithMissingNativeTemplateRendersNothing(): void
    {
        $this->settings['isEnabled'] = false;
        $block = $this->createBlock([], [], [], true);
        $block->method('getTemplateFile')->willReturn(false);
        $block->expects($this->never())->method('fetchView');

        $this->assertSame('', trim($this->render(self::LUMA, $block)));
    }

    public function testLumaReviewTabUsesCoreReviewIdsForAjaxLoading(): void
    {
        $html = $this->render(self::LUMA, $this->createBlock(
            ['description', 'reviews'],
            ['description' => 'DESC-HTML', 'reviews' => 'REVIEW-HTML']
        ));

        $this->assertStringContainsString('id="tab-label-reviews"', $html);
        $this->assertStringContainsString('aria-controls="reviews"', $html);
        $this->assertMatchesRegularExpression('/id="reviews"\s+aria-labelledby="tab-label-reviews"/', $html);
        $this->assertStringContainsString('id="panth-tab-0"', $html);
        $this->assertStringContainsString('id="panth-panel-description"', $html);
        $this->assertSame(1, substr_count($html, 'id="reviews"'));
        $this->assertStringContainsString("new CustomEvent('beforeOpen')", $html);
    }

    public function testLumaUsesRovingTabindexAndFocusablePanels(): void
    {
        $html = $this->render(self::LUMA, $this->createBlock(
            ['description', 'additional', 'reviews'],
            ['description' => 'D', 'additional' => 'A', 'reviews' => 'R']
        ));

        preg_match_all('/role="tab"[^>]*?tabindex="(-?\d)"/s', $html, $tabs);
        $this->assertSame(['0', '-1', '-1'], $tabs[1]);
        $this->assertSame(3, preg_match_all('/role="tabpanel"[^>]*?tabindex="0"/s', $html));
        $this->assertStringContainsString('panth-tabs__btn--active active', $html);
    }

    public function testLumaPanelSwitchingHasNoDeferredShow(): void
    {
        $html = $this->render(self::LUMA, $this->createBlock(['description'], ['description' => 'D']));

        $this->assertStringNotContainsString('transitionend', $html);
        $this->assertStringContainsString('function clearAnim(panel)', $html);
        $this->assertStringContainsString("'ArrowDown'", $html);
    }

    public function testRovingTabindexFallsBackToFirstTabWhenAllClosed(): void
    {
        $this->settings['isFirstTabOpen'] = false;
        $html = $this->render(self::LUMA, $this->createBlock(
            ['description', 'additional'],
            ['description' => 'D', 'additional' => 'A']
        ));

        preg_match_all('/role="tab"[^>]*?tabindex="(-?\d)"/s', $html, $tabs);
        $this->assertSame(['0', '-1'], $tabs[1]);
        $this->assertStringNotContainsString('class="panth-tabs__panel panth-tabs__panel--active"', $html);
        $this->assertStringNotContainsString('class="panth-tabs__btn panth-tabs__btn--active', $html);
    }

    public function testHyvaDefaultPanelIsNotCloakedToAvoidLayoutShift(): void
    {
        $html = $this->render(self::HYVA, $this->createBlock(
            ['description', 'additional'],
            ['description' => 'D', 'additional' => 'A']
        ));

        preg_match_all('/<div class="panth-tabs__panel".*?>/s', $html, $panels);
        $this->assertCount(2, $panels[0]);
        $this->assertStringNotContainsString('x-cloak', $panels[0][0]);
        $this->assertStringContainsString('x-cloak', $panels[0][1]);
        $this->assertStringContainsString(':tabindex="(activeTab >= 0 ? activeTab : 0) === 1 ? 0 : -1"', $html);
        $this->assertStringContainsString('revealAccordion($event.currentTarget)', $html);
    }

    public function testDefaultTabSettingSelectsReviews(): void
    {
        $this->settings['getDefaultTab'] = 'reviews';
        $html = $this->render(self::HYVA, $this->createBlock(
            ['description', 'reviews'],
            ['description' => 'D', 'reviews' => 'R']
        ));

        $this->assertStringContainsString('activeTab: 1,', $html);
        preg_match_all('/<div class="panth-tabs__panel".*?>/s', $html, $panels);
        $this->assertStringContainsString('x-cloak', $panels[0][0]);
        $this->assertStringNotContainsString('x-cloak', $panels[0][1]);
    }

    public function testEmptySectionsAreSkippedAndNoMarkupWhenAllEmpty(): void
    {
        $html = $this->render(self::LUMA, $this->createBlock(
            ['description', 'additional'],
            ['description' => '   ', 'additional' => '']
        ));

        $this->assertStringNotContainsString('panth-product-tabs', $html);
    }

    public function testEmptySectionIsHiddenBetweenFilledOnes(): void
    {
        $html = $this->render(self::LUMA, $this->createBlock(
            ['description', 'additional', 'reviews'],
            ['description' => 'D', 'additional' => ' ', 'reviews' => 'R']
        ));

        $this->assertSame(2, preg_match_all('/<button[^>]*role="tab"/', $html));
        $this->assertStringNotContainsString('More Information', $html);
    }

    public function testVisibilityAndSortOrderAndLabels(): void
    {
        $this->settings['showMoreInfo'] = false;
        $this->settings['getReviewsOrder'] = 5;
        $this->settings['getDescriptionLabel'] = 'Overview <b>';
        $html = $this->render(self::LUMA, $this->createBlock(
            ['description', 'additional', 'reviews'],
            ['description' => 'D', 'additional' => 'A', 'reviews' => 'R']
        ));

        $this->assertStringNotContainsString('More Information', $html);
        $this->assertStringContainsString('Overview &lt;b&gt;', $html);
        $this->assertLessThan(strpos($html, 'Overview'), strpos($html, 'Reviews (2)'));
    }

    public function testCustomCmsTabRulesAndSortOrder(): void
    {
        $this->settings['getCustomCmsTabs'] = [
            'a' => ['enabled' => '1', 'title' => 'Shipping', 'block_identifier' => 'ship-info', 'sort_order' => '1'],
            'b' => ['enabled' => '0', 'title' => 'Off', 'block_identifier' => 'off-block', 'sort_order' => '2'],
            'c' => ['enabled' => '1', 'title' => '', 'block_identifier' => 'no-title', 'sort_order' => '3'],
            'd' => ['enabled' => '1', 'title' => 'Empty', 'block_identifier' => 'empty-block', 'sort_order' => '4'],
        ];
        $cmsHtml = ['ship-info' => 'SHIP-CONTENT', 'empty-block' => '  '];
        $html = $this->render(self::LUMA, $this->createBlock(
            ['description'],
            ['description' => 'D'],
            $cmsHtml
        ));

        $this->assertStringContainsString('id="panth-panel-cms_ship_info"', $html);
        $this->assertStringContainsString('SHIP-CONTENT', $html);
        $this->assertStringNotContainsString('cms_off_block', $html);
        $this->assertStringNotContainsString('cms_no_title', $html);
        $this->assertStringNotContainsString('cms_empty_block', $html);
        $this->assertLessThan(strpos($html, 'id="panth-panel-description"'), strpos($html, 'id="panth-panel-cms_ship_info"'));
    }

    private function render(string $template, Details $block): string
    {
        $escaper = $this->createStub(Escaper::class);
        $escape = static fn ($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
        $escaper->method('escapeHtml')->willReturnCallback($escape);
        $escaper->method('escapeHtmlAttr')->willReturnCallback($escape);
        $file = dirname(__DIR__, 3) . '/view/frontend/templates/' . $template;

        $renderer = static function () use ($block, $escaper, $file) {
            ob_start();
            include $file;
            return (string) ob_get_clean();
        };

        return $renderer();
    }

    private function createBlock(
        array $aliases,
        array $htmlByAlias,
        array $cmsHtml = [],
        bool $mock = false
    ): Details
    {
        $config = $this->createStub(Config::class);
        foreach ($this->settings as $method => $value) {
            $config->method($method)->willReturn($value);
        }
        $viewModel = $this->createStub(TabsViewModel::class);
        $viewModel->method('getCurrentProduct')->willReturn(null);
        $viewModel->method('getApprovedReviewCount')->willReturn(2);

        $layout = $this->createStub(LayoutInterface::class);
        $layout->method('getBlock')->willReturnCallback(function ($name) use ($htmlByAlias) {
            if (!array_key_exists($name, $htmlByAlias)) {
                return false;
            }
            $section = $this->createStub(AbstractBlock::class);
            $section->method('toHtml')->willReturn($htmlByAlias[$name]);
            $section->method('getData')->willReturn(null);
            return $section;
        });
        $layout->method('getElementAlias')->willReturnArgument(0);
        $layout->method('createBlock')->willReturnCallback(function () use ($cmsHtml) {
            $cms = new class ($cmsHtml) {
                private array $map;
                private string $id = '';

                public function __construct(array $map)
                {
                    $this->map = $map;
                }

                public function setBlockId(string $id): self
                {
                    $this->id = $id;
                    return $this;
                }

                public function toHtml(): string
                {
                    return $this->map[$this->id] ?? '';
                }
            };
            return $cms;
        });

        $block = $mock ? $this->createMock(Details::class) : $this->createStub(Details::class);
        $block->method('getData')->willReturnCallback(function ($key) use ($config, $viewModel) {
            return ['panth_config' => $config, 'view_model' => $viewModel][$key] ?? null;
        });
        $block->method('getGroupSortedChildNames')->willReturn($aliases);
        $block->method('getLayout')->willReturn($layout);
        $block->method('getChildData')->willReturn(null);

        return $block;
    }
}

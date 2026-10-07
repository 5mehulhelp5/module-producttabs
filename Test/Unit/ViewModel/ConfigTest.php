<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Test\Unit\ViewModel;

use Panth\ProductTabs\Helper\Data as ConfigHelper;
use Panth\ProductTabs\ViewModel\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    private function helper(): ConfigHelper
    {
        $helper = $this->createStub(ConfigHelper::class);
        $helper->method('isEnabled')->willReturn(true);
        $helper->method('isStickyTabs')->willReturn(true);
        $helper->method('isLazyLoadReviews')->willReturn(false);
        $helper->method('getDefaultTab')->willReturn('reviews');
        $helper->method('getTabStyle')->willReturn('vertical');
        $helper->method('getAnimationType')->willReturn('slide');
        $helper->method('getMobileBehavior')->willReturn('scroll');
        $helper->method('isFirstTabOpen')->willReturn(false);
        $helper->method('isAccordionOnMobile')->willReturn(true);
        $helper->method('isShowTabIcon')->willReturn(true);
        $helper->method('getDescriptionLabel')->willReturn('Details');
        $helper->method('getMoreInfoLabel')->willReturn('Specs');
        $helper->method('getReviewsLabel')->willReturn('Opinions');
        $helper->method('showDescription')->willReturn(true);
        $helper->method('showMoreInfo')->willReturn(false);
        $helper->method('showReviews')->willReturn(true);
        $helper->method('getDescriptionOrder')->willReturn(5);
        $helper->method('getMoreInfoOrder')->willReturn(15);
        $helper->method('getReviewsOrder')->willReturn(25);
        $helper->method('getCustomCmsTabs')->willReturn([['title' => 'Shipping']]);
        $helper->method('getCustomAttributeTabs')->willReturn([['title' => 'Care']]);
        return $helper;
    }

    public function testGetTabConfigSubset(): void
    {
        $this->assertSame([
            'tabStyle' => 'vertical',
            'animationType' => 'slide',
            'firstTabOpen' => false,
            'accordionOnMobile' => true,
            'showTabIcon' => true,
        ], (new Config($this->helper()))->getTabConfig());
    }

    public function testGetAllTabsConfigAggregatesEverySetting(): void
    {
        $this->assertSame([
            'enabled' => true,
            'stickyTabs' => true,
            'lazyLoadReviews' => false,
            'defaultTab' => 'reviews',
            'tabStyle' => 'vertical',
            'animationType' => 'slide',
            'mobileBehavior' => 'scroll',
            'firstTabOpen' => false,
            'accordionOnMobile' => true,
            'showTabIcon' => true,
            'labels' => ['description' => 'Details', 'moreInfo' => 'Specs', 'reviews' => 'Opinions'],
            'visibility' => ['description' => true, 'moreInfo' => false, 'reviews' => true],
            'sortOrder' => ['description' => 5, 'moreInfo' => 15, 'reviews' => 25],
            'customCmsTabs' => [['title' => 'Shipping']],
            'customAttributeTabs' => [['title' => 'Care']],
        ], (new Config($this->helper()))->getAllTabsConfig());
    }

    public function testIndividualAccessorsDelegate(): void
    {
        $config = new Config($this->helper());

        $this->assertTrue($config->isEnabled());
        $this->assertTrue($config->isStickyTabs());
        $this->assertFalse($config->isLazyLoadReviews());
        $this->assertSame('reviews', $config->getDefaultTab());
        $this->assertSame('scroll', $config->getMobileBehavior());
        $this->assertSame('Details', $config->getDescriptionLabel());
        $this->assertSame('Specs', $config->getMoreInfoLabel());
        $this->assertSame('Opinions', $config->getReviewsLabel());
        $this->assertFalse($config->showMoreInfo());
        $this->assertSame(25, $config->getReviewsOrder());
        $this->assertSame([['title' => 'Shipping']], $config->getCustomCmsTabs());
        $this->assertSame([['title' => 'Care']], $config->getCustomAttributeTabs());
    }
}

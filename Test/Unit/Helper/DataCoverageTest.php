<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\ScopeInterface;
use Panth\ProductTabs\Helper\Data;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DataCoverageTest extends TestCase
{
    private function helperWith(array $values): Data
    {
        $scopeConfig = $this->createStub(ScopeConfigInterface::class);
        $scopeConfig->method('getValue')->willReturnCallback(
            static function ($path, $scope = null, $storeId = null) use ($values) {
                if ($scope !== ScopeInterface::SCOPE_STORE) {
                    return null;
                }
                $scoped = $path . '@' . (string) $storeId;
                return array_key_exists($scoped, $values) ? $values[$scoped] : ($values[$path] ?? null);
            }
        );
        $context = $this->createStub(Context::class);
        $context->method('getScopeConfig')->willReturn($scopeConfig);
        return new Data($context, new Json());
    }

    public static function stringDefaults(): array
    {
        return [
            'default tab' => ['getDefaultTab', 'panth_producttabs/general/default_tab', 'first', 'reviews'],
            'mobile behavior' => ['getMobileBehavior', 'panth_producttabs/design/mobile_behavior', 'accordion', 'scroll'],
            'description label' => [
                'getDescriptionLabel', 'panth_producttabs/labels/description_label', 'Description', 'Details',
            ],
            'more info label' => [
                'getMoreInfoLabel', 'panth_producttabs/labels/more_info_label', 'More Information', 'Specs',
            ],
            'reviews label' => ['getReviewsLabel', 'panth_producttabs/labels/reviews_label', 'Reviews', 'Opinions'],
        ];
    }

    #[DataProvider('stringDefaults')]
    public function testStringSettingsUseDefaultsWhenEmpty(
        string $method,
        string $path,
        string $default,
        string $configured
    ): void {
        $this->assertSame($default, $this->helperWith([])->$method());
        $this->assertSame($default, $this->helperWith([$path => ''])->$method());
        $this->assertSame($configured, $this->helperWith([$path => $configured])->$method());
        $this->assertSame($configured, $this->helperWith([$path . '@3' => $configured])->$method(3));
    }

    public static function intDefaults(): array
    {
        return [
            'description' => ['getDescriptionOrder', 'panth_producttabs/sort_order/description_order', 10],
            'more info' => ['getMoreInfoOrder', 'panth_producttabs/sort_order/more_info_order', 20],
            'reviews' => ['getReviewsOrder', 'panth_producttabs/sort_order/reviews_order', 30],
        ];
    }

    #[DataProvider('intDefaults')]
    public function testSortOrdersUseDefaultsWhenEmptyOrZero(string $method, string $path, int $default): void
    {
        $this->assertSame($default, $this->helperWith([])->$method());
        $this->assertSame($default, $this->helperWith([$path => '0'])->$method());
        $this->assertSame(55, $this->helperWith([$path => '55'])->$method());
    }

    public static function flags(): array
    {
        return [
            ['isStickyTabs', 'panth_producttabs/general/sticky_tabs'],
            ['isLazyLoadReviews', 'panth_producttabs/general/lazy_load_reviews'],
            ['isShowTabIcon', 'panth_producttabs/design/show_tab_icon'],
            ['showDescription', 'panth_producttabs/visibility/show_description'],
            ['showMoreInfo', 'panth_producttabs/visibility/show_more_info'],
            ['showReviews', 'panth_producttabs/visibility/show_reviews'],
            ['isFirstTabOpen', 'panth_producttabs/design/first_tab_open'],
            ['isAccordionOnMobile', 'panth_producttabs/design/accordion_on_mobile'],
        ];
    }

    #[DataProvider('flags')]
    public function testBooleanFlags(string $method, string $path): void
    {
        $this->assertFalse($this->helperWith([])->$method());
        $this->assertFalse($this->helperWith([$path => '0'])->$method());
        $this->assertTrue($this->helperWith([$path => '1'])->$method());
        $this->assertTrue($this->helperWith([$path . '@2' => '1'])->$method(2));
        $this->assertFalse($this->helperWith([$path . '@2' => '1'])->$method(5));
    }

    public static function customTabMethods(): array
    {
        return [
            'cms' => ['getCustomCmsTabs', 'panth_producttabs/custom_cms_tabs/cms_tabs'],
            'attribute' => ['getCustomAttributeTabs', 'panth_producttabs/custom_attribute_tabs/attr_tabs'],
        ];
    }

    #[DataProvider('customTabMethods')]
    public function testCustomTabsDecoding(string $method, string $path): void
    {
        $rows = ['_1' => ['title' => 'Shipping', 'sort_order' => '40']];

        $this->assertSame([], $this->helperWith([])->$method());
        $this->assertSame([], $this->helperWith([$path => ''])->$method());
        $this->assertSame($rows, $this->helperWith([$path => json_encode($rows)])->$method());
        $this->assertSame($rows, $this->helperWith([$path => $rows])->$method());
        $this->assertSame([], $this->helperWith([$path => '{broken'])->$method());
        $this->assertSame([], $this->helperWith([$path => '"just a string"'])->$method());
        $this->assertSame([], $this->helperWith([$path => 42])->$method());
        $this->assertSame($rows, $this->helperWith([$path . '@7' => json_encode($rows)])->$method(7));
    }
}

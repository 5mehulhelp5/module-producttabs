<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Test\Unit\Model\Config\Backend;

use Panth\ProductTabs\Model\Config\Backend\DynamicRows;
use PHPUnit\Framework\TestCase;

class DynamicRowsTest extends TestCase
{
    private DynamicRows $model;

    protected function setUp(): void
    {
        $this->model = (new \ReflectionClass(DynamicRows::class))->newInstanceWithoutConstructor();
    }

    public function testDisplayPrefixIsRemovedFromRowKeys(): void
    {
        $rows = [
            'cms__1' => ['title' => 'Shipping'],
            'attr__2' => ['title' => 'Specs'],
            '__empty' => '',
        ];

        $this->assertSame(
            ['_1' => ['title' => 'Shipping'], '_2' => ['title' => 'Specs'], '__empty' => ''],
            $this->model->normalizeRowKeys($rows)
        );
    }

    public function testRepeatedPrefixesFromEarlierSavesAreRemoved(): void
    {
        $this->assertSame(
            ['_1' => ['a' => 1]],
            $this->model->normalizeRowKeys(['cms_cms_cms__1' => ['a' => 1]])
        );
    }

    public function testUnprefixedKeysAreKept(): void
    {
        $rows = ['_1' => ['a' => 1], '_1727000000000_123' => ['a' => 2]];

        $this->assertSame($rows, $this->model->normalizeRowKeys($rows));
    }

    public function testCollidingKeysAreKeptApart(): void
    {
        $result = $this->model->normalizeRowKeys(['_1' => ['a' => 1], 'cms__1' => ['a' => 2]]);

        $this->assertSame(['_1' => ['a' => 1], '_1_1' => ['a' => 2]], $result);
    }

    public function testNormalizingTwiceIsStable(): void
    {
        $once = $this->model->normalizeRowKeys(['cms__1' => ['a' => 1], 'cms__2' => ['a' => 2]]);

        $this->assertSame($once, $this->model->normalizeRowKeys($once));
    }
}

<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Test\Unit\Model\Config\Backend;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;
use Panth\ProductTabs\Model\Config\Backend\DynamicRows;
use PHPUnit\Framework\TestCase;

class DynamicRowsBeforeSaveTest extends TestCase
{
    private function backend($value): DynamicRows
    {
        $context = $this->createStub(Context::class);
        $context->method('getEventDispatcher')->willReturn($this->createStub(ManagerInterface::class));

        return new DynamicRows(
            $context,
            $this->createStub(Registry::class),
            $this->createStub(ScopeConfigInterface::class),
            $this->createStub(TypeListInterface::class),
            null,
            null,
            ['value' => $value],
            new Json()
        );
    }

    public function testBeforeSaveNormalizesKeysAndSerializes(): void
    {
        $backend = $this->backend([
            '__empty' => '',
            'cms__1' => ['title' => 'A'],
            '_1' => ['title' => 'B'],
            'attr_attr__2' => ['title' => 'C'],
        ]);

        $this->assertSame($backend, $backend->beforeSave());
        $this->assertSame(
            ['_1' => ['title' => 'A'], '_1_1' => ['title' => 'B'], '_2' => ['title' => 'C']],
            json_decode((string) $backend->getValue(), true)
        );
    }

    public function testBeforeSaveLeavesScalarValueUntouched(): void
    {
        $backend = $this->backend('{"_1":{"title":"A"}}');

        $backend->beforeSave();

        $this->assertSame('{"_1":{"title":"A"}}', $backend->getValue());
    }

    public function testEmptyKeyAndNumericKeysAreHandled(): void
    {
        $backend = $this->backend([]);

        $this->assertSame(
            ['__empty' => 'x', '0' => 'zero', 'cms_' => 'no-underscore-after'],
            $backend->normalizeRowKeys(['__empty' => 'x', 0 => 'zero', 'cms_' => 'no-underscore-after'])
        );
    }
}

<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Test\Unit\ViewModel;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Magento\Review\Model\ResourceModel\Review\Collection as ReviewCollection;
use Magento\Review\Model\ResourceModel\Review\CollectionFactory as ReviewCollectionFactory;
use Magento\Review\Model\Review;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Panth\ProductTabs\ViewModel\Tabs;
use PHPUnit\Framework\TestCase;

class TabsReviewsTest extends TestCase
{
    /**
     * @var array
     */
    private array $calls = [];

    private function storeManager(int $storeId = 3): StoreManagerInterface
    {
        $store = $this->createStub(Store::class);
        $store->method('getId')->willReturn($storeId);
        $storeManager = $this->createStub(StoreManagerInterface::class);
        $storeManager->method('getStore')->willReturn($store);
        return $storeManager;
    }

    private function registryWith($product): Registry
    {
        $registry = $this->createStub(Registry::class);
        $registry->method('registry')->willReturnCallback(
            static fn ($key) => $key === 'current_product' ? $product : null
        );
        return $registry;
    }

    private function product(?int $id): ProductInterface
    {
        $product = $this->createStub(ProductInterface::class);
        $product->method('getId')->willReturn($id);
        return $product;
    }

    private function collection(int $size = 0, array $items = []): ReviewCollection
    {
        $collection = $this->createStub(ReviewCollection::class);
        $record = function (string $name) use ($collection) {
            return function (...$args) use ($name, $collection) {
                $this->calls[] = [$name, $args];
                return $collection;
            };
        };
        foreach (['addStatusFilter', 'addEntityFilter', 'addStoreFilter', 'setDateOrder', 'setPageSize', 'setCurPage']
            as $method) {
            $collection->method($method)->willReturnCallback($record($method));
        }
        $collection->method('getSize')->willReturn($size);
        $collection->method('getIterator')->willReturn(new \ArrayIterator($items));
        return $collection;
    }

    private function factoryReturning(ReviewCollection $collection): ReviewCollectionFactory
    {
        $factory = $this->createStub(ReviewCollectionFactory::class);
        $factory->method('create')->willReturn($collection);
        return $factory;
    }

    public function testGetCurrentProductOnlyReturnsProductInstances(): void
    {
        $product = $this->product(1);
        $factory = $this->createStub(ReviewCollectionFactory::class);

        $this->assertSame($product, (new Tabs($this->registryWith($product), $factory, $this->storeManager()))
            ->getCurrentProduct());
        $this->assertNull((new Tabs($this->registryWith(new DataObject()), $factory, $this->storeManager()))
            ->getCurrentProduct());
    }

    public function testApprovedReviewCountForExplicitProduct(): void
    {
        $viewModel = new Tabs(
            $this->registryWith(null),
            $this->factoryReturning($this->collection(7)),
            $this->storeManager(3)
        );

        $this->assertSame(7, $viewModel->getApprovedReviewCount(42));
        $this->assertSame([
            ['addStatusFilter', [Review::STATUS_APPROVED]],
            ['addEntityFilter', ['product', 42]],
            ['addStoreFilter', [3]],
        ], $this->calls);
    }

    public function testApprovedReviewCountFallsBackToRegistryProduct(): void
    {
        $viewModel = new Tabs(
            $this->registryWith($this->product(9)),
            $this->factoryReturning($this->collection(2)),
            $this->storeManager()
        );

        $this->assertSame(2, $viewModel->getApprovedReviewCount(0));
        $this->assertSame(['addEntityFilter', ['product', 9]], $this->calls[1]);
    }

    public function testApprovedReviewCountWithoutProductSkipsQuery(): void
    {
        $factory = $this->createMock(ReviewCollectionFactory::class);
        $factory->expects($this->never())->method('create');

        $this->assertSame(0, (new Tabs($this->registryWith(null), $factory, $this->storeManager()))
            ->getApprovedReviewCount());
        $this->assertSame(0, (new Tabs($this->registryWith($this->product(null)), $factory, $this->storeManager()))
            ->getApprovedReviewCount(-1));
    }

    public function testApprovedReviewCountSwallowsErrors(): void
    {
        $factory = $this->createStub(ReviewCollectionFactory::class);
        $factory->method('create')->willThrowException(new \RuntimeException('db'));

        $this->assertSame(0, (new Tabs($this->registryWith(null), $factory, $this->storeManager()))
            ->getApprovedReviewCount(5));
    }

    public function testReviewItemsAreSanitisedAndLimited(): void
    {
        $items = [
            new DataObject([
                'title' => '<b>Great</b>',
                'nickname' => ' Jo &amp; Al ',
                'detail' => '&lt;i&gt;Fits well&lt;/i&gt;',
                'created_at' => '2024-01-01 10:00:00',
            ]),
            new DataObject(['title' => null, 'nickname' => ['x'], 'detail' => 12, 'created_at' => null]),
        ];
        $viewModel = new Tabs(
            $this->registryWith(null),
            $this->factoryReturning($this->collection(0, $items)),
            $this->storeManager()
        );

        $result = $viewModel->getReviewItems(4, 0);

        $this->assertSame([
            ['title' => 'Great', 'nickname' => 'Jo & Al', 'detail' => 'Fits well', 'created_at' => '2024-01-01 10:00:00'],
            ['title' => '', 'nickname' => '', 'detail' => '12', 'created_at' => ''],
        ], $result);
        $this->assertSame(['setDateOrder', ['DESC']], $this->calls[3]);
        $this->assertSame(['setPageSize', [1]], $this->calls[4]);
        $this->assertSame(['setCurPage', [1]], $this->calls[5]);
    }

    public function testReviewItemsUseRequestedLimit(): void
    {
        $viewModel = new Tabs(
            $this->registryWith($this->product(8)),
            $this->factoryReturning($this->collection()),
            $this->storeManager()
        );

        $this->assertSame([], $viewModel->getReviewItems(null, 25));
        $this->assertSame(['setPageSize', [25]], $this->calls[4]);
    }

    public function testReviewItemsSwallowErrors(): void
    {
        $storeManager = $this->createStub(StoreManagerInterface::class);
        $storeManager->method('getStore')->willThrowException(new \Magento\Framework\Exception\NoSuchEntityException());

        $viewModel = new Tabs($this->registryWith(null), $this->factoryReturning($this->collection()), $storeManager);

        $this->assertSame([], $viewModel->getReviewItems(3));
    }

    public function testDeeplyEncodedTextFallsBackToStrippingSpecialCharacters(): void
    {
        $value = '<b>x</b>';
        for ($i = 0; $i < 11; $i++) {
            $value = htmlspecialchars($value, ENT_QUOTES);
        }
        $viewModel = new Tabs(
            $this->registryWith(null),
            $this->createStub(ReviewCollectionFactory::class),
            $this->storeManager()
        );

        $this->assertSame('lt;bgt;xlt;/bgt;', $viewModel->toPlainText($value));
        $this->assertSame('1.5', $viewModel->toPlainText(1.5));
        $this->assertSame('', $viewModel->toPlainText(false));
    }
}

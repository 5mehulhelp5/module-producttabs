<?php
declare(strict_types=1);

namespace Panth\ProductTabs\ViewModel;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Review\Model\Review;
use Magento\Review\Model\ResourceModel\Review\CollectionFactory as ReviewCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class Tabs implements ArgumentInterface
{
    private const MAX_DECODE_PASSES = 10;

    private Registry $registry;

    private ReviewCollectionFactory $reviewCollectionFactory;

    private StoreManagerInterface $storeManager;

    public function __construct(
        Registry $registry,
        ReviewCollectionFactory $reviewCollectionFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->registry = $registry;
        $this->reviewCollectionFactory = $reviewCollectionFactory;
        $this->storeManager = $storeManager;
    }

    public function getCurrentProduct(): ?ProductInterface
    {
        $product = $this->registry->registry('current_product');
        return $product instanceof ProductInterface ? $product : null;
    }

    public function getApprovedReviewCount(?int $productId = null): int
    {
        $productId = $this->resolveProductId($productId);
        if ($productId <= 0) {
            return 0;
        }

        try {
            return (int) $this->createApprovedCollection($productId)->getSize();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getReviewItems(?int $productId = null, int $limit = 10): array
    {
        $productId = $this->resolveProductId($productId);
        if ($productId <= 0) {
            return [];
        }

        try {
            $collection = $this->createApprovedCollection($productId)
                ->setDateOrder()
                ->setPageSize(max(1, $limit))
                ->setCurPage(1);
            $items = [];
            foreach ($collection as $review) {
                $items[] = [
                    'title' => $this->toPlainText($review->getData('title')),
                    'nickname' => $this->toPlainText($review->getData('nickname')),
                    'detail' => $this->toPlainText($review->getData('detail')),
                    'created_at' => (string) $review->getData('created_at'),
                ];
            }
            return $items;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function toPlainText($value): string
    {
        $text = is_scalar($value) ? (string) $value : '';
        for ($pass = 0; $pass < self::MAX_DECODE_PASSES; $pass++) {
            $next = strip_tags(html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            if ($next === $text) {
                return trim($text);
            }
            $text = $next;
        }
        return trim(str_replace(['<', '>', '&'], '', $text));
    }

    private function resolveProductId(?int $productId): int
    {
        if ($productId !== null && $productId > 0) {
            return $productId;
        }
        $product = $this->getCurrentProduct();
        return $product && $product->getId() ? (int) $product->getId() : 0;
    }

    private function createApprovedCollection(int $productId)
    {
        return $this->reviewCollectionFactory->create()
            ->addStatusFilter(Review::STATUS_APPROVED)
            ->addEntityFilter('product', $productId)
            ->addStoreFilter((int) $this->storeManager->getStore()->getId());
    }
}

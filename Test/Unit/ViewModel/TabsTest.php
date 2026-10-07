<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Test\Unit\ViewModel;

use Magento\Framework\Registry;
use Magento\Review\Model\ResourceModel\Review\CollectionFactory as ReviewCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Panth\ProductTabs\ViewModel\Tabs;
use PHPUnit\Framework\TestCase;

class TabsTest extends TestCase
{
    private Tabs $viewModel;

    protected function setUp(): void
    {
        $this->viewModel = new Tabs(
            $this->createStub(Registry::class),
            $this->createStub(ReviewCollectionFactory::class),
            $this->createStub(StoreManagerInterface::class)
        );
    }

    public function testToPlainTextStripsRawMarkup(): void
    {
        $this->assertSame('Great x', $this->viewModel->toPlainText('Great <img src=x onerror=alert(1)>x'));
    }

    public function testToPlainTextStripsEncodedMarkup(): void
    {
        $this->assertSame('Nice', $this->viewModel->toPlainText('Nice &lt;img src=x onerror=alert(1)&gt;'));
    }

    public function testToPlainTextStripsDoubleEncodedMarkup(): void
    {
        $this->assertSame('aalert(1)', $this->viewModel->toPlainText('a&amp;lt;script&amp;gt;alert(1)&amp;lt;/script&amp;gt;'));
    }

    public function testToPlainTextKeepsPlainText(): void
    {
        $this->assertSame('Fits 5 < 6 & "ok"', $this->viewModel->toPlainText('Fits 5 &lt; 6 &amp; &quot;ok&quot;'));
    }

    public function testEscapedPlainTextHasNoMarkupAfterOneDecode(): void
    {
        $plain = $this->viewModel->toPlainText('x &lt;img src=x onerror=alert(1)&gt; <b>y</b>');
        $decoded = html_entity_decode(htmlspecialchars($plain, ENT_QUOTES, 'UTF-8'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $this->assertStringNotContainsString('<', $decoded);
        $this->assertSame('x  y', $decoded);
    }

    public function testToPlainTextHandlesNonScalar(): void
    {
        $this->assertSame('', $this->viewModel->toPlainText(null));
        $this->assertSame('', $this->viewModel->toPlainText(['a']));
    }

    public function testGetReviewItemsWithoutProductReturnsEmpty(): void
    {
        $this->assertSame([], $this->viewModel->getReviewItems());
        $this->assertSame(0, $this->viewModel->getApprovedReviewCount());
    }
}

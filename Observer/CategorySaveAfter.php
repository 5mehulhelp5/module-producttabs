<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CategorySaveAfter implements ObserverInterface
{
    public function execute(Observer $observer): void
    {
    }
}

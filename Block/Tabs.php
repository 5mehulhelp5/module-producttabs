<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Block;

use Magento\Catalog\Block\Product\View\Details;
use Magento\Framework\View\Element\Template\Context;
use Panth\ProductTabs\Helper\Data as ConfigHelper;
use Panth\Core\Helper\Theme;

class Tabs extends Details
{
    private ConfigHelper $configHelper;

    private Theme $themeHelper;

    public function __construct(
        Context $context,
        ConfigHelper $configHelper,
        Theme $themeHelper,
        array $data = []
    ) {
        $this->configHelper = $configHelper;
        $this->themeHelper = $themeHelper;
        parent::__construct($context, $data);
    }

    public function getTemplate()
    {
        if ($this->themeHelper->isHyva()) {
            return 'Panth_ProductTabs::hyva/tabs.phtml';
        }
        return 'Panth_ProductTabs::tabs.phtml';
    }

    public function isEnabled(): bool
    {
        return $this->configHelper->isEnabled();
    }

    public function getTabStyle(): string
    {
        return $this->configHelper->getTabStyle();
    }

    public function getAnimationType(): string
    {
        return $this->configHelper->getAnimationType();
    }

    public function isFirstTabOpen(): bool
    {
        return $this->configHelper->isFirstTabOpen();
    }

    public function isAccordionOnMobile(): bool
    {
        return $this->configHelper->isAccordionOnMobile();
    }

    protected function _toHtml(): string
    {
        if (!$this->isEnabled()) {
            return '';
        }
        return parent::_toHtml();
    }
}

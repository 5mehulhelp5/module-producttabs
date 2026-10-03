<?php
declare(strict_types=1);

namespace Panth\ProductTabs\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Panth\ProductTabs\Helper\Data as ConfigHelper;

class Config implements ArgumentInterface
{
    private ConfigHelper $configHelper;

    public function __construct(
        ConfigHelper $configHelper
    ) {
        $this->configHelper = $configHelper;
    }

    public function isEnabled(): bool
    {
        return $this->configHelper->isEnabled();
    }

    public function isStickyTabs(): bool
    {
        return $this->configHelper->isStickyTabs();
    }

    public function isLazyLoadReviews(): bool
    {
        return $this->configHelper->isLazyLoadReviews();
    }

    public function getDefaultTab(): string
    {
        return $this->configHelper->getDefaultTab();
    }

    public function getTabStyle(): string
    {
        return $this->configHelper->getTabStyle();
    }

    public function getAnimationType(): string
    {
        return $this->configHelper->getAnimationType();
    }

    public function getMobileBehavior(): string
    {
        return $this->configHelper->getMobileBehavior();
    }

    public function isFirstTabOpen(): bool
    {
        return $this->configHelper->isFirstTabOpen();
    }

    public function isAccordionOnMobile(): bool
    {
        return $this->configHelper->isAccordionOnMobile();
    }

    public function isShowTabIcon(): bool
    {
        return $this->configHelper->isShowTabIcon();
    }

    public function getDescriptionLabel(): string
    {
        return $this->configHelper->getDescriptionLabel();
    }

    public function getMoreInfoLabel(): string
    {
        return $this->configHelper->getMoreInfoLabel();
    }

    public function getReviewsLabel(): string
    {
        return $this->configHelper->getReviewsLabel();
    }

    public function showDescription(): bool
    {
        return $this->configHelper->showDescription();
    }

    public function showMoreInfo(): bool
    {
        return $this->configHelper->showMoreInfo();
    }

    public function showReviews(): bool
    {
        return $this->configHelper->showReviews();
    }

    public function getDescriptionOrder(): int
    {
        return $this->configHelper->getDescriptionOrder();
    }

    public function getMoreInfoOrder(): int
    {
        return $this->configHelper->getMoreInfoOrder();
    }

    public function getReviewsOrder(): int
    {
        return $this->configHelper->getReviewsOrder();
    }

    public function getCustomCmsTabs(): array
    {
        return $this->configHelper->getCustomCmsTabs();
    }

    public function getCustomAttributeTabs(): array
    {
        return $this->configHelper->getCustomAttributeTabs();
    }

    public function getTabConfig(): array
    {
        return [
            'tabStyle' => $this->getTabStyle(),
            'animationType' => $this->getAnimationType(),
            'firstTabOpen' => $this->isFirstTabOpen(),
            'accordionOnMobile' => $this->isAccordionOnMobile(),
            'showTabIcon' => $this->isShowTabIcon(),
        ];
    }

    public function getAllTabsConfig(): array
    {
        return [
            'enabled' => $this->isEnabled(),
            'stickyTabs' => $this->isStickyTabs(),
            'lazyLoadReviews' => $this->isLazyLoadReviews(),
            'defaultTab' => $this->getDefaultTab(),
            'tabStyle' => $this->getTabStyle(),
            'animationType' => $this->getAnimationType(),
            'mobileBehavior' => $this->getMobileBehavior(),
            'firstTabOpen' => $this->isFirstTabOpen(),
            'accordionOnMobile' => $this->isAccordionOnMobile(),
            'showTabIcon' => $this->isShowTabIcon(),
            'labels' => [
                'description' => $this->getDescriptionLabel(),
                'moreInfo' => $this->getMoreInfoLabel(),
                'reviews' => $this->getReviewsLabel(),
            ],
            'visibility' => [
                'description' => $this->showDescription(),
                'moreInfo' => $this->showMoreInfo(),
                'reviews' => $this->showReviews(),
            ],
            'sortOrder' => [
                'description' => $this->getDescriptionOrder(),
                'moreInfo' => $this->getMoreInfoOrder(),
                'reviews' => $this->getReviewsOrder(),
            ],
            'customCmsTabs' => $this->getCustomCmsTabs(),
            'customAttributeTabs' => $this->getCustomAttributeTabs(),
        ];
    }
}

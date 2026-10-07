<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    private const XML_PATH_ENABLED = 'panth_producttabs/general/enabled';
    private const XML_PATH_STICKY_TABS = 'panth_producttabs/general/sticky_tabs';
    private const XML_PATH_LAZY_LOAD_REVIEWS = 'panth_producttabs/general/lazy_load_reviews';
    private const XML_PATH_DEFAULT_TAB = 'panth_producttabs/general/default_tab';
    private const XML_PATH_TAB_STYLE = 'panth_producttabs/design/tab_style';
    private const XML_PATH_ANIMATION_TYPE = 'panth_producttabs/design/animation_type';
    private const XML_PATH_MOBILE_BEHAVIOR = 'panth_producttabs/design/mobile_behavior';
    private const XML_PATH_FIRST_TAB_OPEN = 'panth_producttabs/design/first_tab_open';
    private const XML_PATH_ACCORDION_ON_MOBILE = 'panth_producttabs/design/accordion_on_mobile';
    private const XML_PATH_SHOW_TAB_ICON = 'panth_producttabs/design/show_tab_icon';
    private const XML_PATH_DESCRIPTION_LABEL = 'panth_producttabs/labels/description_label';
    private const XML_PATH_MORE_INFO_LABEL = 'panth_producttabs/labels/more_info_label';
    private const XML_PATH_REVIEWS_LABEL = 'panth_producttabs/labels/reviews_label';
    private const XML_PATH_SHOW_DESCRIPTION = 'panth_producttabs/visibility/show_description';
    private const XML_PATH_SHOW_MORE_INFO = 'panth_producttabs/visibility/show_more_info';
    private const XML_PATH_SHOW_REVIEWS = 'panth_producttabs/visibility/show_reviews';
    private const XML_PATH_DESCRIPTION_ORDER = 'panth_producttabs/sort_order/description_order';
    private const XML_PATH_MORE_INFO_ORDER = 'panth_producttabs/sort_order/more_info_order';
    private const XML_PATH_REVIEWS_ORDER = 'panth_producttabs/sort_order/reviews_order';
    private const XML_PATH_CUSTOM_CMS_TABS = 'panth_producttabs/custom_cms_tabs/cms_tabs';
    private const XML_PATH_CUSTOM_ATTRIBUTE_TABS = 'panth_producttabs/custom_attribute_tabs/attr_tabs';

    private Json $jsonSerializer;

    public function __construct(
        Context $context,
        Json $jsonSerializer
    ) {
        parent::__construct($context);
        $this->jsonSerializer = $jsonSerializer;
    }

    public function isEnabled($storeId = null): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isStickyTabs($storeId = null): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_STICKY_TABS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isLazyLoadReviews($storeId = null): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_LAZY_LOAD_REVIEWS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getDefaultTab($storeId = null): string
    {
        return (string) ($this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_TAB,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ) ?: 'first');
    }

    public function getTabStyle($storeId = null): string
    {
        return (string) ($this->scopeConfig->getValue(
            self::XML_PATH_TAB_STYLE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ) ?: 'horizontal');
    }

    public function getAnimationType($storeId = null): string
    {
        return (string) ($this->scopeConfig->getValue(
            self::XML_PATH_ANIMATION_TYPE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ) ?: 'fade');
    }

    public function getMobileBehavior($storeId = null): string
    {
        return (string) ($this->scopeConfig->getValue(
            self::XML_PATH_MOBILE_BEHAVIOR,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ) ?: 'accordion');
    }

    public function isFirstTabOpen($storeId = null): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_FIRST_TAB_OPEN,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isAccordionOnMobile($storeId = null): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_ACCORDION_ON_MOBILE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isShowTabIcon($storeId = null): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_SHOW_TAB_ICON,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getDescriptionLabel($storeId = null): string
    {
        return (string) ($this->scopeConfig->getValue(
            self::XML_PATH_DESCRIPTION_LABEL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ) ?: 'Description');
    }

    public function getMoreInfoLabel($storeId = null): string
    {
        return (string) ($this->scopeConfig->getValue(
            self::XML_PATH_MORE_INFO_LABEL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ) ?: 'More Information');
    }

    public function getReviewsLabel($storeId = null): string
    {
        return (string) ($this->scopeConfig->getValue(
            self::XML_PATH_REVIEWS_LABEL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ) ?: 'Reviews');
    }

    public function showDescription($storeId = null): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_SHOW_DESCRIPTION,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function showMoreInfo($storeId = null): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_SHOW_MORE_INFO,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function showReviews($storeId = null): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_SHOW_REVIEWS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getDescriptionOrder($storeId = null): int
    {
        return (int) ($this->scopeConfig->getValue(
            self::XML_PATH_DESCRIPTION_ORDER,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ) ?: 10);
    }

    public function getMoreInfoOrder($storeId = null): int
    {
        return (int) ($this->scopeConfig->getValue(
            self::XML_PATH_MORE_INFO_ORDER,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ) ?: 20);
    }

    public function getReviewsOrder($storeId = null): int
    {
        return (int) ($this->scopeConfig->getValue(
            self::XML_PATH_REVIEWS_ORDER,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ) ?: 30);
    }

    public function getCustomCmsTabs($storeId = null): array
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_CUSTOM_CMS_TABS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if (empty($value)) {
            return [];
        }

        if (is_string($value)) {
            try {
                $value = $this->jsonSerializer->unserialize($value);
            } catch (\Exception $e) {
                return [];
            }
        }

        return is_array($value) ? $value : [];
    }

    public function getCustomAttributeTabs($storeId = null): array
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_CUSTOM_ATTRIBUTE_TABS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if (empty($value)) {
            return [];
        }

        if (is_string($value)) {
            try {
                $value = $this->jsonSerializer->unserialize($value);
            } catch (\Exception $e) {
                return [];
            }
        }

        return is_array($value) ? $value : [];
    }
}

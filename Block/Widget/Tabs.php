<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Block\Widget;

use Magento\Framework\App\ObjectManager;
use Magento\Widget\Block\BlockInterface;
use Magento\Framework\View\Element\Template\Context;
use Panth\ProductTabs\Block\Tabs as TabsBlock;
use Panth\ProductTabs\Helper\Data as ConfigHelper;
use Panth\ProductTabs\ViewModel\Config as ConfigViewModel;
use Panth\ProductTabs\ViewModel\Tabs as TabsViewModel;
use Panth\Core\Helper\Theme;

class Tabs extends TabsBlock implements BlockInterface
{
    protected $_template = 'Panth_ProductTabs::tabs.phtml';

    public function __construct(
        Context $context,
        ConfigHelper $configHelper,
        Theme $themeHelper,
        ConfigViewModel $configViewModel,
        array $data = [],
        ?TabsViewModel $tabsViewModel = null
    ) {
        $data['panth_config'] = $configViewModel;
        if (!isset($data['view_model'])) {
            $data['view_model'] = $tabsViewModel ?? ObjectManager::getInstance()->get(TabsViewModel::class);
        }
        parent::__construct($context, $configHelper, $themeHelper, $data);
    }
}

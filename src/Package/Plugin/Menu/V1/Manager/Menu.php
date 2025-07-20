<?php
namespace Ababilithub\FlexMasterPro\Package\Plugin\Menu\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Menu\V1\Contract\Menu as MenuContract, 
    FlexWordpress\Package\Menu\V1\Factory\Menu as MenuFactory,
    FlexMasterPro\Package\Plugin\Menu\V1\Concrete\Main\Menu as MainMenu,
    FlexMasterPro\Package\Plugin\Menu\V1\Concrete\Posttype\CompanyInfo\Menu as CompanyInfoMenu,
    FlexMasterPro\Package\Plugin\Menu\V1\Concrete\Posttype\ImportantLink\Menu as ImportantLinkMenu,
    FlexMasterPro\Package\Plugin\Menu\V1\Concrete\Posttype\Typography\Menu as TypographyMenu,
    FlexMasterPro\Package\Plugin\Menu\V1\Concrete\Posttype\ColorScheme\Menu as ColorSchemeMenu,
    FlexMasterPro\Package\Plugin\Menu\V1\Concrete\Option\Menu as OptionBoxMenu, 
};

class  Menu extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }
    
    public function init()
    {
        $this->set_items(
            [
                MainMenu::class,
                CompanyInfoMenu::class,
                // ImportantLinkMenu::class,
                // ColorSchemeMenu::class,
                // TypographyMenu::class,
                OptionBoxMenu::class,                    
            ]
        );
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $item) 
        {
            $item_instance = MenuFactory::get($item);

            if ($item_instance instanceof MenuContract) 
            {
                $item_instance->register();
            }
        }
    }
}
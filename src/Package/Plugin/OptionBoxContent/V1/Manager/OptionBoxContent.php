<?php
namespace Ababilithub\FlexMasterPro\Package\Plugin\OptionBoxContent\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\OptionBoxContent\V1\Factory\OptionBoxContent as OptionBoxContentFactory,
    FlexWordpress\Package\OptionBoxContent\V1\Contract\OptionBoxContent as OptionBoxContentContract, 
    FlexMasterPro\Package\Plugin\OptionBoxContent\V1\Concrete\Section\CompanyInfo\OptionBoxContent as CompanyInfoOptionBoxContent,
    FlexMasterPro\Package\Plugin\OptionBoxContent\V1\Concrete\Section\ColorScheme\OptionBoxContent as ColorSchemeOptionBoxContent,      
};

class OptionBoxContent extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    protected function init(): void
    {
        $this->set_items([
            CompanyInfoOptionBoxContent::class,
            ColorSchemeOptionBoxContent::class,
            // Add more posttype classes here...
        ]);
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $itemClass) 
        {
            $itemObject = OptionBoxContentFactory::get($itemClass);

            if ($itemObject instanceof OptionBoxContentContract) 
            {
                $itemObject->init();
            }
        }
    }
}

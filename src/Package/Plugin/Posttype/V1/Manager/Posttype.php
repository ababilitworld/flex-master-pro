<?php
namespace Ababilithub\FlexMasterPro\Package\Plugin\Posttype\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Posttype\V1\Factory\Posttype as PosttypeFactory,
    FlexWordpress\Package\Posttype\V1\Contract\Posttype as PosttypeContract, 
    FlexMasterPro\Package\Plugin\Posttype\V1\Concrete\CompanyInfo\Posttype as CompanyInfoPosttype,
    FlexMasterPro\Package\Plugin\Posttype\V1\Concrete\ColorScheme\Posttype as ColorSchemePosttype,   
};

class Posttype extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    protected function init(): void
    {
        $this->set_items([
            CompanyInfoPosttype::class,
            ColorSchemePosttype::class,
            // Add more posttype classes here...
        ]);
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $itemClass) 
        {
            $posttype = PosttypeFactory::get($itemClass);

            if ($posttype instanceof PosttypeContract) 
            {
                $posttype->register();
            }
        }
    }
}

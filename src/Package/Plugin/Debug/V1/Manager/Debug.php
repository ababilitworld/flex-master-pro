<?php
namespace Ababilithub\FlexMasterPro\Package\Plugin\Debug\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Debug\V1\Contract\Debug as DebugContract, 
    FlexWordpress\Package\Debug\V1\Factory\Debug as DebugFactory,
    FlexWordpress\Package\Debug\V1\Concrete\WpError\Debug as WpErrorDebug, 
};

class  Debug extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->set_items(
                [
                WpErrorDebug::class,
            ]
        );
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $item) 
        {
            $item_instance = DebugFactory::get($item);

            if ($item_instance instanceof DebugContract) 
            {
                $item_instance->init();
            }
        }
    }
}
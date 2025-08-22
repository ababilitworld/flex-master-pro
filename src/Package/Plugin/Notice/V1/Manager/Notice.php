<?php
namespace Ababilithub\FlexMasterPro\Package\Plugin\Notice\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Notice\V1\Contract\Notice as NoticeContract, 
    FlexWordpress\Package\Notice\V1\Factory\Notice as NoticeFactory,    
    FlexWordpress\Package\Notice\V1\Concrete\Transient\Notice as TransientNotice,
    FlexWordpress\Package\Notice\V1\Concrete\WpError\Notice as WpErrorNotice, 
};

class  Notice extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->set_items(
            [
                TransientNotice::class,
                WpErrorNotice::class,
            ]
        );
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $item) 
        {
            $item_instance = NoticeFactory::get($item);

            if ($item_instance instanceof NoticeContract) 
            {
                $item_instance->init();
            }
        }
    }
}
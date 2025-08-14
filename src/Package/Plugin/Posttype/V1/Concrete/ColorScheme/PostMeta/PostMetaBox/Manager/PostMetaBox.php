<?php
namespace Ababilithub\FlexMasterPro\Package\Plugin\Posttype\V1\Concrete\ColorScheme\PostMeta\PostMetaBox\Manager;
    
(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\PostMetaBox\V1\Contract\PostMetaBox as PostMetaBoxContract, 
    FlexWordpress\Package\PostMetaBox\V1\Factory\PostMetaBox as PostMetaBoxFactory,
    FlexMasterPro\Package\Plugin\Posttype\V1\Concrete\ColorScheme\PostMeta\PostMetaBox\Concrete\PostMetaBoxOne\PostMetaBox as ColorSchemePostMetaBox,
};

class  PostMetaBox extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->set_items(
                [
                ColorSchemePostMetaBox::class,
            ]
        );
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $item) 
        {
            $item_instance = PostMetaBoxFactory::get($item);

            if ($item_instance instanceof PostMetaBoxContract) 
            {
                $item_instance->register();
            }
        }
    }
}
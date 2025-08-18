<?php
namespace Ababilithub\FlexMasterPro\Package\Plugin\Production;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexMasterPro\Package\Plugin\Menu\V1\Manager\Menu as MenuManager,
    FlexMasterPro\Package\Plugin\Posttype\V1\Manager\Posttype as PosttypeManager,
    FlexMasterPro\Package\Plugin\Shortcode\V1\Manager\Shortcode as ShortcodeManager, 
    FlexMasterPro\Package\Plugin\OptionBox\V1\Manager\OptionBox as OptionBoxManager,
    FlexMasterPro\Package\Plugin\OptionBoxContent\V1\Manager\OptionBoxContent as OptionBoxContentManager,
    FlexMasterPro\Package\Plugin\Debug\V1\Manager\Debug as DebugManager,
};

if (!class_exists(__NAMESPACE__.'\Production')) 
{
    class Production 
    {
        use StandardMixin;

        public function __construct($data = []) 
        {
            $this->init();      
        }

        public function init() 
        {
            add_action('init', function () {
                (new DebugManager())->boot();
            });


            // add_action('init', function () {
            //     (new TaxonomyManager())->boot();
            // });

            add_action('init', function () {
                (new PosttypeManager())->boot();
            });

            add_action('init', function () {
                (new ShortcodeManager())->boot();
            });

            add_action('init', function () {
                (new OptionBoxManager())->boot();
            });

            add_action('init', function () {
                (new OptionBoxContentManager())->boot();
            });
            
            // Initialize only once on admin_menu
            add_action('init', function () {
                (new MenuManager())->boot();
            });

        }
        
    }
}
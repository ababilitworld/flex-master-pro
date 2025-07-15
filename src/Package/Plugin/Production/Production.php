<?php
namespace Ababilithub\FlexMasterPro\Package\Plugin\Production;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexMasterPro\Package\Plugin\Menu\V1\Manager\Menu as MenuManager,
    FlexMasterPro\Package\Plugin\Audit\V1\Manager\Audit as AuditManager,
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

            // add_action('init', function () {
            //     (new TaxonomyManager())->boot();
            // });

            // add_action('init', function () {
            //     (new PosttypeManager())->boot();
            // });

            // add_action('init', function () {
            //     (new ShortcodeManager())->boot();
            // });

            // Initialize only once on admin_menu
            add_action('init', function () {
                (new AuditManager())->boot();
            });

            // Initialize only once on admin_menu
            add_action('init', function () {
                (new MenuManager())->boot();
            });

        }
        
    }
}
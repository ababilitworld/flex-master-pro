<?php

namespace Ababilithub\FlexELand\Package\Plugin\Posttype\Land\Document\Presentation;

(defined('ABSPATH') && defined('WPINC')) || die();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
};

if (!class_exists(__NAMESPACE__.'\Presentation')) 
{
    class Presentation 
    {
        use StandardMixin;

        public function __construct() 
        {
           //
        }
    }
}

?>

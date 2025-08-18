<?php
namespace Ababilithub\FlexWordpress\Package\Debug\V1\Concrete\WpError;

use Ababilithub\{
    FlexWordpress\Package\Debug\V1\Base\Debug as BaseDebug
};

use const Ababilithub\{
    FlexMasterPro\PLUGIN_PRE_HYPH,
    FlexMasterPro\PLUGIN_PRE_UNDS,
};

class Debug extends BaseDebug
{
    public function init(array $data = []): static
    {
        $this->debugger = new \WP_Error();
        $this->init_service();
        $this->init_hook();
        return $this;
    }

    public function init_service():void
    {

    }

    public function init_hook():void
    {
        add_action('admin_notices',[$this,'render']);
    }
    
    public function render() : void
    {
        if($this->debugger->has_errors())
        {
            foreach($this->debugger->get_error_messages() as $error)
            {
                $class = 'notice notice-error is-dismissible';
                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses_post( $error ) );
            }
            
        }
    }

    public function add_error(array $error = []) : static
    {
        $this->debugger->add($error['code'], $error['message'],$error['data']);
        return $this;
    }
    
}
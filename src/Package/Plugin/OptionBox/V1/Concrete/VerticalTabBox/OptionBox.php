<?php
namespace Ababilithub\FlexMasterPro\Package\Plugin\OptionBox\V1\Concrete\VerticalTabBox;

use Ababilithub\{
    FlexWordpress\Package\OptionBox\V1\Base\OptionBox as BaseOptionBox,
    FlexWordpress\Package\Debug\V1\Factory\Debug as DebugFactory,
    FlexWordpress\Package\Debug\V1\Concrete\WpError\Debug as WpErrorDebug,
};

use const Ababilithub\{
    FlexMasterPro\PLUGIN_PRE_HYPH,
    FlexMasterPro\PLUGIN_PRE_UNDS,
};

class OptionBox extends BaseOptionBox 
{
    public const OPTION_NAME = PLUGIN_PRE_UNDS.'_'.'options';
    public array $option_value = [];
    private $debugger;
    public $show_notice = false;
    private $redirect_url_after_update_option;
    public function init(array $data = []) : static
    {
        $this->id = $data['id'] ?? PLUGIN_PRE_HYPH.'-'.'vertical-tab-options';
        $this->title = $data['title'] ?? 'Attributes';
        $this->redirect_url_after_update_option = admin_url('admin.php?page=flex-master-pro-option');
        $this->init_service();
        $this->init_hook();
        return $this;
    }

    public function init_service():void
    {
        $this->debugger = DebugFactory::get(WpErrorDebug::class);
    }

    public function init_hook():void
    {

        // Add filter for processing save data
        add_action('admin_init',[$this,'save']);
    }

    public function render(): void
    {
        ?>
        <div class="fpba">
            <div class="meta-box">
                <form method="post" action="">
                    <?php wp_nonce_field($this->id.'_nonce_action'); ?>
                    <input type="hidden" name="option_page" value="<?php echo esc_attr($this->id); ?>">
                    
                    <div class="app-container">
                        
                        <div class="vertical-tabs">
                            <div class="tabs-header">
                                <button class="toggle-tabs" id="toggleTabs">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <span class="tabs-title"><?php echo $this->title;?></span>
                            </div>
                            <ul class="tab-items">
                                <?php do_action($this->id.'_'.'tab_item'); ?>
                            </ul>
                        </div>
                        <main class="content-area">
                            <?php do_action($this->id.'_'.'tab_content'); ?>
                        </main>
                    </div>

                    <?php submit_button(__('Save Settings', 'text-domain')); ?>
                    
                </form>
            </div>
        </div>
        <?php
    }

    public function save(): void
    {
        if (!$this->is_valid_save_request() && $this->verify_save_security()) 
        {
            return;
        }

        // Initialize with empty array
        $prepared_data = [];

        // Allow content sections to prepare their data
        $prepared_data = apply_filters(PLUGIN_PRE_UNDS.'_prepare_option_data',[]);
        
        $option_saved = $this->update_option($prepared_data);

        if ($option_saved) 
        {
            wp_safe_redirect($this->redirect_url_after_update_option);
            exit;
        }
    }

    protected function is_valid_save_request(): bool
    {
        return (
            isset($_POST['submit']) && 
            $_SERVER['REQUEST_METHOD'] === 'POST' && 
            isset($_POST['option_page']) && 
            $_POST['option_page'] === $this->id
        );
    }

    protected function verify_save_security(): array
    {
        $response = [];
        // Verify nonce
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], $this->id.'_nonce_action')) 
        {
            $response['status'] = false;
            $response['message'] = __('Security check failed', 'text-domain');
            return $response;
        }

        // Check user capabilities
        if (!current_user_can('manage_options')) 
        {
            $response['status'] = false;
            $response['message'] = __('Authorization failed', 'text-domain');
            return $response;
        }

        $response['status'] = true;
        $response['message'] = __('No Security Issue Found !!!', 'text-domain');
        return $response;
    }

    public function update_option(array $new_data = []): bool
    {
        // Get current options
        $current_options = get_option(self::OPTION_NAME, []);
        if(empty($current_options))$current_options = [];
        
        $updated_options = array_merge($current_options, $new_data);
        
        return update_option(self::OPTION_NAME, $updated_options);
    }

    public function display_success_notice(): void
    {
        // Start session if not already started
        if (!session_id()) 
        {
            session_start();
        }
        
        if (!empty($_SESSION[PLUGIN_PRE_UNDS.'_show_notice'])) 
        {
            // Clear the flag
            unset($_SESSION[PLUGIN_PRE_UNDS.'_show_notice']);
            
            // Show notice
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e('Settings saved successfully!', 'text-domain'); ?></p>
            </div>
            <?php
        }
    }

}
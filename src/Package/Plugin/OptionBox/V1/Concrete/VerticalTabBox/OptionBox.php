<?php
namespace Ababilithub\FlexMasterPro\Package\Plugin\OptionBox\V1\Concrete\VerticalTabBox;

use Ababilithub\{
    FlexWordpress\Package\OptionBox\V1\Base\OptionBox as BaseOptionBox
};

use const Ababilithub\{
    FlexMasterPro\PLUGIN_PRE_HYPH,
    FlexMasterPro\PLUGIN_PRE_UNDS,
};

class OptionBox extends BaseOptionBox 
{
    public const OPTION_NAME = PLUGIN_PRE_UNDS.'_'.'options';
    public array $option_value = [];
    public $show_notice = false;
    public function init(array $data = []) : static
    {
        $this->id = $data['id'] ?? PLUGIN_PRE_HYPH.'-'.'vertical-tab-options';
        $this->title = $data['title'] ?? 'Attributes';
        $this->init_service();
        $this->init_hook();
        return $this;
    }

    public function init_service():void
    {

    }

    public function init_hook():void
    {

        // Add filter for processing save data
        add_filter(PLUGIN_PRE_UNDS.'_process_save_data', [$this, 'process_save_data']);
        add_action(PLUGIN_PRE_UNDS.'_'.'save_option',[$this,'save_option']);
        add_action('admin_init',[$this,'save']);
        add_action('admin_notices', [$this, 'display_success_notice']);
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

    public function process_save_data(array $saved_data, array $post_data): array
    {
        // Allow other content instances to modify the saved data
        $saved_data = apply_filters(PLUGIN_PRE_UNDS.'_before_save_data', $saved_data, $post_data);
        
        // Process core fields
        if (isset($post_data['company'])) {
            $saved_data['companies'] = [
                'selected' => absint($post_data['company']),
            ];
        }
        
        return $saved_data;
    }

    public function save(): void
    {
        if (!$this->is_valid_save_request() && $this->verify_save_security()) 
        {
            return;
        }

        // Initialize with empty array
        $ready_data = $prepared_data = $processed_data = $ready_data = [];

        // Allow content sections to prepare their data
        $prepared_data = apply_filters(PLUGIN_PRE_UNDS.'_prepare_save_data', $ready_data);
        // Process the data
        $processed_data = $this->process_save_data($prepared_data, $_POST);

        // Allow final modifications before saving
        $ready_data = apply_filters(PLUGIN_PRE_UNDS.'_before_option_update', $processed_data);

        $option_saved = $this->update_option($ready_data);

        if ($option_saved) 
        {
             // Start session if not already started
            if (!session_id()) 
            {
                session_start();
            }
            
            // Set session flag
            $_SESSION[PLUGIN_PRE_UNDS.'_show_notice'] = true;
            
            wp_safe_redirect(wp_get_referer());
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
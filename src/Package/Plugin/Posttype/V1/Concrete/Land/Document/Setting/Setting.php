<?php
    namespace Ababilithub\FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Document\Setting;

    (defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

    use Ababilithub\{
        FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
        FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Document\Setting\General\Setting as GeneralSetting
    };

    use const Ababilithub\{
        FlexELand\PLUGIN_NAME,
        FlexELand\PLUGIN_DIR,
        FlexELand\PLUGIN_URL,
        FlexELand\PLUGIN_FILE,
        FlexELand\PLUGIN_PRE_UNDS,
        FlexELand\PLUGIN_PRE_HYPH,
        FlexELand\PLUGIN_VERSION,
        FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Document\POSTTYPE,
    };

    
    if (!class_exists(__NAMESPACE__.'\Setting')) 
    {
        class Setting 
        {
            use StandardMixin;

            private $posttype;
            private $general_setting;

            public function __construct() 
            {
                $this->init();
            }

            private function init() 
            {
                $this->posttype = POSTTYPE;
                $this->general_setting = GeneralSetting::getInstance();
                add_action('add_meta_boxes', array($this, 'meta_box'));       
            }

            public function meta_box($post_type) 
            {
                if ($post_type === POSTTYPE) 
                {
                    add_meta_box(
                        PLUGIN_PRE_UNDS.'_'.POSTTYPE.'_meta_box', 
                        '<span class="fas fa-cogs"></span>' . esc_html__(' Attributes : ', 'flex-eland') . get_the_title(get_the_id()),
                        array($this, 'settings'));
                }
            }
            
            public function settings() 
            {
                $post_id = get_the_ID();
                ?>
                <div class="fpba">
                    <div class="meta-box">
                        <div class="app-container">
                            <div class="vertical-tabs">
                                <div class="tabs-header">
                                    <button class="toggle-tabs" id="toggleTabs">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <span class="tabs-title">Attributes</span>
                                </div>
                                <ul class="tab-items">
                                    <!-- <li class="tab-item active" data-tab="dashboard">
                                        <a href="#" class="tab-link">
                                            <i class="fas fa-home"></i>
                                            <span class="tab-text">Dashboard</span>
                                        </a>
                                    </li> -->
                                    <?php do_action(PLUGIN_PRE_UNDS.'_'.POSTTYPE.'_'.'setting_tab_item'); ?>
                                </ul>
                            </div>
                            <main class="content-area">
                                <!-- <div class="tab-content active" id="dashboard">
                                    <h1>Dashboard</h1>
                                    <p>Welcome to your dashboard. This is the main content area.</p>
                                </div> -->
                                <?php do_action(PLUGIN_PRE_UNDS.'_'.POSTTYPE.'_'.'setting_tab_content',$post_id); ?>
                            </main>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
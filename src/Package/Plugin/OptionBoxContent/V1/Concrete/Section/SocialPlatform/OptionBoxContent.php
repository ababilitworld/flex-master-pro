<?php
namespace Ababilithub\FlexMasterPro\Package\Plugin\OptionBoxContent\V1\Concrete\Section\SocialPlatform;

use Ababilithub\{
    FlexWordpress\Package\Option\V1\Mixin\Option as OptionMixin,
    FlexWordpress\Package\OptionBoxContent\V1\Base\OptionBoxContent as BaseOptionBoxContent,
    FlexPhp\Package\Form\Field\V1\Factory\Field as FieldFactory,
    FlexPhp\Package\Form\Field\V1\Concrete\Text\Field as TextField,
    FlexPhp\Package\Form\Field\V1\Concrete\Select\Field as SelectField,
    FlexMasterPro\Package\Plugin\OptionBox\V1\Concrete\VerticalTabBox\OptionBox as VerticalTabBoxOptionBox,
    
};

use const Ababilithub\{
    FlexMasterPro\PLUGIN_PRE_HYPH,
    FlexMasterPro\PLUGIN_PRE_UNDS,
};

class OptionBoxContent extends BaseOptionBoxContent 
{
    use OptionMixin;
    
    public $option_name;
    public array $option_value = [];

    public function init(array $data = []): static
    {
        $this->tab_id = PLUGIN_PRE_HYPH.'-'.'vertical-tab-options';
        $this->tab_item_id = $this->tab_id.'_social_platform_settings';
        $this->tab_item_label = esc_html__('Social Platform');
        $this->tab_item_icon = 'fas fa-share-alt';
        $this->tab_item_status = 'not-active';
        $this->option_name = VerticalTabBoxOptionBox::OPTION_NAME;
        $this->option_value = $this->get_option_value();
        //echo "<pre>";print_r($this->option_value);echo "</pre>";exit;
        $this->init_service();
        $this->init_hook();
        return $this;
    }

    protected function init_service(): void
    {
        // Service initialization logic can be added here
    }

    protected function init_hook(): void
    {
        add_action($this->tab_id.'_tab_item', [$this, 'tab_item']);
        add_action($this->tab_id.'_tab_content', [$this, 'tab_content']);
        add_filter(PLUGIN_PRE_UNDS.'_prepare_option_data', [$this, 'prepare_option_data']);

    }

    public function prepare_option_data(array $data): array
    {
        if (isset($_POST['facebook-url'])) 
        {
            $data['social_platform_settings'] = [                
                'social_platform_youtube_url' => esc_url($_POST['youtube-url']),
                'social_platform_facebook_url' => esc_url($_POST['facebook-url']),
                'social_platform_linkedin_url' => esc_url($_POST['linkedin-url']),
                'social_platform_twitter_url' => esc_url($_POST['twitter-url']),
                'social_platform_instagram_url' => esc_url($_POST['instagram-url']),
            ];
        }
        
        return $data;
    }

    public function render(): void
    {
        $option_values = $this->get_option_box_content_values();
        //echo "<pre>";print_r(array($option_values));echo "</pre>";exit;
        
        ?>
        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Social Platform Settings</h2>
            </div>
            <div class="panel-body">
                <div class="panel-row">
                    <?php
                        $field = '';
                        $field = FieldFactory::get(TextField::class);
                        $field->init([
                            'name' => 'youtube-url',
                            'id' => 'youtube-url',
                            'label' => 'Youtube',
                            'class' => 'custom-file-input',
                            'required' => false,
                            'help_text' => 'Enter Youtube Url',
                            'value' => $option_values['social_platform_settings']['social_platform_youtube_url'] ?? '',
                            'attributes' => [
                                'data-preview-size' => '150'
                            ]
                        ])->render();
                    ?>
                </div>
                <div class="panel-row">
                    <?php
                        $field = '';
                        $field = FieldFactory::get(TextField::class);
                        $field->init([
                            'name' => 'facebook-url',
                            'id' => 'facebook-url',
                            'label' => 'Facebook',
                            'class' => 'custom-file-input',
                            'required' => false,
                            'help_text' => 'Enter Facebook Url',
                            'value' => $option_values['social_platform_settings']['social_platform_facebook_url'] ?? '',
                            'attributes' => [
                                'data-preview-size' => '150'
                            ]
                        ])->render();
                    ?>
                </div>
                <div class="panel-row">
                    <?php
                        $field = '';
                        $field = FieldFactory::get(TextField::class);
                        $field->init([
                            'name' => 'linkedin-url',
                            'id' => 'linkedin-url',
                            'label' => 'Linkedin',
                            'class' => 'custom-file-input',
                            'required' => false,
                            'help_text' => 'Enter Linkedin Url',
                            'value' => $option_values['social_platform_settings']['social_platform_linkedin_url'] ?? '',
                            'attributes' => [
                                'data-preview-size' => '150'
                            ]
                        ])->render();
                    ?>
                </div>
                <div class="panel-row">
                    <?php
                        $field = '';
                        $field = FieldFactory::get(TextField::class);
                        $field->init([
                            'name' => 'twitter-url',
                            'id' => 'twitter-url',
                            'label' => 'Twitter',
                            'class' => 'custom-file-input',
                            'required' => false,
                            'help_text' => 'Enter Twitter Url',
                            'value' => $option_values['social_platform_settings']['social_platform_twitter_url'] ?? '',
                            'attributes' => [
                                'data-preview-size' => '150'
                            ]
                        ])->render();
                    ?>
                </div>
                <div class="panel-row">
                    <?php
                        $field = '';
                        $field = FieldFactory::get(TextField::class);
                        $field->init([
                            'name' => 'instagram-url',
                            'id' => 'instagram-url',
                            'label' => 'Instagram',
                            'class' => 'custom-file-input',
                            'required' => false,
                            'help_text' => 'Enter Instagram Url',
                            'value' => $option_values['social_platform_settings']['social_platform_instagram_url'] ?? '',
                            'attributes' => [
                                'data-preview-size' => '150'
                            ]
                        ])->render();
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    public function get_option_box_content_values(): array
    {
        return [
            'social_platform_settings' => [
                'social_platform_youtube_url' => $this->option_value['social_platform_settings']['social_platform_youtube_url'] ?? '',
                'social_platform_facebook_url' => $this->option_value['social_platform_settings']['social_platform_facebook_url'] ?? '',
                'social_platform_linkedin_url' => $this->option_value['social_platform_settings']['social_platform_linkedin_url'] ?? '',
                'social_platform_twitter_url' => $this->option_value['social_platform_settings']['social_platform_twitter_url'] ?? '',
                'social_platform_instagram_url' => $this->option_value['social_platform_settings']['social_platform_instagram_url'] ?? '',
            ]
        ];
    }

    public function get_option_value(): array
    {
        return get_option($this->option_name) ?: [];
    }

}
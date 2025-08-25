<?php
namespace Ababilithub\FlexMasterPro\Package\Plugin\OptionBoxContent\V1\Concrete\Section\ColorScheme;

use Ababilithub\{
    FlexWordpress\Package\Option\V1\Mixin\Option as OptionMixin,
    FlexWordpress\Package\OptionBoxContent\V1\Base\OptionBoxContent as BaseOptionBoxContent,
    FlexPhp\Package\Form\Field\V1\Factory\Field as FieldFactory,
    FlexPhp\Package\Form\Field\V1\Concrete\Select\Field as SelectField,
    FlexMasterPro\Package\Plugin\Posttype\V1\Concrete\ColorScheme\Posttype as ColorSchemePosttype,
    FlexMasterPro\Package\Plugin\OptionBox\V1\Concrete\VerticalTabBox\OptionBox as VerticalTabBoxOptionBox,
    
};

use const Ababilithub\{
    FlexMasterPro\PLUGIN_PRE_HYPH,
    FlexMasterPro\PLUGIN_PRE_UNDS,
    FlexMasterPro\PLUGIN_OPTION_NAME,
    FlexMasterPro\PLUGIN_OPTION_VALUE,
};

class OptionBoxContent extends BaseOptionBoxContent 
{
    use OptionMixin;
    
    public $option_name;
    public array $option_value = [];

    public function init(array $data = []): static
    {
        $this->tab_id = PLUGIN_PRE_HYPH.'-'.'vertical-tab-options';
        $this->tab_item_id = $this->tab_id.'_color_scheme_settings';
        $this->tab_item_label = esc_html__('Color Scheme');
        $this->tab_item_icon = 'fas fa-palette';
        $this->tab_item_status = 'not-active';
        $this->option_name = PLUGIN_OPTION_NAME ?? VerticalTabBoxOptionBox::OPTION_NAME;
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
        if (isset($_POST['color_scheme_id'])) 
        {
            $data['color_scheme_settings'] = [
                'selected_color_scheme_id' => absint($_POST['color_scheme_id']),
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
                <h2 class="panel-title">Color Scheme Settings</h2>
            </div>
            <div class="panel-body">
                <div class="panel-row">
                    <?php
                    $selectField = FieldFactory::get(SelectField::class);
                    $selectField->init([
                        'name' => 'color_scheme_id',
                        'id' => 'color_scheme_id',
                        'label' => 'Color Scheme',
                        'class' => 'custom-select-input',
                        'required' => false,
                        'help_text' => 'Select the color_scheme to show information in frontend',
                        'value' => $option_values['color_scheme_settings']['selected_color_scheme_id'] ?? null,
                        'options' => $option_values['color_scheme_settings']['color_scheme_list']??[],
                        'multiple' => false,
                        'searchable' => true,
                        'allowClear' => true,
                        'placeholder' => 'Select a Color Scheme',
                        'data' => [
                            'custom' => 'value'
                        ],
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
            'color_scheme_settings' => [
                'color_scheme_list' => $this->prepare_select_options(),
                'selected_color_scheme_id' => $this->option_value['color_scheme_settings']['selected_color_scheme_id'] ?? [],
                
            ]
        ];
    }

    public function get_option_value(): array
    {
        return PLUGIN_OPTION_VALUE ?? get_option($this->option_name) ?: [];
    }

    public function prepare_select_options(): array
    {
        $post_list = $this->get_query_posts();
        $options = [];
        
        foreach ($post_list as $post) 
        {
            $options[$post['id']] = $post['title'];
        }
        
        return $options;
    }

    public function get_query_posts(): array
    {
        $posts = get_posts([
            'post_type' => ColorSchemePosttype::POSTTYPE,
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ]);
        
        $formated_posts = [];
        
        foreach ($posts as $post) 
        {
            $formated_posts[] = [
                'id' => $post->ID,
                'title' => $post->post_title,
                'meta' => get_post_meta($post->ID) // Gets all meta as array
                // Or for specific meta fields:
                // 'meta' => [
                //     'address' => get_post_meta($color_scheme->ID, 'address', true),
                //     'phone' => get_post_meta($color_scheme->ID, 'phone', true),
                //     // Add other specific meta fields you need
                // ]
            ];
        }
        
        return $formated_posts;
    }

}
<?php
namespace Ababilithub\FlexMasterPro\Package\Plugin\OptionBoxContent\V1\Concrete\Section\CompanyInfo;

use Ababilithub\{
    FlexWordpress\Package\Option\V1\Mixin\Option as OptionMixin,
    FlexWordpress\Package\OptionBoxContent\V1\Base\OptionBoxContent as BaseOptionBoxContent,
    FlexPhp\Package\Form\Field\V1\Factory\Field as FieldFactory,
    FlexPhp\Package\Form\Field\V1\Concrete\Select\Field as SelectField,
    FlexMasterPro\Package\Plugin\Posttype\V1\Concrete\CompanyInfo\Posttype as CompanyInfoPosttype,
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
        $this->tab_item_id = $this->tab_id.'_company_settings';
        $this->tab_item_label = esc_html__('Company');
        $this->tab_item_icon = 'fas fa-home';
        $this->tab_item_status = 'active';
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

        // Register save handlers
        add_filter(PLUGIN_PRE_UNDS.'_prepare_save_data', [$this, 'prepare_save_data']);
        add_filter(PLUGIN_PRE_UNDS.'_before_option_update', [$this, 'validate_save_data']);
    }

    public function prepare_save_data(array $data): array
    {
        if (isset($_POST['company_id'])) 
        {
            $data['company_settings'] = [
                'selected_company_id' => absint($_POST['company_id']),
            ];
        }
        
        return $data;
    }

    public function validate_save_data(array $data): array
    {
        
        if (isset($data['company_settings']['selected_company_id'])) 
        {
            if (!is_numeric($data['company_settings']['selected_company_id'])) 
            {
                wp_die(__('Invalid company selection', 'text-domain'));
            }
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
                <h2 class="panel-title">Company Settings</h2>
            </div>
            <div class="panel-body">
                <div class="panel-row">
                    <?php
                    $selectField = FieldFactory::get(SelectField::class);
                    $selectField->init([
                        'name' => 'company_id',
                        'id' => 'company_id',
                        'label' => 'Company',
                        'class' => 'custom-select-input',
                        'required' => false,
                        'help_text' => 'Select the company to show information in frontend',
                        'value' => $option_values['company_settings']['selected_company_id'] ?? null,
                        'options' => $option_values['company_settings']['company_list']??[],
                        'multiple' => false,
                        'searchable' => true,
                        'allowClear' => true,
                        'placeholder' => 'Select a Company',
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
            'company_settings' => [
                'company_list' => $this->prepare_select_options(),
                'selected_company_id' => $this->option_value['company_settings']['selected_company_id'] ?? [],
                
            ]
        ];
    }

    public function get_option_value(): array
    {
        return get_option($this->option_name) ?: [];
    }

    public function prepare_select_options(): array
    {
        $company_list = $this->get_companies();
        $options = [];
        
        foreach ($company_list as $company) 
        {
            $options[$company['id']] = $company['title'];
        }
        
        return $options;
    }

    public function get_companies(): array
    {
        $companies = get_posts([
            'post_type' => CompanyInfoPosttype::POSTTYPE,
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ]);
        
        $formatted_companies = [];
        
        foreach ($companies as $company) 
        {
            $formatted_companies[] = [
                'id' => $company->ID,
                'title' => $company->post_title,
                'meta' => get_post_meta($company->ID) // Gets all meta as array
                // Or for specific meta fields:
                // 'meta' => [
                //     'address' => get_post_meta($company->ID, 'address', true),
                //     'phone' => get_post_meta($company->ID, 'phone', true),
                //     // Add other specific meta fields you need
                // ]
            ];
        }
        
        return $formatted_companies;
    }

}
<?php
namespace Ababilithub\FlexMasterPro\Package\Plugin\OptionBoxContent\V1\Concrete\Section\ImportantLink;

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
        $this->tab_item_id = $this->tab_id.'_settings-important-link';
        $this->tab_item_label = esc_html__('Important Link');
        $this->tab_item_icon = 'fas fa-bolt';
        $this->tab_item_status = 'not-active';
        $this->option_name = VerticalTabBoxOptionBox::OPTION_NAME;
        $this->option_value = $this->get_option_value();
        //echo "<pre>";print_r(PLUGIN_OPTION_VALUE);echo "</pre>";exit;
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
        if (isset($_POST['terms-and-conditions-url'])) 
        {
            $data['settings-important-link'] = [                
                'terms-and-conditions-url' => esc_url($_POST['terms-and-conditions-url']),
                'refund-policy-url' => esc_url($_POST['refund-policy-url']),
                'documentation-url' => esc_url($_POST['documentation-url']),
                'support-url' => esc_url($_POST['support-url']),
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
                <h2 class="panel-title">Important Link Settings</h2>
            </div>
            <div class="panel-body">
                <div class="panel-row">
                    <?php
                        $field = '';
                        $field = FieldFactory::get(TextField::class);
                        $field->init([
                            'name' => 'terms-and-conditions-url',
                            'id' => 'terms-and-conditions-url',
                            'label' => 'Terms and Conditions',
                            'class' => 'custom-file-input',
                            'required' => false,
                            'help_text' => 'Enter Terms and Conditions Url',
                            'value' => $option_values['settings-important-link']['terms-and-conditions-url'] ?? '',
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
                            'name' => 'refund-policy-url',
                            'id' => 'refund-policy-url',
                            'label' => 'Refund Policy',
                            'class' => 'custom-file-input',
                            'required' => false,
                            'help_text' => 'Enter Refund Policy Url',
                            'value' => $option_values['settings-important-link']['refund-policy-url'] ?? '',
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
                            'name' => 'documentation-url',
                            'id' => 'documentation-url',
                            'label' => 'Documentation',
                            'class' => 'custom-file-input',
                            'required' => false,
                            'help_text' => 'Enter Documentation Url',
                            'value' => $option_values['settings-important-link']['documentation-url'] ?? '',
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
                            'name' => 'support-url',
                            'id' => 'support-url',
                            'label' => 'Support',
                            'class' => 'custom-file-input',
                            'required' => false,
                            'help_text' => 'Enter Support Url',
                            'value' => $option_values['settings-important-link']['support-url'] ?? '',
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
            'settings-important-link' => [
                'terms-and-conditions-url' => $this->option_value['settings-important-link']['terms-and-conditions-url'] ?? '',
                'refund-policy-url' => $this->option_value['settings-important-link']['refund-policy-url'] ?? '',
                'documentation-url' => $this->option_value['settings-important-link']['documentation-url'] ?? '',
                'support-url' => $this->option_value['settings-important-link']['support-url'] ?? '',
            ]
        ];
    }

    public function get_option_value(): array
    {
        return PLUGIN_OPTION_VALUE ?? get_option($this->option_name) ?: [];
    }

}
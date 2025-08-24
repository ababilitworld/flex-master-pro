<?php
namespace Ababilithub\FlexMasterPro\Package\Plugin\Posttype\V1\Concrete\ColorScheme\PostMeta\PostMetaBoxContent\Concrete\Section\General;

use Ababilithub\{
    FlexMasterPro\Package\Plugin\Posttype\V1\Concrete\ColorScheme\POSTTYPE as ColorSchemePosttype,
    FlexWordpress\Package\PostMeta\V1\Mixin\PostMeta as PostMetaMixin,
    FlexWordpress\Package\PostMetaBoxContent\V1\Base\PostMetaBoxContent as BasePostMetaBoxContent,
    FlexPhp\Package\Form\Field\V1\Factory\Field as FieldFactory,
    FlexPhp\Package\Form\Field\V1\Concrete\Text\Field as TextField,
    FlexPhp\Package\Form\Field\V1\Concrete\Color\Field as ColorField,
    FlexPhp\Package\Form\Field\V1\Concrete\File\Document\Field as DocField,
    FlexPhp\Package\Form\Field\V1\Concrete\File\Image\Field as ImageField
};

use const Ababilithub\{
    FlexMasterPro\PLUGIN_PRE_HYPH,
    FlexMasterPro\PLUGIN_PRE_UNDS,
};

class PostMetaBoxContent extends BasePostMetaBoxContent
{
    use PostMetaMixin;
    public function init(array $data = []) : static
    {
        $this->posttype = ColorSchemePosttype::POSTTYPE;
        $this->post_id = get_the_ID();
        $this->tab_item_id = $this->posttype.'-'.'section-general';
        $this->tab_item_label = esc_html__('General Information');
        $this->tab_item_icon = 'fas fa-edit';
        $this->tab_item_status = 'active';

        $this->init_service();
        $this->init_hook();

        return $this;
    }

    public function init_service():void
    {

    }

    public function init_hook() : void
    {
        add_action(PLUGIN_PRE_UNDS.'_'.$this->posttype.'_'.'meta_box_tab_item',[$this,'tab_item']);
        add_action(PLUGIN_PRE_UNDS.'_'.$this->posttype.'_'.'meta_box_tab_content', [$this,'tab_content']);
    }

    public function render() : void
    {
        $meta_values = $this->get_meta_values(get_the_ID());
        //echo "<pre>";print_r($meta_values);echo "</pre>";exit;
        ?>
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">Color Scheme Details</h2>
                </div>
                <div class="panel-body">
                    <div class="panel-row  three-columns">
                        <?php
                            $customField = FieldFactory::get(ColorField::class);
                            $customField->init([
                                'name' => 'primary-color',
                                'id' => 'primary-color',
                                'label' => 'Primary Color',
                                'class' => 'custom-color-input',
                                'required' => true,
                                'help_text' => 'Enter primary color of the scheme',
                                'value' => $meta_values['primary-color'] ?? '',
                                'data' => [
                                    'custom' => 'value'
                                ],
                                'attributes' => [
                                    'data-preview-size' => '150'
                                ]
                            ])->render();
                        ?>
                        <?php
                            $customField = FieldFactory::get(ColorField::class);
                            $customField->init([
                                'name' => 'primary-dark-color',
                                'id' => 'primary-dark-color',
                                'label' => 'Primary Dark Color',
                                'class' => 'custom-color-input',
                                'required' => true,
                                'help_text' => 'Enter primary dark color of the scheme',
                                'value' => $meta_values['primary-dark-color'] ?? '',
                                'data' => [
                                    'custom' => 'value'
                                ],
                                'attributes' => [
                                    'data-preview-size' => '150'
                                ]
                            ])->render();
                        ?>
                        <?php
                            $customField = FieldFactory::get(ColorField::class);
                            $customField->init([
                                'name' => 'secondary-color',
                                'id' => 'secondary-color',
                                'label' => 'Secondary Color',
                                'class' => 'custom-color-input',
                                'required' => true,
                                'help_text' => 'Enter secondary color of the scheme',
                                'value' => $meta_values['secondary-color'] ?? '',
                                'data' => [
                                    'custom' => 'value'
                                ],
                                'attributes' => [
                                    'data-preview-size' => '150'
                                ]
                            ])->render();
                        ?>
                    </div>
                    <div class="panel-row">                        
                        
                        
                    </div>
                    <div class="panel-row two-columns">
                        <?php
                            $customField = FieldFactory::get(ColorField::class);
                            $customField->init([
                                'name' => 'text-color',
                                'id' => 'text-color',
                                'label' => 'Text Color',
                                'class' => 'custom-color-input',
                                'required' => true,
                                'help_text' => 'Enter text color of the scheme',
                                'value' => $meta_values['text-color'] ?? '',
                                'data' => [
                                    'custom' => 'value'
                                ],
                                'attributes' => [
                                    'data-preview-size' => '150'
                                ]
                            ])->render();
                        ?>

                        <?php
                            $customField = FieldFactory::get(ColorField::class);
                            $customField->init([
                                'name' => 'background-color',
                                'id' => 'background-color',
                                'label' => 'Background Color',
                                'class' => 'custom-color-input',
                                'required' => true,
                                'help_text' => 'Enter background color of the scheme',
                                'value' => $meta_values['background-color'] ?? '',
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

    public function get_meta_values($post_id): array 
    {
        return [
            'primary-color' => get_post_meta($post_id, 'primary-color', true) ?: '',
            'primary-dark-color' => get_post_meta($post_id, 'primary-dark-color', true) ?: '',
            'secondary-color' => get_post_meta($post_id, 'secondary-color', true) ?: '',
            'text-color' => get_post_meta($post_id, 'text-color', true) ?: '',
            'background-color' => get_post_meta($post_id, 'background-color', true) ?: '',
            'success-color' => get_post_meta($post_id, 'success-color', true) ?: ''
        ];
    }

    public function save($post_id, $post, $update): void 
    {
        if (!$this->is_valid_save($post_id, $post)) 
        {
            return;
        }

        // Save color fields
        $this->save_text_field($post_id, 'primary-color', sanitize_text_field($_POST['primary-color'] ?? ''));
        $this->save_text_field($post_id, 'primary-dark-color', sanitize_text_field($_POST['primary-dark-color'] ?? ''));
        $this->save_text_field($post_id, 'secondary-color', sanitize_text_field($_POST['secondary-color'] ?? ''));
        $this->save_text_field($post_id, 'text-color', sanitize_text_field($_POST['text-color'] ?? ''));
        $this->save_text_field($post_id, 'background-color', sanitize_text_field($_POST['background-color'] ?? ''));
        $this->save_text_field($post_id, 'success-color', sanitize_text_field($_POST['success-color'] ?? ''));
    }

}
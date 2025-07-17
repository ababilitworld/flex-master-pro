<?php
namespace Ababilithub\FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Deed\PostMeta\PostMetaBoxContent\Concrete\GeneralSettings;

use Ababilithub\{
    FlexWordpress\Package\PostMeta\V1\Mixin\PostMeta as PostMetaMixin,
    FlexWordpress\Package\PostMetaBoxContent\V1\Base\PostMetaBoxContent as BasePostMetaBoxContent,
    FlexPhp\Package\Form\Field\V1\Factory\Field as FieldFactory,
    FlexPhp\Package\Form\Field\V1\Concrete\Text\Field as TextField,
    FlexPhp\Package\Form\Field\V1\Concrete\File\Document\Field as DocField,
    FlexPhp\Package\Form\Field\V1\Concrete\File\Image\Field as ImageField
};

use const Ababilithub\{
    FlexELand\PLUGIN_PRE_HYPH,
    FlexELand\PLUGIN_PRE_UNDS,
    FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Deed\POSTTYPE,
};

class PostMetaBoxContent extends BasePostMetaBoxContent
{
    use PostMetaMixin;
    public function init(array $data = []) : static
    {
        $this->posttype = POSTTYPE;
        $this->post_id = get_the_ID();
        $this->tab_item_id = $this->posttype.'-'.'general-settings';
        $this->tab_item_label = esc_html__('General Settings');
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
        //add_action('save_post', [$this, 'save'], 10, 3);
    }

    public function render() : void
    {
        $meta_values = $this->get_meta_values(get_the_ID());
        //echo "<pre>";print_r($meta_values);echo "</pre>";exit;
        ?>
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">Deed Details</h2>
                </div>
                <div class="panel-body">
                    <div class="panel-row">
                        <?php
                            $deedDateField = FieldFactory::get(TextField::class);
                            $deedDateField->init([
                                'name' => 'deed-date',
                                'id' => 'deed-date',
                                'label' => 'Deed Date',
                                'class' => 'custom-file-input',
                                'required' => true,
                                'help_text' => 'Enter Deed Date used in the Deed',
                                'value' => $meta_values['deed_date'],
                                'data' => [
                                    'custom' => 'value'
                                ],
                                'attributes' => [
                                    'data-preview-size' => '150'
                                ]
                            ])->render();
                        ?>
                    </div>
                    <div class="panel-row two-columns">
                        <?php
                            $deedNumberField = FieldFactory::get(TextField::class);
                            $deedNumberField->init([
                                'name' => 'deed-number',
                                'id' => 'deed-number',
                                'label' => 'Deed Number',
                                'class' => 'custom-file-input',
                                'required' => true,
                                'help_text' => 'Enter Deed number of the deed',
                                'value' => $meta_values['deed_number'],
                                'data' => [
                                    'custom' => 'value'
                                ],
                                'attributes' => [
                                    'data-preview-size' => '150'
                                ]
                            ])->render();
                        ?>
                        <?php
                            $plotNumberField = FieldFactory::get(TextField::class);
                            $plotNumberField->init([
                                'name' => 'plot-number',
                                'id' => 'plot-number',
                                'label' => 'Plot Number',
                                'class' => 'custom-file-input',
                                'required' => true,
                                'help_text' => 'Enter Plot number according to the Respective Survey',
                                'value' => $meta_values['plot_number'],
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
                        <?php
                            $landQuantityField = FieldFactory::get(TextField::class);
                            $landQuantityField->init([
                                'name' => 'land-quantity',
                                'id' => 'land-quantity',
                                'label' => 'Land Quantity (Decimal)',
                                'class' => 'custom-file-input',
                                'required' => true,
                                'help_text' => 'Enter Land Quantity in decimal used in the Deed',
                                'value' => $meta_values['land_quantity'],
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
            
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">Deed Images</h2>
                </div>
                <div class="panel-body">
                    <div class="panel-row">
                        <?php
                            $imageField = FieldFactory::get(ImageField::class);
                            $imageField->init([
                                'name' => 'deed-thumbnail-image',
                                'id' => 'deed-thumbnail-image',
                                'label' => 'Deed Thumbnail',
                                'class' => 'custom-file-input',
                                'required' => true,
                                'multiple' => false,
                                'allowed_types' => ['.jpg', '.jpeg', '.png', '.gif', '.webp'],
                                'max_size' => 2097152, // 2MB
                                'enable_media_library' => true,
                                'upload_action_text' => 'Select Image',
                                'help_text' => 'Only jpg, png, gif, webp files are allowed',
                                'preview_items' => $meta_values['deed_thumbnail_image'],
                                'data' => [
                                    'custom' => 'value'
                                ],
                                'attributes' => [
                                    'data-preview-size' => '150'
                                ]
                            ])->render();
                        ?>
                        <?php
                            $imageField = FieldFactory::get(ImageField::class);
                            $imageField->init([
                                'name' => 'deed-images',
                                'id' => 'deed-images',
                                'label' => 'Deed Images',
                                'class' => 'custom-file-input',
                                'required' => true,
                                'multiple' => true,
                                'allowed_types' => ['.jpg', '.jpeg', '.png', '.gif', '.webp'],
                                'max_size' => 2097152, // 2MB
                                'enable_media_library' => true,
                                'upload_action_text' => 'Select Images',
                                'help_text' => 'Only jpg, png, gif, webp files are allowed',
                                'preview_items' => $meta_values['deed_images'],
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

            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">Deed Attachments</h2>
                </div>
                <div class="panel-body">
                    <div class="panel-row">
                        <?php
                            $deedPdfField = FieldFactory::get(DocField::class);
                            $deedPdfField->init([
                                'name' => 'deed-attachments',
                                'id' => 'deed-attachments',
                                'label' => 'Deed Attachments',
                                'class' => 'custom-file-input',
                                'required' => true,
                                'multiple' => true,
                                'allowed_types' => ['.pdf', '.doc', '.docx', '.xls', '.xlsx'],
                                'upload_action_text' => 'Select Attachments',
                                'help_text' => 'Only PDF, Word, and Excel files are allowed',
                                'max_size' => 5242880, // 5MB
                                'enable_media_library' => true,
                                'preview_items' => $meta_values['deed_attachments'],
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
            'deed_date' => get_post_meta($post_id, 'deed-date', true) ?: '',
            'deed_number' => get_post_meta($post_id, 'deed-number', true) ?: '',
            'plot_number' => get_post_meta($post_id, 'plot-number', true) ?: '',
            'land_quantity' => get_post_meta($post_id, 'land-quantity', true) ?: '',
            'deed_thumbnail_image' => get_post_meta($post_id, 'deed-thumbnail-image', true) ?: '',
            'deed_images' => get_post_meta($post_id, 'deed-images', true) ?: [],
            'deed_attachments' => get_post_meta($post_id, 'deed-attachments', true) ?: []
        ];
    }

    public function save($post_id, $post, $update) : void 
    {
        if (!$this->is_valid_save($post_id, $post)) 
        {
            return;
        }

        $this->save_text_field($post_id,'deed-date',sanitize_text_field($_POST['deed-date']));
        $this->save_text_field($post_id,'deed-number',sanitize_text_field($_POST['deed-number']));
        $this->save_text_field($post_id,'plot-number',sanitize_text_field($_POST['plot-number']));
        $this->save_text_field($post_id,'land-quantity',sanitize_text_field($_POST['land-quantity']));
        $this->save_thumbnail_image($post_id,'deed-thumbnail-image',absint($_POST['deed-thumbnail-image']));
        $this->save_multiple_images($post_id,'deed-images',array_map('sanitize_text_field',$_POST['deed-images']));
        $this->save_multiple_attachments($post_id,'deed-attachments',array_map('sanitize_text_field',$_POST['deed-attachments']));
    }
}
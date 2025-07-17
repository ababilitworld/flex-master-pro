<?php
namespace Ababilithub\FlexELand\Package\Plugin\Posttype\V1\Concrete\Website\Setting\ContactInfo\Setting\General;

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexPhp\Package\Form\Field\V1\Factory\Field as FieldFactory,
    FlexPhp\Package\Form\Field\V1\Concrete\Text\Field as TextField,
    FlexPhp\Package\Form\Field\V1\Concrete\File\Document\Field as DocField,
    FlexPhp\Package\Form\Field\V1\Concrete\File\Image\Field as ImageField
};

use const Ababilithub\{
    FlexELand\PLUGIN_NAME,
    FlexELand\PLUGIN_DIR,
    FlexELand\PLUGIN_URL,
    FlexELand\PLUGIN_FILE,
    FlexELand\PLUGIN_PRE_UNDS,
    FlexELand\PLUGIN_PRE_HYPH,
    FlexELand\PLUGIN_VERSION,
    FlexELand\Package\Plugin\Posttype\V1\Concrete\Website\Setting\ContactInfo\POSTTYPE,
};

(defined('ABSPATH') && defined('WPINC')) || exit();

if (!class_exists(__NAMESPACE__.'\Setting')) 
{
    class Setting 
    {
        use StandardMixin;

        private $posttype;

        public function __construct() 
        {
            $this->posttype = POSTTYPE;
            $this->init();
        }



        private function init() 
        {
            add_action('admin_enqueue_scripts', array($this, 'enqueue'));
            add_action(PLUGIN_PRE_UNDS.'_'.POSTTYPE.'_'.'setting_tab_item', array($this, 'tab_item'));
            add_action(PLUGIN_PRE_UNDS.'_'.POSTTYPE.'_'.'setting_tab_content', array($this, 'tab_content'));
            add_action(PLUGIN_PRE_UNDS.'_'.POSTTYPE.'_'.'setting_general_info', array($this, 'general_info'));
            add_action('save_post', array($this, 'save'));
        }

        public function enqueue() 
        {
            wp_enqueue_media();
            wp_enqueue_script(
                PLUGIN_PRE_HYPH.'-deed-upload',
                PLUGIN_URL . '/assets/js/deed-upload.js',
                ['jquery'],
                PLUGIN_VERSION,
                true
            );
        }

        public function tab_item() 
        {
            ?>
            <li class="tab-item active" data-tab="deed-general-settings">
                <span href="#" class="tab-link">
                    <i class="fas fa-home"></i>
                    <span class="tab-text">General Settings</span>
                </span>
            </li>
            <?php
        }

        public function tab_content($post_id) 
        {
            ?>
            <div class="tab-content active" id="deed-general-settings">
                <h3>General Settings</h3>
                <?php 
                                
                // echo '<h4>All Meta</h4>';
                // echo '<pre>'; print_r(get_post_meta($post_id)); echo '</pre>';

                $this->general_info($post_id); ?>
            </div>
            <?php
        }

        public function general_info($post_id) 
        {
            $phone_number = get_post_meta($post_id, 'phone-number', true) ?: '';
            $email = get_post_meta($post_id, 'email', true) ?: '';            
            $address = get_post_meta($post_id, 'address', true) ?: '';
            
            $deed_thumbnail_image = get_post_meta($post_id, 'company-logo', true) ?: '';
            
            $images = get_post_meta($post_id, 'deed-images', true) ?: [];
            $docs = get_post_meta($post_id, 'deed-docs', true) ?: [];
            
            wp_nonce_field('deed_deeds_nonce', 'deed_deeds_nonce');
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
                                'name' => 'phone-number',
                                'id' => 'phone-number',
                                'label' => 'Phone Number',
                                'class' => 'custom-file-input',
                                'required' => true,
                                'help_text' => 'Enter Phone Number used in the Deed',
                                'value' => $phone_number,
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
                            $deedNumberField = FieldFactory::get(TextField::class);
                            $deedNumberField->init([
                                'name' => 'email',
                                'id' => 'email',
                                'label' => 'Email',
                                'class' => 'custom-file-input',
                                'required' => true,
                                'help_text' => 'Enter Email',
                                'value' => $email,
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
                                'name' => 'address',
                                'id' => 'address',
                                'label' => 'Address',
                                'class' => 'custom-file-input',
                                'required' => true,
                                'help_text' => 'Enter Land Quantity in decimal used in the Deed',
                                'value' => $address,
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
                                'name' => 'company-logo',
                                'id' => 'company-logo',
                                'label' => 'Website Logo',
                                'class' => 'custom-file-input',
                                'required' => true,
                                'multiple' => false,
                                'allowed_types' => ['.jpg', '.jpeg', '.png', '.gif', '.webp'],
                                'max_size' => 2097152, // 2MB
                                'enable_media_library' => true,
                                'upload_action_text' => 'Select Images',
                                'help_text' => 'Only jpg, png, gif, webp files are allowed',
                                'preview_items' => $deed_thumbnail_image,
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
                                'preview_items' => $images,
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
                    <h2 class="panel-title">Deed Documents</h2>
                </div>
                <div class="panel-body">
                    <div class="panel-row">
                        <?php
                            $deedPdfField = FieldFactory::get(DocField::class);
                            $deedPdfField->init([
                                'name' => 'deed-docs',
                                'id' => 'deed-docs',
                                'label' => 'Deed Documents',
                                'class' => 'custom-file-input',
                                'required' => true,
                                'multiple' => true,
                                'allowed_types' => ['.pdf', '.doc', '.docx', '.xls', '.xlsx'],
                                'upload_action_text' => 'Select Documents',
                                'help_text' => 'Only PDF, Word, and Excel files are allowed',
                                'max_size' => 5242880, // 5MB
                                'enable_media_library' => true,
                                'preview_items' => $docs,
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

        public function save($post_id) 
        {
            // DEBUG OUTPUT
            // update_post_meta($post_id, 'deed-images', [481,551]);
            // update_post_meta($post_id, 'deed-docs', [481,551]);

            //Verify nonce
            if (!isset($_POST['deed_deeds_nonce']) || 
                !wp_verify_nonce($_POST['deed_deeds_nonce'], 'deed_deeds_nonce')) {
                return;
            }

            // Check user permissions
            if (!current_user_can('edit_post', $post_id)) 
            {
                return;
            }

            // Only save for our post type
            if (get_post_type($post_id) != POSTTYPE) 
            {
                return;
            }

            // Save plot number
            if (isset($_POST['phone-number'])) 
            {
                $address = sanitize_text_field($_POST['phone-number']);                
                update_post_meta($post_id, 'phone-number', $address);
            } 
            else 
            {
                delete_post_meta($post_id, 'phone-number');
            }

            // Save deeed number
            if (isset($_POST['email'])) 
            {
                $address = sanitize_text_field($_POST['email']);                
                update_post_meta($post_id, 'email', $address);
            } 
            else 
            {
                delete_post_meta($post_id, 'email');
            }

            // Save plot number
            if (isset($_POST['address'])) 
            {
                $address = sanitize_text_field($_POST['address']);                
                update_post_meta($post_id, 'address', $address);
            } 
            else 
            {
                delete_post_meta($post_id, 'address');
            }

            // Save quantity
            if (isset($_POST['address'])) 
            {
                $quantity = sanitize_text_field($_POST['address']);                
                update_post_meta($post_id, 'address', $quantity);
            } 
            else 
            {
                delete_post_meta($post_id, 'address');
            }

            // Save thumbnail image
            if (isset($_POST['company-logo']) && !empty($_POST['company-logo'])) 
            {
                // Ensure we're working with a single image ID (not an array)
                $image_id = absint($_POST['company-logo']);
                
                // Verify the attachment exists and is an image
                if ($image_id && wp_attachment_is_image($image_id)) 
                {
                    // Set as featured image (post thumbnail)
                    set_post_thumbnail($post_id, $image_id);
                    
                    // Also save in custom meta if needed
                    update_post_meta($post_id, 'company-logo', $image_id);
                } 
                else 
                {
                    // Invalid image - remove both featured image and custom meta
                    delete_post_thumbnail($post_id);
                    delete_post_meta($post_id, 'company-logo');
                }
            } 
            else 
            {
                // No image submitted - remove both featured image and custom meta
                delete_post_thumbnail($post_id);
                delete_post_meta($post_id, 'company-logo');
            }

            // Save images
            if (isset($_POST['deed-images'])) 
            {
                $images = array_map('absint', $_POST['deed-images']);
                $valid_images = [];
                foreach ((array)$images as $image_id) 
                {
                    if (wp_attachment_is_image($image_id)) 
                    {
                        $valid_images[] = $image_id;
                    }
                }
                update_post_meta($post_id, 'deed-images', $valid_images);
            } 
            else
            {
                delete_post_meta($post_id, 'deed-images');
            }

            // Save deeds - CORRECTED VERSION
            if (isset($_POST['deed-docs'])) 
            {
                $docs = array_map('absint', $_POST['deed-docs']);
                $valid_docs = [];
                foreach ((array)$docs as $doc_id) 
                {
                    // Check if attachment exists (different approach than images)
                    if (get_post($doc_id) && get_post_type($doc_id) === 'attachment') 
                    {
                        $valid_docs[] = $doc_id;
                    }
                }
                update_post_meta($post_id, 'deed-docs', $valid_docs);
            } 
            else 
            {
                delete_post_meta($post_id, 'deed-docs');
            }
        }

    }
}
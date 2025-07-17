<?php
namespace Ababilithub\FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Document\Setting\General;

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexPhp\Package\Form\Field\V1\Factory\Field as FieldFactory,
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
    FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Document\POSTTYPE,
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
                PLUGIN_PRE_HYPH.'-document-upload',
                PLUGIN_URL . '/assets/js/document-upload.js',
                ['jquery'],
                PLUGIN_VERSION,
                true
            );
        }

        public function tab_item() 
        {
            ?>
            <li class="tab-item active" data-tab="document-general-settings">
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
            <div class="tab-content active" id="document-general-settings">
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
            $images = get_post_meta($post_id, 'document-images', true) ?: [];
            $docs = get_post_meta($post_id, 'document-docs', true) ?: [];
            
            wp_nonce_field('document_documents_nonce', 'document_documents_nonce');
            ?>
            
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">Document Images</h2>
                </div>
                <div class="panel-body">
                    <div class="panel-row">
                        <?php
                            $imageField = FieldFactory::get(ImageField::class);
                            $imageField->init([
                                'name' => 'document-images',
                                'id' => 'document-images',
                                'label' => 'Document Images',
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
                    <h2 class="panel-title">Document Documents</h2>
                </div>
                <div class="panel-body">
                    <div class="panel-row">
                        <?php
                            $documentPdfField = FieldFactory::get(DocField::class);
                            $documentPdfField->init([
                                'name' => 'document-docs',
                                'id' => 'document-docs',
                                'label' => 'Document Documents',
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
            // update_post_meta($post_id, 'document-images', [481,551]);
            // update_post_meta($post_id, 'document-docs', [481,551]);

            //Verify nonce
            if (!isset($_POST['document_documents_nonce']) || 
                !wp_verify_nonce($_POST['document_documents_nonce'], 'document_documents_nonce')) {
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

            // Save images
            if (isset($_POST['document-images'])) 
            {
                $images = array_map('absint', $_POST['document-images']);
                $valid_images = [];
                foreach ((array)$images as $image_id) 
                {
                    if (wp_attachment_is_image($image_id)) 
                    {
                        $valid_images[] = $image_id;
                    }
                }
                update_post_meta($post_id, 'document-images', $valid_images);
            } 
            else
            {
                delete_post_meta($post_id, 'document-images');
            }

            // Save documents - CORRECTED VERSION
            if (isset($_POST['document-docs'])) 
            {
                $docs = array_map('absint', $_POST['document-docs']);
                $valid_docs = [];
                foreach ((array)$docs as $doc_id) 
                {
                    // Check if attachment exists (different approach than images)
                    if (get_post($doc_id) && get_post_type($doc_id) === 'attachment') 
                    {
                        $valid_docs[] = $doc_id;
                    }
                }
                update_post_meta($post_id, 'document-docs', $valid_docs);
            } 
            else 
            {
                delete_post_meta($post_id, 'document-docs');
            }
        }

    }
}
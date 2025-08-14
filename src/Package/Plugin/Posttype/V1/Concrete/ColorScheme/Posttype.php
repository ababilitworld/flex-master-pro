<?php
namespace Ababilithub\FlexMasterPro\Package\Plugin\Posttype\V1\Concrete\ColorScheme;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Posttype\V1\Mixin\Posttype as WpPosttypeMixin,
    FlexWordpress\Package\Posttype\V1\Base\Posttype as BasePosttype,
    FlexMasterPro\Package\Plugin\Posttype\V1\Concrete\ColorScheme\Presentation\Template\Single\Template as PosttypeTemplate,
    FlexMasterPro\Package\Plugin\Posttype\V1\Concrete\ColorScheme\PostMeta\PostMetaBox\Manager\PostMetaBox as ColorSchemePostMetaBoxManager,
    FlexMasterPro\Package\Plugin\Posttype\V1\Concrete\ColorScheme\PostMeta\PostMetaBoxContent\Manager\PostMetaBoxContent as ColorSchemePostMetaBoxContentManager,
    
};

use const Ababilithub\{
    FlexMasterPro\PLUGIN_PRE_UNDS,
    FlexMasterPro\PLUGIN_DIR,
};

class Posttype extends BasePosttype 
{ 
    use WpPosttypeMixin;

    public const POSTTYPE = 'flcscem';

    private $template_service;
    
    public function init() : void
    {
        $this->posttype = self::POSTTYPE;
        $this->slug = self::POSTTYPE;

        $this->set_labels([
            'name' => esc_html__('Color Schemes', 'flex-master-pro'),
            'singular_name' => esc_html__('Color Scheme', 'flex-master-pro'),
            'menu_name' => esc_html__('Color Schemes', 'flex-master-pro'),
            'name_admin_bar' => esc_html__('Color Schemes', 'flex-master-pro'),
            'archives' => esc_html__('Color Scheme List', 'flex-master-pro'),
            'attributes' => esc_html__('Color Scheme List', 'flex-master-pro'),
            'parent_item_colon' => esc_html__('Color Scheme Item : ', 'flex-master-pro'),
            'all_items' => esc_html__('All Color Scheme', 'flex-master-pro'),
            'add_new_item' => esc_html__('Add new Color Scheme', 'flex-master-pro'),
            'add_new' => esc_html__('Add new Color Scheme', 'flex-master-pro'),
            'new_item' => esc_html__('New Color Scheme', 'flex-master-pro'),
            'edit_item' => esc_html__('Edit Color Scheme', 'flex-master-pro'),
            'update_item' => esc_html__('Update Color Scheme', 'flex-master-pro'),
            'view_item' => esc_html__('View Color Scheme', 'flex-master-pro'),
            'view_items' => esc_html__('View Color Schemes', 'flex-master-pro'),
            'search_items' => esc_html__('Search Color Schemes', 'flex-master-pro'),
            'not_found' => esc_html__('Color Scheme Not found', 'flex-master-pro'),
            'not_found_in_trash' => esc_html__('Color Scheme Not found in Trash', 'flex-master-pro'),
            'featured_image' => esc_html__('Color Scheme Feature Image', 'flex-master-pro'),
            'set_featured_image' => esc_html__('Set Color Scheme Feature Image', 'flex-master-pro'),
            'remove_featured_image' => esc_html__('Remove Feature Image', 'flex-master-pro'),
            'use_featured_image' => esc_html__('Use as Color Scheme featured image', 'flex-master-pro'),
            'insert_into_item' => esc_html__('Insert into Color Scheme', 'flex-master-pro'),
            'uploaded_to_this_item' => esc_html__('Uploaded to this ', 'flex-master-pro'),
            'items_list' => esc_html__('Color Scheme list', 'flex-master-pro'),
            'items_list_navigation' => esc_html__('Color Scheme list navigation', 'flex-master-pro'),
            'filter_items_list' => esc_html__('Filter Color Scheme List', 'flex-master-pro')
        ]);

        $this->set_posttype_supports(
            array('title', 'thumbnail', 'editor')
        );

        $this->set_taxonomies([]);

        $this->set_args([
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_in_menu' => false, // Don't show in menu by default
            'labels' => $this->labels,
            'menu_icon' => "dashicons-admin-post",
            'rewrite' => array('slug' => $this->slug,'with_front' => false),
            'has_archive' => true,        // If you want archive pages
            'supports' => $this->posttype_supports,
            'taxonomies' => $this->taxonomies,
        ]);

        $this->init_service();
        $this->init_hook();

    }

    public function init_service(): void
    {
       $this->template_service = new PosttypeTemplate();
    }

    public function init_hook(): void
    {
        add_action('after_setup_theme', [$this, 'init_theme_supports'],0);

        add_action('add_meta_boxes', function () {
            (new ColorSchemePostMetaBoxManager())->boot();
        });

        add_action('add_meta_boxes', function () {
            (new ColorSchemePostMetaBoxContentManager())->boot();
        });

        add_action('save_post', function ($post_id, $post, $update) {
            (new ColorSchemePostMetaBoxContentManager())->save_post($post_id, $post, $update);
        }, 10, 3);

        add_filter('the_content', [$this, 'single_post']);
        
        add_filter('post_row_actions', [$this, 'add_action_view_details'], 10, 2);
        add_filter('page_row_actions', [$this, 'add_action_view_details'], 10, 2);


    }

    public function init_theme_supports()
    {
        add_theme_support('post-thumbnails', [$this->posttype]);
        add_theme_support('editor-color-palette', [
            [
                'name'  => 'Primary Blue',
                'slug'  => 'primary-blue',
                'color' => '#3366FF',
            ],
        ]);
        add_theme_support('align-wide');
        add_theme_support('responsive-embeds');
    }

    public function single_post($content)
    {
        // Only modify content on single post pages of specific post types
        if (!is_singular() || !in_the_loop() || !is_main_query()) {
            return $content;
        }

        global $post;
        
        // First check if $post exists and is a valid post object
        if (!$post || !is_object($post)) {
            return $content;
        }
        
        // Then check the post type
        if ($post->post_type !== self::POSTTYPE) {
            return $content;
        }
        
        // Prevent infinite recursion
        remove_filter('the_content', [$this, 'single_post']);
        
        // Get template content - use the class directly like in LandDeed version
        $template_content = $this->template_service->single_post($post);
        
        // Re-add our filter
        add_filter('the_content', [$this, 'single_post']);
        
        // Combine with original content
        return $template_content;
    }

}
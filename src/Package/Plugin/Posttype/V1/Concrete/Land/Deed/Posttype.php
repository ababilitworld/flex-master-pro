<?php
namespace Ababilithub\FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Deed;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Posttype\V1\Mixin\Posttype as WpPosttypeMixin,
    FlexWordpress\Package\Posttype\V1\Base\Posttype as BasePosttype,
    FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Deed\Presentation\Template\List\PremiumCard\Template as PosttypeListTemplate,
    FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Deed\Presentation\Template\Single\Template as PosttypeTemplate,
    FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Deed\Setting\Setting as PosttypeSetting,
    FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Deed\PostMeta\PostMetaBox\Manager\PostMetaBox as LandDeedPostMetaBoxManager,
    FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Deed\PostMeta\PostMetaBoxContent\Manager\PostMetaBoxContent as LandDeedPostMetaBoxContentManager,
    
};

use const Ababilithub\{
    FlexELand\PLUGIN_PRE_UNDS,
    FlexELand\PLUGIN_DIR,
};

defined( __NAMESPACE__.'\POSTTYPE' ) || define( __NAMESPACE__.'\POSTTYPE', 'fldeed' );

class Posttype extends BasePosttype 
{ 
    use WpPosttypeMixin;

    private $template_service;

    private $meta_box_manager;
    private $meta_box_content_manager;
    
    public function init() : void
    {
        $this->posttype = POSTTYPE;
        $this->slug = POSTTYPE;

        $this->set_labels([
            'name' => esc_html__('Land Deeds', 'flex-eland'),
            'singular_name' => esc_html__('Land Deed', 'flex-eland'),
            'menu_name' => esc_html__('Land Deeds', 'flex-eland'),
            'name_admin_bar' => esc_html__('Land Deeds', 'flex-eland'),
            'archives' => esc_html__('Land Deed List', 'flex-eland'),
            'attributes' => esc_html__('Land Deed List', 'flex-eland'),
            'parent_item_colon' => esc_html__('Land Deed Item : ', 'flex-eland'),
            'all_items' => esc_html__('All Land Deed', 'flex-eland'),
            'add_new_item' => esc_html__('Add new Land Deed', 'flex-eland'),
            'add_new' => esc_html__('Add new Land Deed', 'flex-eland'),
            'new_item' => esc_html__('New Land Deed', 'flex-eland'),
            'edit_item' => esc_html__('Edit Land Deed', 'flex-eland'),
            'update_item' => esc_html__('Update Land Deed', 'flex-eland'),
            'view_item' => esc_html__('View Land Deed', 'flex-eland'),
            'view_items' => esc_html__('View Land Deeds', 'flex-eland'),
            'search_items' => esc_html__('Search Land Deeds', 'flex-eland'),
            'not_found' => esc_html__('Land Deed Not found', 'flex-eland'),
            'not_found_in_trash' => esc_html__('Land Deed Not found in Trash', 'flex-eland'),
            'featured_image' => esc_html__('Land Deed Feature Image', 'flex-eland'),
            'set_featured_image' => esc_html__('Set Land Deed Feature Image', 'flex-eland'),
            'remove_featured_image' => esc_html__('Remove Feature Image', 'flex-eland'),
            'use_featured_image' => esc_html__('Use as Land Deed featured image', 'flex-eland'),
            'insert_into_item' => esc_html__('Insert into Land Deed', 'flex-eland'),
            'uploaded_to_this_item' => esc_html__('Uploaded to this ', 'flex-eland'),
            'items_list' => esc_html__('Land Deed list', 'flex-eland'),
            'items_list_navigation' => esc_html__('Land Deed list navigation', 'flex-eland'),
            'filter_items_list' => esc_html__('Filter Land Deed List', 'flex-eland')
        ]);

        $this->set_posttype_supports(
            array('title', 'thumbnail', 'editor')
        );

        $this->set_taxonomies(
            array('district','thana','land-mouza','land-survey','land-deed-type','land-type')
        );

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
       //$this->meta_service = new PosttypeSetting();
       $this->template_service = new PosttypeTemplate();
    //    $this->meta_box_manager = new LandDeedPostMetaBoxManager();
    //    $this->meta_box_content_manager = new LandDeedPostMetaBoxContentManager();
    }

    public function init_hook(): void
    {
        add_action('after_setup_theme', [$this, 'init_theme_supports'],0);

        add_action('add_meta_boxes', function () {
            (new LandDeedPostMetaBoxManager())->boot();
        });

        add_action('add_meta_boxes', function () {
            (new LandDeedPostMetaBoxContentManager())->boot();
        });

        add_action('save_post', function ($post_id, $post, $update) {
            (new LandDeedPostMetaBoxContentManager())->save_post($post_id, $post, $update);
        }, 10, 3);

        add_filter(PLUGIN_PRE_UNDS.'_admin_menu', [$this, 'add_menu_items']);
        add_filter('the_content', [$this, 'single_post']);
        
        add_filter('post_row_actions', [$this, 'add_action_view_details'], 10, 2);
        add_filter('page_row_actions', [$this, 'add_action_view_details'], 10, 2);


    }

    public function init_theme_supports()
    {
        add_theme_support('post-thumbnails', [POSTTYPE]);
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

    public function add_menu_items($menu_items = [])
    {
        $menu_items[] = [
            'type' => 'submenu',
            'parent_slug' => 'flex-eland',
            'page_title' => __('Land Deed', 'flex-eland'),
            'menu_title' => __('Land Deed', 'flex-eland'),
            'capability' => 'manage_options',
            'menu_slug' => 'edit.php?post_type='.POSTTYPE,
            'callback' => null,
            'position' => 8,
        ];

        return $menu_items;
    }

    public function single_post($content)
    {
        // Only modify content on single post pages of specific post types
        if (!is_singular() || !in_the_loop() || !is_main_query()) 
        {
            return $content;
        }

        global $post;
        
        if ($post->post_type !== POSTTYPE) 
        {
            return $content;
        }

        // Prevent infinite recursion
        remove_filter('the_content', [$this, 'single_post']);
        
        // Get template content
        $template_content = PosttypeTemplate::single_post($post);
        
        // Re-add our filter
        add_filter('the_content', [$this, 'single_post']);
        
        // Combine with original content
        return $template_content;
    }

}
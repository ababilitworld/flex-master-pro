<?php
namespace Ababilithub\FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Document;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Posttype\V1\Mixin\Posttype as WpPosttypeMixin,
    FlexWordpress\Package\Posttype\V1\Base\Posttype as BasePosttype,
    FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Document\Setting\Setting as PosttypeSetting

};

use const Ababilithub\{
    FlexELand\PLUGIN_PRE_UNDS,
    FlexELand\PLUGIN_DIR,
};

defined( __NAMESPACE__.'\POSTTYPE' ) || define( __NAMESPACE__.'\POSTTYPE', 'fldoc' );

class Posttype extends BasePosttype 
{ 
    use WpPosttypeMixin;
    
    public function init() : void
    {
        $this->posttype = POSTTYPE;
        $this->slug = POSTTYPE;

        $this->set_labels([
            'name' => esc_html__('Land Docs', 'flex-eland'),
            'singular_name' => esc_html__('Land Doc', 'flex-eland'),
            'menu_name' => esc_html__('Land Docs', 'flex-eland'),
            'name_admin_bar' => esc_html__('Land Docs', 'flex-eland'),
            'archives' => esc_html__('Land Doc List', 'flex-eland'),
            'attributes' => esc_html__('Land Doc List', 'flex-eland'),
            'parent_item_colon' => esc_html__('Land Doc Item : ', 'flex-eland'),
            'all_items' => esc_html__('All Land Doc', 'flex-eland'),
            'add_new_item' => esc_html__('Add new Land Doc', 'flex-eland'),
            'add_new' => esc_html__('Add new Land Doc', 'flex-eland'),
            'new_item' => esc_html__('New Land Doc', 'flex-eland'),
            'edit_item' => esc_html__('Edit Land Doc', 'flex-eland'),
            'update_item' => esc_html__('Update Land Doc', 'flex-eland'),
            'view_item' => esc_html__('View Land Doc', 'flex-eland'),
            'view_items' => esc_html__('View Land Docs', 'flex-eland'),
            'search_items' => esc_html__('Search Land Docs', 'flex-eland'),
            'not_found' => esc_html__('Land Doc Not found', 'flex-eland'),
            'not_found_in_trash' => esc_html__('Land Doc Not found in Trash', 'flex-eland'),
            'featured_image' => esc_html__('Land Doc Feature Image', 'flex-eland'),
            'set_featured_image' => esc_html__('Set Land Doc Feature Image', 'flex-eland'),
            'remove_featured_image' => esc_html__('Remove Feature Image', 'flex-eland'),
            'use_featured_image' => esc_html__('Use as Land Doc featured image', 'flex-eland'),
            'insert_into_item' => esc_html__('Insert into Land Doc', 'flex-eland'),
            'uploaded_to_this_item' => esc_html__('Uploaded to this ', 'flex-eland'),
            'items_list' => esc_html__('Land Doc list', 'flex-eland'),
            'items_list_navigation' => esc_html__('Land Doc list navigation', 'flex-eland'),
            'filter_items_list' => esc_html__('Filter Land Doc List', 'flex-eland')
        ]);

        $this->set_posttype_supports(
            array('title', 'thumbnail', 'editor')
        );

        $this->set_taxonomies(
            array('land-type','media-type','extension-type')
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
        $this->meta_service = new PosttypeSetting();
    }

    public function init_hook(): void
    {
        add_action('after_setup_theme', [$this, 'init_theme_supports']);
                
        // Or if you want to use the action approach:do_action('flex_theme_by_ababilithub_content_template');
        add_action('flex_theme_by_ababilithub_content_template', [$this, 'template_include']);
        //add_filter('template_include', [$this, 'include_template']);
        //remove_filter('template_include', [$this, 'include_template']);
        //add_filter('single_template', array( $this, 'load_single_template' ) );
        //add_filter( 'single_template', array( $this, 'load_single_template' ) );
		//add_filter( 'template_include', array( $this, 'template_include' ) );
        add_filter(PLUGIN_PRE_UNDS.'_admin_menu', [$this, 'add_menu_items']);          
        add_filter('post_row_actions', [$this, 'add_action_view_details'], 10, 2);
        
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
            'page_title' => __('Land Doc', 'flex-eland'),
            'menu_title' => __('Land Doc', 'flex-eland'),
            'capability' => 'manage_options',
            'menu_slug' => 'edit.php?post_type='.POSTTYPE,
            'callback' => null,
            'position' => 9,
        ];

        return $menu_items;
    }

    public function template_include($template) 
    {
        if (is_singular($this->slug)) 
        {
            // Check theme first
            // Theme template hierarchy
            $theme_templates = [
                "single-{POSTTYPE}.php",
                "templates/single-{POSTTYPE}.php",
                "single.php"
            ];
            
            // Check theme files first
            $located = locate_template($theme_templates);
            if ($located) {
                return $located;
            }
            
            // Then check plugin directory
            $plugin_template = trailingslashit(PLUGIN_DIR) . 'src/Package/Plugin/Posttype/Land/Document/Presentation/Template/Single/V1/SinglePost-' . $this->slug . '.php';
            clearstatcache(true, $plugin_template);
            if (file_exists($plugin_template)) 
            {
                return $plugin_template;
            }
        }
        return $template;
    }
}
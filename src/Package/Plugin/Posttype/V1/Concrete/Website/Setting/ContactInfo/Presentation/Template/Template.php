<?php
namespace Ababilithub\FlexELand\Package\Plugin\Posttype\Land\Document\Presentation\Template;

(defined('ABSPATH') && defined('WPINC')) || die();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Mixin\V1\Standard\Mixin as StandardWpMixin,
};

use const Ababilithub\FlexELand\{
    PLUGIN_NAME,
    PLUGIN_DIR,
    PLUGIN_URL,
    PLUGIN_FILE,
    PLUGIN_PRE_UNDS,
    PLUGIN_PRE_HYPH,
    PLUGIN_VERSION
};

if (!class_exists(__NAMESPACE__.'\Template')) 
{
    class Template 
    {
        use StandardMixin, StandardWpMixin;

        private $package;
        private $template_url;
        private $asset_url;
        private $posttype;

        public function __construct() 
        {
            $this->posttype = 'flexdoc';
            $this->asset_url = $this->get_url('Asset/');
            add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts' ) );
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts' ) );
        }

        public function enqueue_scripts()
        {
            wp_enqueue_script('jquery');

            wp_enqueue_style(
                'flex-eland-template-style', 
                $this->asset_url.'css/style.css',
                array(), 
                time()
            );

            wp_enqueue_script(
                'flex-eland-template-script', 
                $this->asset_url.'js/script.js',
                array(), 
                time(), 
                true
            );
            
            wp_localize_script(
                'flex-eland-template-script', 
                PLUGIN_PRE_UNDS.'_'.'template_localize', 
                array(
                    'adminAjaxUrl' => admin_url('admin-ajax.php'),
                    'ajaxUrl' => admin_url('admin-ajax.php'),
                    'ajaxNonce' => wp_create_nonce(PLUGIN_PRE_UNDS . '_nonce'),
                    // 'ajaxAction' => PLUGIN_PRE_UNDS . '_action',
                    // 'ajaxData' => PLUGIN_PRE_UNDS . '_data',
                    // 'ajaxError' => PLUGIN_PRE_UNDS . '_error',
                )
            );
        }

        public static function single_post_template($query)
        {            
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                    </header>

                    <div class="entry-content">
                        <?php the_content(); ?>
                        
                        <?php 
                        // Display custom fields
                        if ($description = get_post_meta(get_the_ID(), '_doc_description', true)) {
                            echo '<div class="doc-description">' . esc_html($description) . '</div>';
                        }
                        ?>
                    </div>
                </article>
            <?php
        }
        
        public static function category_list($query)
        {            
            ?>
                <div class="category-container">
                <?php
                    $categories = get_categories(array(
                        'taxonomy' => 'category',
                        'post_type' => self::$posttype,
                        'hide_empty' => true,
                    ));

                    if (!empty($categories)) 
                    {
                        echo '<ul class="category-list">';
                        foreach ($categories as $category) 
                        {
                            echo '<li><a href="#" data-category-id="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</a></li>';
                        }
                        echo '</ul>';
                    } 
                    else 
                    {
                        echo '<p>No categories found.</p>';
                    }
                ?>
                </div>
            <?php
        }

        public static function portfolio_default_list($query) 
        {
            ?>
                <div class="portfolio-container">
                    <?php
                    if($query->found_posts > 0) 
                    {
                        while ($query->have_posts()) 
                        {
                            $query->the_post();
                            $post_id = get_the_ID();
                            $title = get_the_title();
                            $images = get_post_meta($post_id, 'portfolio_images', true);
                            $image_urls = array();
                            if(is_array($images) && count($images))
                            {
                                foreach($images as $key => $image)
                                {
                                    $image_urls[] = wp_get_attachment_image_url($image, 'full');
                                }
                            }
                            ?>
                            <div class="portfolio-card" data-id="<?php echo $post_id; ?>" data-title="<?php echo esc_attr($title); ?>" data-images="<?php echo esc_attr(json_encode($image_urls)); ?>">
                                <div class="header"></div>
                                <div class="content">
                                    <?php if (has_post_thumbnail($post_id)): ?>
                                        <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'single-post-thumbnail'); ?>
                                        <div>
                                            <img src="<?php echo $image[0] ?>" />
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="footer">
                                    <span><b><?php echo $title; ?></b></span>
                                </div>
                            </div>
                            <?php
                            wp_reset_postdata();
                        }                        
                    }
                    ?>
                </div>                        
            <?php
        }

        public static function lightbox()
        {
            ?>
                <div class="lightbox">
                    <div id="myModal" class="modal">
                        <div class="modal-content">
                            <span class="close cursor">&times;</span>
                            <div class="slider-row">
                                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                                <div class="slideshow"></div>
                                <a class="next" onclick="plusSlides(1)">&#10095;</a>
                            </div>
                            <div class="image-name-row">
                                <div class="image-name">
                                    <p id="caption"></p>
                                </div>
                            </div>
                            <div class="thumbnail-row"></div>
                        </div>
                    </div>
                </div>            
            <?php
        }
    }
}

?>
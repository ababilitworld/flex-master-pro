<?php
namespace Ababilithub\FlexMasterPro\Package\Plugin\Posttype\V1\Concrete\ColorScheme\Presentation\Template\Single;

(defined('ABSPATH') && defined('WPINC')) || die();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Mixin\V1\Standard\Mixin as StandardWpMixin,
    FlexWordpress\Package\PostMeta\V1\Mixin\PostMeta as PostMetaMixin,    
    FlexMasterPro\Package\Plugin\Posttype\V1\Concrete\ColorScheme\Posttype as ColorSchemePosttype,
};

use const Ababilithub\{
    FlexMasterPro\PLUGIN_NAME,
    FlexMasterPro\PLUGIN_DIR,
    FlexMasterPro\PLUGIN_URL,
    FlexMasterPro\PLUGIN_FILE,
    FlexMasterPro\PLUGIN_PRE_UNDS,
    FlexMasterPro\PLUGIN_PRE_HYPH,
    FlexMasterPro\PLUGIN_VERSION,
};

class Template 
{
    use StandardMixin, StandardWpMixin, PostMetaMixin;

    private $package;
    private $template_url;
    private $asset_url;
    private $posttype;

    public function __construct() 
    {
        $this->posttype = ColorSchemePosttype::POSTTYPE;
        $this->asset_url = $this->get_url('Asset/');
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts' ) );
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts' ) );
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('jquery');

        wp_enqueue_style(
            PLUGIN_PRE_HYPH.'-'.ColorSchemePosttype::POSTTYPE.'-template-style', 
            $this->asset_url.'Css/Style.css',
            array(), 
            time()
        );

        wp_enqueue_script(
            PLUGIN_PRE_HYPH.'-'.ColorSchemePosttype::POSTTYPE.'-template-script', 
            $this->asset_url.'Js/Script.js',
            array(), 
            time(), 
            true
        );
        
        wp_localize_script(
            PLUGIN_PRE_HYPH.'-'.ColorSchemePosttype::POSTTYPE.'-template-localize-script', 
            PLUGIN_PRE_UNDS.'_'.ColorSchemePosttype::POSTTYPE.'_template_localize', 
            array(
                'adminAjaxUrl' => admin_url('admin-ajax.php'),
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'ajaxNonce' => wp_create_nonce(PLUGIN_PRE_UNDS.'_'.ColorSchemePosttype::POSTTYPE.'_nonce'),
                // 'ajaxAction' => PLUGIN_PRE_UNDS . '_action',
                // 'ajaxData' => PLUGIN_PRE_UNDS . '_data',
                // 'ajaxError' => PLUGIN_PRE_UNDS . '_error',
            )
        );
    }

    public function single_post($post = null)
    {
        // Use passed post or fall back to global
        $post = $post ?: get_post();
        
        if (!$post) {
            return '';
        }
        
        // Setup post data
        setup_postdata($post);
        
        ob_start();
        ?>
        <main class="fl-single-post">
            <div class="flsp-container">
                <article class="flsp-article" id="post-<?php echo $post->ID; ?>">
                    <!-- Hero Section -->
                    <header class="flsp-hero">
                        <?php if (has_post_thumbnail($post->ID)) : ?>
                            <?php echo get_the_post_thumbnail($post->ID, 'large', ['class' => 'flsp-hero-image']); ?>
                        <?php endif; ?>
                        
                        <div class="flsp-hero-overlay"></div>
                        
                        <div class="flsp-hero-content">
                            <h1 class="flsp-title"><?php echo get_the_title($post); ?></h1>
                            <div class="flsp-meta">
                                <span class="flsp-meta-item">
                                    <i class="fas fa-calendar"></i> <?php echo get_the_date('', $post); ?>
                                </span>
                                <?php if ($post_id = $post->ID) : ?>
                                    <span class="flsp-meta-item">
                                        <i class="fas fa-file-alt"></i> <?php echo esc_html($post_id); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </header>

                    <!-- Main Content Grid -->
                    <div class="flsp-grid">
                        <!-- Primary Content Column -->
                        <div class="flsp-main-content">

                            <!-- Property Details -->
                            <section class="flsp-section">
                                <h2 class="flsp-section-title">
                                    <i class="fas fa-info-circle"></i>
                                    <?php esc_html_e('Company  Information', 'flex-master-pro'); ?>
                                </h2>
                                
                                <div class="flsp-details-grid">
                                    <?php 
                                    $property_details = [
                                        'mobile-number' => ['icon' => 'fas fa-calendar-check', 'label' => __('Mobile Number', 'flex-master-pro')],
                                        'email-address' => ['icon' => 'fas fa-hashtag', 'label' => __('Email Address', 'flex-master-pro')],
                                        'physical-address' => ['icon' => 'fas fa-map-marker-alt', 'label' => __('Physical Address', 'flex-master-pro')],
                                        //'google-map-location' => ['icon' => 'fas fa-ruler-combined', 'label' => __('Google Map Location', 'flex-master-pro')],
                                    ];
                                    
                                    foreach ($property_details as $field => $data) :
                                        if ($value = get_post_meta($post->ID, $field, true)) : ?>
                                            <div class="flsp-card flsp-detail-item">
                                                <div class="flsp-detail-icon">
                                                    <i class="<?php echo esc_attr($data['icon']); ?>"></i>
                                                </div>
                                                <div class="flsp-detail-content">
                                                    <h3><?php echo esc_html($data['label']); ?></h3>
                                                    <p><?php echo esc_html($value); ?></p>
                                                </div>
                                            </div>
                                        <?php endif;
                                    endforeach; ?>
                                </div>
                            </section>

                            <!-- Map Location -->
                            <section class="flsp-section">
                                <h2 class="flsp-section-title">
                                    <i class="fas fa-info-circle"></i>
                                    <?php esc_html_e('Map Location', 'flex-master-pro'); ?>
                                </h2>
                                
                                <div class="flsp-map-grid">
                                    <?php 
                                    $property_details = [
                                        'google-map-location' => ['icon' => 'fas fa-ruler-combined', 'label' => __('Google Map Location', 'flex-master-pro')],
                                    ];
                                    
                                    foreach ($property_details as $field => $data) :
                                        if ($value = get_post_meta($post->ID, $field, true)) : ?>
                                            <div class="flsp-card flsp-map-item">
                                                <?php echo $this->get_map_location($value);?>
                                            </div>
                                        <?php endif;
                                    endforeach; ?>
                                </div>
                            </section>

                            <!-- Company  Content -->
                            <section class="flsp-section">
                                <h2 class="flsp-section-title">
                                    <i class="fas fa-file-signature"></i>
                                    <?php esc_html_e('Company  Details', 'flex-master-pro'); ?>
                                </h2>
                                <div class="flsp-content-wrapper">
                                    <?php echo apply_filters('the_content', $post->post_content); ?>
                                </div>
                            </section>
                        </div>

                        <!-- Sidebar Column -->
                        <aside class="flsp-sidebar">
                            <!-- Documents Gallery Widget -->
                            <?php if ($docs = get_post_meta($post->ID, 'post-attachments', true)) : ?>
                                <div class="flsp-sidebar-widget">
                                    <h3 class="flsp-widget-title">
                                        <i class="fas fa-file-contract"></i>
                                        <?php esc_html_e('Company  Documents', 'flex-master-pro'); ?>
                                    </h3>
                                    <div class="flsp-documents-grid">
                                        <?php foreach ($docs as $doc_id) : 
                                            $doc_url = wp_get_attachment_url($doc_id);
                                            $doc_title = get_the_title($doc_id);
                                            $file_type = wp_check_filetype($doc_url);
                                            $icon = self::get_file_icon($file_type['ext']);
                                            ?>
                                            <div class="flsp-card flsp-document-card">
                                                <div class="flsp-document-icon">
                                                    <i class="<?php echo esc_attr($icon); ?>"></i>
                                                </div>
                                                <div class="flsp-document-info">
                                                    <h4><?php echo esc_html($doc_title); ?></h4>
                                                    <span class="flsp-file-type"><?php echo strtoupper($file_type['ext']); ?></span>
                                                </div>
                                                <a href="<?php echo esc_url($doc_url); ?>" 
                                                class="flsp-download-btn" 
                                                target="_blank" 
                                                download>
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else : ?>
                                <div class="flsp-sidebar-widget">
                                    <h3 class="flsp-widget-title">
                                        <i class="fas fa-file-contract"></i>
                                        <?php esc_html_e('Company  Documents', 'flex-master-pro'); ?>
                                    </h3>
                                    <p class="flsp-text-center flsp-mb-0"><?php esc_html_e('No documents available', 'flex-master-pro'); ?></p>
                                </div>
                            <?php endif; ?>

                            <!-- Image Gallery -->
                            <?php if ($images = get_post_meta($post->ID, 'post-images', true)) : ?>
                                <div class="flsp-sidebar-widget">
                                    <h3 class="flsp-widget-title">
                                        <i class="fas fa-images"></i>
                                        <?php esc_html_e('Image Gallery', 'flex-master-pro'); ?>
                                    </h3>
                                    <div class="flsp-gallery-grid">
                                        <?php foreach ($images as $image_id) : 
                                            $image_url = wp_get_attachment_image_url($image_id, 'medium');
                                            $image_full = wp_get_attachment_image_url($image_id, 'full');
                                            ?>
                                            <a href="<?php echo esc_url($image_full); ?>" class="flsp-gallery-item" data-fancybox="deed-gallery">
                                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(get_the_title($image_id)); ?>">
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Related Company s -->
                            <?php 
                            $mouza_terms = get_the_terms($post->ID, 'land-mouza');
                            if ($mouza_terms && !is_wp_error($mouza_terms)) :
                                $related_args = [
                                    'post_type' => 'fldeed',
                                    'posts_per_page' => 3,
                                    'post__not_in' => [$post->ID],
                                    'tax_query' => [
                                        [
                                            'taxonomy' => 'land-mouza',
                                            'field' => 'term_id',
                                            'terms' => wp_list_pluck($mouza_terms, 'term_id')
                                        ]
                                    ]
                                ];
                
                                $related_deeds = new \WP_Query($related_args);
                                
                                if ($related_deeds->have_posts()) : ?>
                                    <div class="flsp-sidebar-widget">
                                        <h3 class="flsp-widget-title">
                                            <i class="fas fa-paperclip"></i>
                                            <?php esc_html_e('Related Company s', 'flex-master-pro'); ?>
                                        </h3>
                                        <div class="flsp-related-grid">
                                            <?php while ($related_deeds->have_posts()) : $related_deeds->the_post(); ?>
                                                <a href="<?php the_permalink(); ?>" class="flsp-card flsp-related-card">
                                                    <?php if (has_post_thumbnail()) : ?>
                                                        <div class="flsp-related-thumbnail">
                                                            <?php the_post_thumbnail('thumbnail'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="flsp-related-content">
                                                        <h4><?php the_title(); ?></h4>
                                                        <?php if ($deed_number = get_post_meta(get_the_ID(), 'deed-number', true)) : ?>
                                                            <span class="flsp-deed-number"><?php echo esc_html($deed_number); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </a>
                                            <?php endwhile; wp_reset_postdata(); ?>
                                        </div>
                                    </div>
                                <?php endif;
                            endif; ?>
                        </aside>
                    </div>
                </article>
            </div>
        </main>
        <?php
        
        // Reset post data
        wp_reset_postdata();
        
        return ob_get_clean();
    }
    /**
     * Get Font Awesome icon for a file extension
     * 
     * @param string $extension The file extension (without dot)
     * @return string Font Awesome icon class
     */
    public static function get_file_icon($extension) 
    {
        $extension = strtolower($extension);
        
        $icon_map = [
            // Documents
            'pdf'   => 'fas fa-file-pdf',
            'doc'   => 'fas fa-file-word',
            'docx'  => 'fas fa-file-word',
            'odt'   => 'fas fa-file-alt',
            'txt'   => 'fas fa-file-alt',
            'rtf'   => 'fas fa-file-alt',
            
            // Spreadsheets
            'xls'   => 'fas fa-file-excel',
            'xlsx'  => 'fas fa-file-excel',
            'ods'   => 'fas fa-file-excel',
            'csv'   => 'fas fa-file-csv',
            
            // Presentations
            'ppt'   => 'fas fa-file-powerpoint',
            'pptx'  => 'fas fa-file-powerpoint',
            'odp'   => 'fas fa-file-powerpoint',
            
            // Archives
            'zip'   => 'fas fa-file-archive',
            'rar'   => 'fas fa-file-archive',
            '7z'    => 'fas fa-file-archive',
            'tar'   => 'fas fa-file-archive',
            'gz'    => 'fas fa-file-archive',
            
            // Images
            'jpg'   => 'fas fa-file-image',
            'jpeg'  => 'fas fa-file-image',
            'png'   => 'fas fa-file-image',
            'gif'   => 'fas fa-file-image',
            'webp'  => 'fas fa-file-image',
            'svg'   => 'fas fa-file-image',
            'bmp'   => 'fas fa-file-image',
            
            // Audio/Video
            'mp3'   => 'fas fa-file-audio',
            'wav'   => 'fas fa-file-audio',
            'ogg'   => 'fas fa-file-audio',
            'mp4'   => 'fas fa-file-video',
            'mov'   => 'fas fa-file-video',
            'avi'   => 'fas fa-file-video',
            'mkv'   => 'fas fa-file-video',
            
            // Code
            'php'   => 'fas fa-file-code',
            'html'  => 'fas fa-file-code',
            'css'   => 'fas fa-file-code',
            'js'    => 'fas fa-file-code',
            'json'  => 'fas fa-file-code',
            'xml'   => 'fas fa-file-code',
            
            // Other
            'exe'   => 'fas fa-file-download',
            'dmg'   => 'fas fa-file-download',
        ];
        
        // Return specific icon if found, otherwise generic file icon
        return $icon_map[$extension] ?? 'fas fa-file';
    }
}

?>
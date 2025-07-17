<?php
namespace Ababilithub\FlexELand\Package\Plugin\Posttype\V1\Concrete\Website\Setting\ContactInfo\Presentation\Template\Single;

(defined('ABSPATH') && defined('WPINC')) || die();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Mixin\V1\Standard\Mixin as StandardWpMixin,
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

class Template 
{
    use StandardMixin, StandardWpMixin;

    private $package;
    private $template_url;
    private $asset_url;
    private $posttype;

    public function __construct() 
    {
        $this->posttype = POSTTYPE;
        $this->asset_url = $this->get_url('Asset/');
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts' ) );
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts' ) );
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('jquery');

        wp_enqueue_style(
            PLUGIN_PRE_HYPH.'-'.POSTTYPE.'-template-style', 
            $this->asset_url.'Css/Style.css',
            array(), 
            time()
        );

        wp_enqueue_script(
            PLUGIN_PRE_HYPH.'-'.POSTTYPE.'-template-script', 
            $this->asset_url.'Js/Script.js',
            array(), 
            time(), 
            true
        );
        
        wp_localize_script(
            PLUGIN_PRE_HYPH.'-'.POSTTYPE.'-template-localize-script', 
            PLUGIN_PRE_UNDS.'_'.POSTTYPE.'_template_localize', 
            array(
                'adminAjaxUrl' => admin_url('admin-ajax.php'),
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'ajaxNonce' => wp_create_nonce(PLUGIN_PRE_UNDS.'_'.POSTTYPE.'_nonce'),
                // 'ajaxAction' => PLUGIN_PRE_UNDS . '_action',
                // 'ajaxData' => PLUGIN_PRE_UNDS . '_data',
                // 'ajaxError' => PLUGIN_PRE_UNDS . '_error',
            )
        );
    }

    public static function single_post($post = null)
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
        <?php echo "bismillah";echo "<pre>";print_r($post);echo "</pre>";?>
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
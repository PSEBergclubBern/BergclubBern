<?php
/**
 * Berclub Bern functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * @package WordPress
 * @subpackage Bergclub_Bern
 */

/**
 * Only works in WordPress 4.1 or later.
 */
if (version_compare($GLOBALS['wp_version'], '4.1-alpha', '<')) {
    require get_template_directory() . '/inc/back-compat.php';
}

if (!function_exists('bcb_setup')) {
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function bcb_setup()
    {
        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
         */
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(825, 510, true);

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'header-menu' => 'Hauptmenü',
            'footer-menu' => 'Fusszeile',
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
        ));

        /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        add_theme_support('post-formats', array(
            'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
        ));
    }
};

add_action('after_setup_theme', 'bcb_setup');

if (!function_exists('bcb_post_thumbnail')) {
    /**
     * Display an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     */
    function bcb_post_thumbnail()
    {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return;
        }

        if (is_singular()) {
            ?>

            <div class="post-thumbnail">
                <?php the_post_thumbnail(); ?>
            </div><!-- .post-thumbnail -->

        <?php } else { ?>

            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
                <?php
                the_post_thumbnail('post-thumbnail', array('alt' => get_the_title()));
                ?>
            </a>

        <?php }
    }
}

if (!function_exists('bcb_enqueue_scripts')) {
    /**
     * Adds needed style and script files
     */
    function bcb_enqueue_scripts()
    {
        wp_dequeue_script('jquery-core');
        wp_deregister_script('jquery-core');

        wp_enqueue_style('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
        wp_enqueue_style('lightbox', get_template_directory_uri() . '/css/lightbox.css');
        wp_enqueue_style('font-roboto', 'https://fonts.googleapis.com/css?family=Roboto:400,400i', ['bootstrap']);
        //wp_enqueue_style('font-touren',  get_template_directory_uri() . '/css/fonts/touren.css', ['bootstrap']);
        if (!bcb_is_jugend()) {
            wp_enqueue_style('default', get_template_directory_uri() . '/css/bergclub.css', ['bootstrap']);
        } else {
            wp_enqueue_style('default', bcb_add_jugend_to_url(get_template_directory_uri() . '/css/jugend.css'), ['bootstrap']);
        }
        wp_enqueue_style('carousel', bcb_add_jugend_to_url(get_template_directory_uri() . '/css/carousel.css', true), ['bootstrap']);

        wp_add_inline_style('carousel', bcb_carousel_images_css());

        wp_enqueue_script('ielt9', bcb_add_jugend_to_url(get_template_directory_uri() . '/js/html5.js', true));
        wp_script_add_data('ielt9', 'conditional', 'lt IE 9');

        wp_enqueue_script('jquery-own', 'https://code.jquery.com/jquery-3.1.1.min.js', null, null, true);
        wp_enqueue_script('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', ['jquery-own'], null, true);
        wp_enqueue_script('lightbox', get_template_directory_uri() . '/js/lightbox.js', ['jquery-own'], null, true);
        wp_enqueue_script('bergclub', bcb_add_jugend_to_url(get_template_directory_uri() . '/js/bergclub.js', true), ['jquery-own'], null, true);
    }
}

add_action('wp_enqueue_scripts', 'bcb_enqueue_scripts');

add_editor_style(get_template_directory_uri() . '/css/bergclub.css');

if (!function_exists('bcb_carousel_images_css')) {
    /**
     * Creates inline css for the background images located in folder img/carousel.
     * Adds the images in random order.
     *
     * @return string the needed inline style to include with `wp_enqueue_style`.
     */
    function bcb_carousel_images_css()
    {
        $images = get_option('bcb_background_images');
        shuffle($images);
        $carouselImages = "";
        $index = 0;
        foreach ($images as $image) {
            if ($image['active']) {
                $index++;
                $carouselImages .= ".item:nth-child(" . $index . "){background: url(" . bcb_add_jugend_to_url(get_template_directory_uri() . $image['filename'], true) . ") no-repeat " . $image['vertical'] . " " . $image['horizontal'] . " fixed;}";
            }
        }
        return $carouselImages;
    }
}

//include custom post types
require_once(__DIR__ . '/inc/post-types.php');

//include bootstrap navigation walker
require_once('inc/wp_bootstrap_navwalker.php');

//including functionality used for jugend
require_once(__DIR__ . '/inc/jugend.php');

//including functionality used for custom pagination
require_once __DIR__ . '/inc/pagination.php';

//including functionality used for adding and display notices (bootstrap alerts)
require_once __DIR__ . '/inc/notice.php';

//including simple math captcha functionality
require_once __DIR__ . '/inc/captcha.php';

//including background images functionality
require_once __DIR__ . '/inc/background-images.php';

//Ensures that only the category name is shown as title in archive
add_filter('get_the_archive_title', function ($title) {
    return trim(str_replace('Kategorie:', '', str_replace('Archive:', '', $title)));
});

//disable admin bar in frontend
add_filter('show_admin_bar', '__return_false');

//adds Training and JS to the Title
function add_additional_info_to_title($title, $id = null)
{
    if ("touren" == get_post_type(get_post($id))) {
        $training = get_post_meta($id, "_training", true);
        $jsEvent = get_post_meta($id, "_jsEvent", true);
        if (!empty($training) || !empty($jsEvent)) {
            $title .= " (";
            if (!empty($training)) {
                $title .= "Training";
                if (!empty($jsEvent))
                    $title .= ", JS-Event";
            } else {
                $title .= "JS-Event";
            }
            $title .= ")";
        }
    }

    return $title;
}

//adds the filter for the title
add_filter('the_title', 'add_additional_info_to_title', 10, 2);


//removes all images from the content and reinserts them at the beginning with as lightbox images
function move_images_to_lightbox($content)
{
    $img_ids = array();

    //searches the gallery ids in the content and places them in the $img_ids array
    $gallery_ids = array();
    preg_match_all('/gallery ids="(?<ids>[\d+,?]+)"/', $content, $gallery_ids);
    if (array_key_exists('ids', $gallery_ids)) {
        foreach ($gallery_ids['ids'] as $ids) {
            $img_ids = array_merge($img_ids, explode(",", $ids));
        }
    }

    //searches single image ids in the content and places them in the $img_ids array
    $single_img_ids = array();
    preg_match_all('/wp-image-(?<id>\d+)/', $content, $single_img_ids);
    if (array_key_exists('id', $single_img_ids)) {
        foreach ($single_img_ids['id'] as $id) {
            array_push($img_ids, $id);
        }
    }

    //remove all images from the content
    $content = preg_replace("/\[gallery[^\]]+\]/i", " ", $content); //replaces the gallery
    $content = preg_replace("/\[caption[^\\\]+\/caption]/i", " ", $content); //replaces single images with caption
    $content = preg_replace("/<img[^>]+\>/i", " ", $content);  //replaces single images without caption

    //if there were no image ids found, return
    if (empty($img_ids)) {
        return $content;
    }

    //if there are images patch together the html for the lightbox images
    $lightbox_html = "<div class=\"report-images row\">";
    foreach ($img_ids as $id) {
        $imgDescription = htmlentities(get_post($id)->post_excerpt);
        $lightbox_html .= "<a href=\"" . wp_get_attachment_url($id) . "\" data-lightbox=\"report-gallery\" data-title=\"" . nl2br($imgDescription) . "\">
                <img alt=\"" . $imgDescription . "\" title=\"" . $imgDescription . "\" src=\"" . wp_get_attachment_thumb_url($id) . "\" class=\"report-image\"></a>";
    }
    $lightbox_html .= "</div>";
    return $lightbox_html . $content;
}

//adds the filter for the content
add_filter('the_content', 'move_images_to_lightbox');


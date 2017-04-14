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
            'header-menu' => __('Hauptmenü', 'header-navigation'),
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

if ( ! function_exists( 'bcb_post_thumbnail' ) ) {
    /**
     * Display an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     */
    function bcb_post_thumbnail() {
        if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
            return;
        }

        if ( is_singular() ) {
            ?>

            <div class="post-thumbnail">
                <?php the_post_thumbnail(); ?>
            </div><!-- .post-thumbnail -->

        <?php }else{ ?>

            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
                <?php
                the_post_thumbnail( 'post-thumbnail', array( 'alt' => get_the_title() ) );
                ?>
            </a>

        <?php }
    }
}

if ( ! function_exists( 'bcb_enqueue_scripts' ) ) {
    /**
     * Adds needed style and script files
     */
    function bcb_enqueue_scripts()
    {
        wp_dequeue_script('jquery-core');
        wp_deregister_script('jquery-core');

        wp_enqueue_style('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
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
        wp_enqueue_script('bergclub', bcb_add_jugend_to_url(get_template_directory_uri() . '/js/bergclub.js', true), ['jquery-own'], null, true);
    }
}

add_action( 'wp_enqueue_scripts', 'bcb_enqueue_scripts' );

if ( ! function_exists( 'bcb_carousel_images_css' ) ) {
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
                $carouselImages .= ".item:nth-child(" . $index . "){background: url(" . bcb_add_jugend_to_url(get_template_directory_uri() . $image['filename'], true) . ") no-repeat " . $image['vertical'] . " ". $image['horizontal'] . " fixed;}";
            }
        }
        return $carouselImages;
    }
}

/**
 * Registers the custom post types
 */
function bcb_register_custom_post_types() {

    /**
     * Post Type: Touren.
     */

    $labels = array(
        "name" => __( 'Touren', '' ),
        "singular_name" => __( 'Tour', '' ),
        "menu_name" => __( 'Touren', '' ),
        "all_items" => __( 'Touren', '' ),
        "add_new" => __( 'Tour erfassen', '' ),
        "add_new_item" => __( 'Neue Tour erfassen', '' ),
        "edit_item" => __( 'Tour anpassen', '' ),
        "new_item" => __( 'Neue Tour', '' ),
        "view_item" => __( 'Tour ansehen', '' ),
        "view_items" => __( 'Touren ansehen', '' ),
        "search_items" => __( 'Tour suchen', '' ),
    );

    $args = array(
        "label" => __( 'Touren', '' ),
        "labels" => $labels,
        "description" => "Touren des Bergclubs Bern",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => false,
        "rest_base" => "",
        "has_archive" => true,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => ['tour', 'touren'],
        "map_meta_cap" => true,
        "hierarchical" => false,
        "query_var" => true,
        "supports" => array( "title", "editor", "thumbnail" ),
        "menu_position" => 5,
        "rewrite" => ['slug' => 'tourenprogramm'],
    );

    register_post_type( "touren", $args );

    //disable editor for post type 'touren';
    remove_post_type_support( 'touren', 'editor' );


    /**
     * Post Type: Tourenberichte.
     */

    $labels = array(
        "name" => __( 'Tourenberichte', '' ),
        "singular_name" => __( 'Tourenbericht', '' ),
        "menu_name" => __( 'Tourenberichte', '' ),
        "all_items" => __( 'Tourenberichte', '' ),
        "add_new" => __( 'Tourenbericht erfassen', '' ),
        "add_new_item" => __( 'Neuen Tourenbericht erfassen', '' ),
        "edit_item" => __( 'Tourenbericht anpassen', '' ),
        "new_item" => __( 'Neuer Tourenbericht', '' ),
        "view_item" => __( 'Tourenbericht ansehen', '' ),
        "view_items" => __( 'Tourenberichte ansehen', '' ),
        "search_items" => __( 'Tourenbericht suchen', '' ),
    );

    $args = array(
        "label" => __( 'Tourenberichte', '' ),
        "labels" => $labels,
        "description" => "Tourenberichte des Bergclubs Bern",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => false,
        "rest_base" => "",
        "has_archive" => true,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => ['tourenbericht', 'tourenberichte'],
        "map_meta_cap" => true,
        "hierarchical" => false,
        "query_var" => true,
        "supports" => array( "title", "editor", "thumbnail" ),
        "menu_position" => 6,
    );

    register_post_type( "tourenberichte", $args );

    remove_post_type_support( 'tourenberichte', 'title' );
}

add_action( 'init', 'bcb_register_custom_post_types' );


function bcb_get_touren_type_by_slug($slug){
    $tourenTypes = get_option('bcb_tourenarten');
    if(isset($tourenTypes[$slug])) {
        return $tourenTypes[$slug];
    }
    return "";
}

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
add_filter( 'get_the_archive_title', function ( $title ) {
    return trim(str_replace('Kategorie:', '', str_replace('Archive:', '', $title)));
});

//disable admin bar in frontend
add_filter('show_admin_bar', '__return_false');
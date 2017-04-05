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
     *
     * @since Twenty Fifteen 1.0
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

function bcb_carousel_images(){
    $files = scandir(__DIR__ . '/img/carousel');
    $carouselImages = null;
    $index = 0;
    foreach($files as $file){
        if($file != "." && $file != ".."){
            $index++;
            $carouselImages .= "
                .item:nth-child(". $index . ") {
                    background: url(" . get_template_directory_uri() . "/img/" . $file .") no-repeat top center fixed;
                }";
        }
    }
    return $carouselImages;
}

//adding css/js files
function bcb_enqueue_scripts()
{
    wp_dequeue_script('jquery-core');
    wp_deregister_script('jquery-core');

    wp_enqueue_style('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
    wp_enqueue_style('default', get_template_directory_uri() . '/css/bergclub.css', ['bootstrap']);
    wp_enqueue_style('jugend', get_template_directory_uri() . '/css/jugend.css', ['default']);
    wp_enqueue_style('carousel', get_template_directory_uri() . '/css/carousel.css', ['bootstrap']);

    wp_add_inline_style( 'carousel', bcb_carousel_images());

    wp_enqueue_script('ielt9', get_template_directory_uri() . '/js/html5.js');
    wp_script_add_data('ielt9', 'conditional', 'lt IE 9');

    wp_enqueue_script('jquery-own', 'https://code.jquery.com/jquery-3.1.1.min.js', null, null, true);
    wp_enqueue_script('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', ['jquery-own'], null, true);
    wp_enqueue_script('bergclub', get_template_directory_uri() . '/js/bergclub.js', ['jquery-own'], null, true);
}

add_action( 'wp_enqueue_scripts', 'bcb_enqueue_scripts' );


// Add navigation walker for bootstrap menu
require_once('inc/wp_bootstrap_navwalker.php');

function add_query_vars_filter( $vars ){
    $vars[] = "jugend";
    return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );

//disable admin bar in frontend
add_filter('show_admin_bar', '__return_false');

function bcb_is_jugend(){
    $hostParts = explode('.', $_SERVER['SERVER_NAME']);
    return $hostParts[0] == 'jugend';
}

function bcb_jugend_home(){
    $hostParts = explode('.', $_SERVER['SERVER_NAME']);
    return $hostParts[0] == 'jugend' ? '//' . $_SERVER['SERVER_NAME'] . '/' : '//jugend.' . $_SERVER['SERVER_NAME'] . '/';
}

function add_jugend_to_href( $atts, $item, $args ) {
    if(bcb_is_jugend()){
        $parsedURL = parse_url($atts['href']);
        if(!empty($parsedURL)){
            if(!isset($parsedURL['path'])){
                $parsedURL['path'] = '/';
            }
            $atts['href'] = '//' . $_SERVER['SERVER_NAME'] . $parsedURL['path'];

            if(isset($parsedURL['query'])){
                $atts['href'] .= '?' . $parseURL['query'];
            }
        }else{
            $atts['href'] = '//' . $_SERVER['SERVER_NAME'] . '/';
        }

    }
    if(get_query_var("jugend", false)) {
        //$atts['href'] = add_query_arg( 'jugend', 'true', $atts['href']);
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'add_jugend_to_href', 10, 3 );


/*
 * Ensures that only the category name is shown as title in archive
 */
add_filter( 'get_the_archive_title', function ( $title ) {

    if( is_category() ) {

        $title = single_cat_title( '', false );

    }

    return $title;

});


/*
 * Creates a custom pagination
 */
function bcb_pagination() {

    $pagerange = 5;

    global $paged;
    if (empty($paged)) {
        $paged = 1;
    }

    global $wp_query;
    $numpages = $wp_query->max_num_pages;

    if(!$numpages) {
        $numpages = 1;
    }

    $pagination_args = [
        'total'           => $numpages,
        'show_all'        => false,
        'end_size'        => 1,
        'mid_size'        => $pagerange,
        'prev_next'       => false,
        'type'            => 'array',
    ];

    $paginate = paginate_links($pagination_args);
    $paginate = str_replace("<span class='page-numbers current'>", "", $paginate);
    $paginate = str_replace("</span>", "", $paginate);
    $paginate = str_replace(" class='page-numbers'", "", $paginate);

    global $wp;
    $current_url = home_url(add_query_arg(array(),$wp->request));

    $pagination = "<ul class='pagination'>";

    foreach($paginate as $page){
        if($page == $paged) {
            $pagination .= "<li class='active'><a href='" . $current_url . "'>" . $page . "</a></li>";
        }else{
            $pagination .= "<li>" . $page . "</li>";
        }
    }
    $pagination .= "</ul>";


    echo $pagination;
}
<?php

/**
 * Creates a custom pagination
 */
function bcb_pagination()
{

    $pagerange = 5;

    global $paged;
    if (empty($paged)) {
        $paged = 1;
    }

    global $wp_query;
    $numpages = $wp_query->max_num_pages;

    if (!$numpages) {
        $numpages = 1;
    }

    $pagination_args = [
        'total' => $numpages,
        'show_all' => false,
        'end_size' => 1,
        'mid_size' => $pagerange,
        'prev_next' => false,
        'type' => 'array',
    ];

    $pagination = "";
    $paginate = paginate_links($pagination_args);
    if (!empty($paginate)) {
        $paginate = str_replace("<span class='page-numbers current'>", "", $paginate);
        $paginate = str_replace("</span>", "", $paginate);
        $paginate = str_replace(" class='page-numbers'", "", $paginate);

        global $wp;
        $current_url = home_url(add_query_arg(array(), $wp->request));

        $pagination = "<ul class='pagination'>";

        foreach ($paginate as $page) {
            if ($page == $paged) {
                $pagination .= "<li class='active'><a href='" . $current_url . "'>" . $page . "</a></li>";
            } else {
                $pagination .= "<li>" . $page . "</li>";
            }
        }
        $pagination .= "</ul>";
    }

    if (bcb_is_jugend()) {
        $pagination = str_replace(bcb_host(), bcb_jugend_host(), $pagination);
    }

    echo $pagination;
}

/**
 * Creates custom previous / next links
 */
function bcb_prev_next_links()
{
    $prev = "";
    $post = get_previous_post(true);
    if ($post) {
        $prev = "<li><a href='" . $post->guid . "'>&laquo; " . $post->post_title . "</a></li>";
    }

    $next = "";
    $post = get_next_post(true);
    if ($post) {
        $next = "<li><a href='" . $post->guid . "'>" . $post->post_title . " &raquo;</a></li>";
    }


    $wpCategory = get_the_category();
    $category = "";
    if (!empty($wpCategory)) {
        $categoryId = $wpCategory[0]->term_id;
        $category = "<li><a href='" . get_category_link($categoryId) . "'>Alle " . $wpCategory[0]->name . "</a></li>";
        $prev = str_replace('/uncategorized/', '/' . $wpCategory[0]->slug . '/', $prev);
        $next = str_replace('/uncategorized/', '/' . $wpCategory[0]->slug . '/', $next);
    }

    $links = $prev . $category . $next;

    if (bcb_is_jugend()) {
        $links = str_replace(bcb_host(), bcb_jugend_host(), $links);
    }

    echo "<ul class='pagination'>" . $links . "</ul>";
}
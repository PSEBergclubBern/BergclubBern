<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Bergclub Bern
 */
?>

            </div><!-- .site-content -->

            <div class="footer navbar-fixed-bottom">
                <p>&copy; Bergclub Bern 2017</p>
                <p class="pull-right"><a href="<?php echo get_permalink(get_page_by_path('kontakt'))?>">Kontakt</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo get_permalink(get_page_by_path('impressum')) ?>">Impressum</a></p>
            </div>

        </div><!-- .site -->

    <?php wp_footer(); ?>

    </body>
</html>

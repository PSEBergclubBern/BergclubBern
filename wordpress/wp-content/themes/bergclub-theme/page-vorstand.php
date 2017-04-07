<?php
/**
 * Template for page-vorstand
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>
            <div class="container">
                <div class="row">
                    <?php
                    the_title('<h1 class="page-header">', '</h1>');
                    the_content('<p>', '</p>');
                    ?>
                </div>
                <div class="row">
                    <div class="table-responsive page-content">
                        <table class="table table-hover">
                            <colgroup>
                                <col class="col-md-4">
                                <col class="col-md-3">
                                <col class="col-md-5">
                            </colgroup>
                            <tr>
                                <td>
                                    <strong>Präsident</strong><br>
                                    Grossenbacher Peter<br>
                                    Rebenweg 37, 3293 Dotzigen
                                </td>
                                <td>
                                    032 353 73 55 (P)<br>
                                    031 631 30 89 (G)
                                </td>
                                <td>
                                    peter_grossenbacher(@)bluewin.ch
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Tourenchef BCB</strong><br>
                                    Michlig Rudolf<br>
                                    Rütiweg 87, 3072 Ostermundigen
                                </td>
                                <td>
                                    031 931 68 87 (P)<br>
                                    079 410 37 06 (N)
                                </td>
                                <td>
                                    rudolf.michlig(@)bluewin.ch
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

<?php get_footer(); ?>
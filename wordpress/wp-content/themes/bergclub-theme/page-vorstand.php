<?php

get_header(); ?>
            <div class="container">
                <div class="row">
                    <?php the_title('<h1 class="page-header">', '</h1>'); ?>
                </div>
                <div class="row">
                    <div class="table-responsive page-content">
                        <table class="table table-hover">
                            <colgroup>
                                <col class="col-md-5">
                                <col class="col-md-4">
                                <col class="col-md-3">
                            </colgroup>
                            <?php do_action('bcb_vorstand_table', 'vorstand'); ?>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <h1 class="page-header">Weitere Chargen</h1>
                </div>
                <div class="row">
                    <div class="table-responsive page-content">
                        <table class="table table-hover">
                            <colgroup>
                                <col class="col-md-5">
                                <col class="col-md-4">
                                <col class="col-md-3">
                                <?php do_action('bcb_vorstand_table_chargen', 'vorstandchargen'); ?>
                            </colgroup>
                        </table>
                    </div>
                </div>

            </div>

<?php get_footer(); ?>
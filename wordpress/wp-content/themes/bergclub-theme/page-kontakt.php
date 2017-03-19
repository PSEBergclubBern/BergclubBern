<?php
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
            <div class="container">
                <div class="page-content">
                    <h1>Kontaktformular</h1>
                    <br />
                    <form class="form-horizontal">

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="anrede">Anrede:</label>
                            <div class="col-sm-10"><select id="sel1" class="form-control">
                                    <option>Frau</option>
                                    <option>Herr</option>
                                </select></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">Name:</label>
                            <div class="col-sm-10"><input id="name" class="form-control" type="text" placeholder="Name" /></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="vorname">Vorname:</label>
                            <div class="col-sm-10"><input id="vorname" class="form-control" type="text" placeholder="Vorname" /></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="Zusatz">Zusatz:</label>
                            <div class="col-sm-10"><input id="zusatz" class="form-control" type="text" placeholder="Zusatz" /></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="strasse">Strasse:</label>
                            <div class="col-sm-10"><input id="strasse" class="form-control" type="text" placeholder="Strasse" /></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="plz">PLZ:</label>
                            <div class="col-sm-10"><input id="plz" class="form-control" type="text" placeholder="PLZ" /></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="ort">Ort:</label>
                            <div class="col-sm-10"><input id="ort" class="form-control" type="text" placeholder="Ort" /></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="telefon_p">Telefon (P):</label>
                            <div class="col-sm-10"><input id="telefon_p" class="form-control" type="text" placeholder="Telefon (P)" /></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="telefon_g">Telefon (G):</label>
                            <div class="col-sm-10"><input id="telefon_g" class="form-control" type="text" placeholder="Telefon (G)" /></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="mobile">Mobile:</label>
                            <div class="col-sm-10"><input id="mobile" class="form-control" type="text" placeholder="Mobile" /></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email">Email:</label>
                            <div class="col-sm-10"><input id="email" class="form-control" type="email" placeholder="Email" /></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="geburtsdatum">Geburtsdatum:</label>
                            <div class="col-sm-10"><input id="geburtsdatum" class="form-control" type="date" placeholder="01.01.1970" /></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="bemerkungen">Bemerkungen:</label>
                            <div class="col-sm-10"><textarea id="bemerkungen" class="form-control" rows="5"></textarea></div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-2"><button class="btn btn-default" type="submit">Absenden</button></div>
                        </div>
                    </form>
                    <br /> 
                </div>
            </div>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>

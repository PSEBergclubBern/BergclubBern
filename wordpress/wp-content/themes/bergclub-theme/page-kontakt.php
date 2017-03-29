<?php
get_header();

$parsedURL = parse_url($_SERVER['REQUEST_URI']);
$pathParts = explode('/', trim($parsedURL['path'], '/'));
$page = $pathParts[0];

include 'contact-form-action.php';

?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
            <div class="container">
                <div class="page-content">
                    <div class="row">
                    <?=$success?'
                    <div class="col-sm-4 col-sm-offset-4 alert alert-success alert-dismissable fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        Nachricht erfolgreich versendet.
                    </div>
                    ':'';?>
                    </div>
                    <div class="row">
                    <h1>Kontaktformular</h1>
                    </div>
                    <br />
                    <form class="form-horizontal text-left" method="post">
                        <div class="form-group enquirytype">
                            <label class="control-label col-sm-2" for="enquirytype">Anfrageart:</label>
                            <div class="col-sm-10"><select name="enquirytype" id="enquirytype" class="form-control">
                                <?php foreach($selectValues as $key => $name){?>
                                <option value="<?=$key ?>"><?=$name ?></option>
                                <?php } ?>
                            </select></div>
                        </div>

                        <div class="form-group gender">
                            <label class="control-label col-sm-2" for="gender">Anrede:</label>
                            <div class="col-sm-10"><select name="gender" id="gender" class="form-control">
                                <option>Frau</option>
                                <option>Herr</option>
                            </select></div>
                        </div>

                        <div class="form-group last-name">
                            <label class="control-label col-sm-2" for="last-name">Nachname:</label>
                            <div class="col-sm-10"><input name="last-name" id="last-name" class="form-control always-required maybe-required" type="text" placeholder="Nachname" required /></div>
                        </div>

                        <div class="form-group first-name">
                            <label class="control-label col-sm-2" for="first-name">Vorname:</label>
                            <div class="col-sm-10"><input name="first-name" id="first-name" class="form-control always-required maybe-required" type="text" placeholder="Vorname" required /></div>
                        </div>

                        <div class="form-group adress-affix">
                            <label class="control-label col-sm-2" for="adress-affix">Adresszusatz:</label>
                            <div class="col-sm-10"><input name="adress-affix" id="adress-affix" class="form-control" type="text" placeholder="Adresszusatz" /></div>
                        </div>

                        <div class="form-group street">
                            <label class="control-label col-sm-2" for="street">Strasse:</label>
                            <div class="col-sm-10"><input name="street" id="street" class="form-control adresschange-required membership-required maybe-required" type="text" placeholder="Strasse" /></div>
                        </div>

                        <div class="form-group zip">
                            <label class="control-label col-sm-2" for="zip">PLZ:</label>
                            <div class="col-sm-10"><input name="zip" id="zip" class="form-control adresschange-required membership-required maybe-required" type="text" placeholder="PLZ" pattern="[1-9][0-9]{3}"/></div>
                        </div>

                        <div class="form-group city">
                            <label class="control-label col-sm-2" for="city">Ort:</label>
                            <div class="col-sm-10"><input name="city" id="city" class="form-control adresschange-required membership-required maybe-required" type="text" placeholder="Ort" /></div>
                        </div>

                        <div class="form-group phone-p">
                            <label class="control-label col-sm-2" for="phone-p">Telefon (P):</label>
                            <div class="col-sm-10"><input name="phone-p" id="phone-p" class="form-control maybe-required" type="tel" placeholder="Telefon (P)" /></div>
                        </div>

                        <div class="form-group phone-g">
                            <label class="control-label col-sm-2" for="phone-g">Telefon (G):</label>
                            <div class="col-sm-10"><input name="phone-g" id="phone-g" class="form-control maybe-required" type="tel" placeholder="Telefon (G)" /></div>
                        </div>

                        <div class="form-group phone-m">
                            <label class="control-label col-sm-2" for="phone-m">Telefon (M):</label>
                            <div class="col-sm-10"><input name="phone-m" id="phone-m" class="form-control maybe-required" type="tel" placeholder="Telefon (M)" /></div>
                        </div>

                        <div class="form-group email">
                            <label class="control-label col-sm-2" for="email">Email:</label>
                            <div class="col-sm-10"><input name="email" id="email" class="form-control maybe-required" type="email" placeholder="Email" /></div>
                        </div>

                        <div class="form-group birthday">
                            <label class="control-label col-sm-2" for="birthday">Geburtsdatum:</label>
                            <div class="col-sm-10"><input name="birthday" id="birthday" class="form-control membership-required maybe-required" type="date" /></div>
                        </div>

                        <div class="form-group comment">
                            <label class="control-label col-sm-2" for="comment">Bemerkungen:</label>
                            <div class="col-sm-10"><textarea name="comment" id="comment" class="form-control vresize message-required maybe-required" rows="5" required></textarea></div>
                        </div>

                        <div class="form-group submit">
                            <div class="col-sm-offset-2 col-sm-2"><button class="btn btn-default btn-lg pull-left" type="submit">Absenden</button></div>
                        </div>
                    </form>
                    <br />
                </div>
            </div>
		</main>
	</div>

<?php get_footer(); ?>

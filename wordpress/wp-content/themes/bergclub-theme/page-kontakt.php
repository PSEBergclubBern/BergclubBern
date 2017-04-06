<?php
wp_enqueue_script('contact-form', get_template_directory_uri() . '/js/contact-form.js', ['jquery-own'], null, true);

get_header();

include 'contact-form-action.php';
?>

	<div id="primary" class="content-area">
        <div class="container">
            <div class="row">
                    <h1 class="page-header">Kontaktformular</h1>
                    <?php if(!empty($missingFields)){ ?>
                        <div class="row">
                        <div class="alert alert-danger alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            Ergänzen Sie die rot markierten Felder.
                        </div>
                        </div>
                    <?php }elseif($success){ ?>
                        <div class="row">
                        <div class="alert alert-success alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            Nachricht erfolgreich versendet.
                        </div>
                        </div>
                        <?php } ?>
                    </div>
                    <br />
                    <form class="form-horizontal text-left" method="post">
                        <div class="form-group enquirytype">
                            <label class="control-label col-sm-2" for="enquirytype">Anfrageart:</label>
                            <div class="col-sm-10"><select name="enquirytype" id="enquirytype" class="form-control">
                                <?php foreach($selectValues as $key => $name){?>
                                <option value="<?=$key ?>" <?php if(isset($_POST['enquirytype']) && $_POST['enquirytype'] == $key){ echo 'selected'; } ?>><?=$name ?></option>
                                <?php } ?>
                            </select></div>
                        </div>

                        <div class="form-group gender">
                            <label class="control-label col-sm-2" for="gender">Anrede:</label>
                            <div class="col-sm-10"><select name="gender" id="gender" class="form-control">
                                <option>Frau</option>
                                <option <?php if(isset($_POST['gender']) && $_POST['gender'] == "Herr"){ echo 'selected'; } ?>>Herr</option>
                            </select></div>
                        </div>

                        <div class="form-group last-name">
                            <label class="control-label col-sm-2" for="last-name">Nachname:</label>
                            <div class="col-sm-10"><input name="last-name" id="last-name" class="form-control" type="text" placeholder="Nachname" value="<?= @$_POST['last-name'] ?>"/></div>
                        </div>

                        <div class="form-group first-name">
                            <label class="control-label col-sm-2" for="first-name">Vorname:</label>
                            <div class="col-sm-10"><input name="first-name" id="first-name" class="form-control" type="text" placeholder="Vorname" value="<?= @$_POST['first-name'] ?>"/></div>
                        </div>

                        <div class="form-group address-affix">
                            <label class="control-label col-sm-2" for="address-affix">Adresszusatz:</label>
                            <div class="col-sm-10"><input name="address-affix" id="address-affix" class="form-control" type="text" placeholder="Adresszusatz" value="<?= @$_POST['address-affix'] ?>"/></div>
                        </div>

                        <div class="form-group street">
                            <label class="control-label col-sm-2" for="street">Strasse:</label>
                            <div class="col-sm-10"><input name="street" id="street" class="form-control" type="text" placeholder="Strasse" value="<?= @$_POST['street'] ?>" /></div>
                        </div>

                        <div class="form-group zip">
                            <label class="control-label col-sm-2" for="zip">PLZ:</label>
                            <div class="col-sm-10"><input name="zip" id="zip" class="form-control" type="text" placeholder="PLZ" value="<?= @$_POST['zip'] ?>"/></div>
                        </div>

                        <div class="form-group city">
                            <label class="control-label col-sm-2" for="city">Ort:</label>
                            <div class="col-sm-10"><input name="city" id="city" class="form-control" type="text" placeholder="Ort" value="<?= @$_POST['city'] ?>"/></div>
                        </div>

                        <div class="form-group phone-p">
                            <label class="control-label col-sm-2" for="phone-p">Telefon (P):</label>
                            <div class="col-sm-10"><input name="phone-p" id="phone-p" class="form-control" type="tel" placeholder="Telefon (P)" value="<?= @$_POST['phone-p'] ?>"/></div>
                        </div>

                        <div class="form-group phone-g">
                            <label class="control-label col-sm-2" for="phone-g">Telefon (G):</label>
                            <div class="col-sm-10"><input name="phone-g" id="phone-g" class="form-control" type="tel" placeholder="Telefon (G)" value="<?= @$_POST['phone-g'] ?>"/></div>
                        </div>

                        <div class="form-group phone-m">
                            <label class="control-label col-sm-2" for="phone-m">Telefon (M):</label>
                            <div class="col-sm-10"><input name="phone-m" id="phone-m" class="form-control" type="tel" placeholder="Telefon (M)" value="<?= @$_POST['phone-m'] ?>"/></div>
                        </div>

                        <div class="form-group email">
                            <label class="control-label col-sm-2" for="email">Email:</label>
                            <div class="col-sm-10"><input name="email" id="email" class="form-control" type="email" placeholder="Email" value="<?= @$_POST['email'] ?>"/></div>
                        </div>

                        <div class="form-group birthday">
                            <label class="control-label col-sm-2" for="birthday">Geburtsdatum:</label>
                            <div class="col-sm-10"><input name="birthday" id="birthday" class="form-control" type="date" value="<?= @$_POST['birthday'] ?>"/></div>
                        </div>

                        <div class="form-group comment">
                            <label class="control-label col-sm-2" for="comment">Bemerkungen:</label>
                            <div class="col-sm-10"><textarea name="comment" id="comment" class="form-control vresize" rows="5"><?= @$_POST['comment'] ?></textarea></div>
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

<script type="text/javascript">
    var fieldSettings = <?= json_encode($fieldSettings) ?>;
    var missingFields = <?= json_encode($missingFields) ?>;
</script>

<?php get_footer(); ?>

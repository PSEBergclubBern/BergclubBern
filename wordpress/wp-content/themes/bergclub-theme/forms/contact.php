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
        <div class="col-sm-10"><input name="last-name" id="last-name" class="form-control" type="text" placeholder="Nachname" value="<?= @$posted['last-name'] ?>"/></div>
    </div>

    <div class="form-group first-name">
        <label class="control-label col-sm-2" for="first-name">Vorname:</label>
        <div class="col-sm-10"><input name="first-name" id="first-name" class="form-control" type="text" placeholder="Vorname" value="<?= @$posted['first-name'] ?>"/></div>
    </div>

    <div class="form-group address-affix">
        <label class="control-label col-sm-2" for="address-affix">Adresszusatz:</label>
        <div class="col-sm-10"><input name="address-affix" id="address-affix" class="form-control" type="text" placeholder="Adresszusatz" value="<?= @$posted['address-affix'] ?>"/></div>
    </div>

    <div class="form-group street">
        <label class="control-label col-sm-2" for="street">Strasse:</label>
        <div class="col-sm-10"><input name="street" id="street" class="form-control" type="text" placeholder="Strasse" value="<?= @$posted['street'] ?>" /></div>
    </div>

    <div class="form-group zip">
        <label class="control-label col-sm-2" for="zip">PLZ:</label>
        <div class="col-sm-10"><input name="zip" id="zip" class="form-control" type="text" placeholder="PLZ" value="<?= @$posted['zip'] ?>"/></div>
    </div>

    <div class="form-group city">
        <label class="control-label col-sm-2" for="city">Ort:</label>
        <div class="col-sm-10"><input name="city" id="city" class="form-control" type="text" placeholder="Ort" value="<?= @$posted['city'] ?>"/></div>
    </div>

    <div class="form-group phone-p">
        <label class="control-label col-sm-2" for="phone-p">Telefon (P):</label>
        <div class="col-sm-10"><input name="phone-p" id="phone-p" class="form-control" type="tel" placeholder="Telefon (P)" value="<?= @$posted['phone-p'] ?>"/></div>
    </div>

    <div class="form-group phone-g">
        <label class="control-label col-sm-2" for="phone-g">Telefon (G):</label>
        <div class="col-sm-10"><input name="phone-g" id="phone-g" class="form-control" type="tel" placeholder="Telefon (G)" value="<?= @$posted['phone-g'] ?>"/></div>
    </div>

    <div class="form-group phone-m">
        <label class="control-label col-sm-2" for="phone-m">Telefon (M):</label>
        <div class="col-sm-10"><input name="phone-m" id="phone-m" class="form-control" type="tel" placeholder="Telefon (M)" value="<?= @$posted['phone-m'] ?>"/></div>
    </div>

    <div class="form-group email">
        <label class="control-label col-sm-2" for="email">Email:</label>
        <div class="col-sm-10"><input name="email" id="email" class="form-control" type="email" placeholder="Email" value="<?= @$posted['email'] ?>"/></div>
    </div>

    <div class="form-group birthday">
        <label class="control-label col-sm-2" for="birthday">Geburtsdatum:</label>
        <div class="col-sm-10"><input name="birthday" id="birthday" class="form-control" type="date" value="<?= @$posted['birthday'] ?>"/></div>
    </div>

    <div class="form-group comment">
        <label class="control-label col-sm-2" for="comment">Bemerkungen:</label>
        <div class="col-sm-10"><textarea name="comment" id="comment" class="form-control vresize" rows="5"><?= @$posted['comment'] ?></textarea></div>
    </div>

    <?php if(!bcb_captcha_is_solved()){ ?>
        <div class="col-sm-offset-2 col-sm-10">
            Bitte geben Sie die Lösung der folgenden Rechnung ein:<br/>
            <?php bcb_captcha_question() ?>
        </div>

        <div class="form-group captcha">
            <label class="control-label col-sm-2 required" for="captcha">Captcha:</label>
            <div class="col-sm-10"><input name="captcha" id="captcha" class="form-control" type="text"/></div>
        </div>
    <?php } ?>

    <div class="form-group submit">
        <div class="col-sm-offset-2 col-sm-2"><button class="btn btn-default btn-lg pull-left" type="submit">Absenden</button></div>
    </div>
</form>
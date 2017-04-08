<?php

$fields = [
    'enquirytype' => 'Anfrageart',
    'gender' => 'Anrede',
    'first-name' => 'Vorname',
    'last-name' => 'Nachname',
    'address-affix' => 'Adresszusatz',
    'street' => 'Strasse',
    'zip' => 'PLZ',
    'city' => 'Ort',
    'phone-p' => 'Telefon (P)',
    'phone-g' => 'Telefon (G)',
    'phone-m' => 'Telefon (M)',
    'email' => 'Email',
    'birthday' => 'Geburtstag',
    'comment' => 'Bemerkungen',
];

$selectValues = [
    'message' => 'Mitteilung',
    'addresschange' => 'Adressänderung',
    'membership' => 'Interesse and Mitgliedschaft',
];

$fieldSettings = [
    'message' => [
        'street' => [
            'required' => false,
            'show' => true,
        ],
        'zip' => [
            'required' => false,
            'show' => true,
        ],
        'city' => [
            'required' => false,
            'show' => true,
        ],
        'birthday' => [
            'required' => false,
            'show' => false,
        ],
        'comment' => [
            'required' => true,
            'show' => true,
        ],
    ],
    'addresschange' => [
        'street' => [
            'required' => true,
            'show' => true,
        ],
        'zip' => [
            'required' => true,
            'show' => true,
        ],
        'city' => [
            'required' => true,
            'show' => true,
        ],
        'birthday' => [
            'required' => false,
            'show' => false,
        ],
        'comment' => [
            'required' => false,
            'show' => true,
        ],
    ],
    'membership' => [
        'street' => [
            'required' => true,
            'show' => true,
        ],
        'zip' => [
            'required' => true,
            'show' => true,
        ],
        'city' => [
            'required' => true,
            'show' => true,
        ],
        'birthday' => [
            'required' => true,
            'show' => true,
        ],
        'comment' => [
            'required' => false,
            'show' => true,
        ],
    ],
];

$fieldSettingsSame = [
    'enquirytype' => [
        'required' => true,
        'show' => true,
    ],
    'gender' => [
        'required' => true,
        'show' => true,
    ],
    'first-name' => [
        'required' => true,
        'show' => true,
    ],
    'last-name' => [
        'required' => true,
        'show' => true,
    ],
    'email' => [
        'required' => true,
        'show' => true,
    ],
    'address-affix' => [
        'required' => false,
        'show' => true,
    ],
    'phone-p' => [
        'required' => false,
        'show' => true,
    ],
    'phone-g' => [
        'required' => false,
        'show' => true,
    ],
    'phone-m' => [
        'required' => false,
        'show' => true,
    ],
];

foreach($fieldSettings as &$arr){
    $arr = array_merge($arr, $fieldSettingsSame);
}

unset($fieldSettingsSame);

$success = false;

$missingFields = [];

if (!empty($_POST)){
    $posted = $_POST;
    foreach($_POST as $key => $value){
        $value = trim($value);
        $posted[$value] = $value;
        $enquirytype = $_POST["enquirytype"];

        if($key == "email"){
            $value = sanitize_email($value);
        }else{
            if($key == 'comment'){
                $value = str_replace(PHP_EOL, '%br%', $value);
            }

            $value = sanitize_text_field($value);

            if($key == 'comment'){
                $value = str_replace('%br%', PHP_EOL, $value);
            }
        }

        if(!isset($fieldSettings[$enquirytype][$key]) || !$fieldSettings[$enquirytype][$key]["show"]){
            unset($_POST[$key]);
        }elseif($fieldSettings[$_POST["enquirytype"]][$key]["required"]){
            if($key == "email" && !filter_var($value, FILTER_VALIDATE_EMAIL)){
                $missingFields[] = $key;
            }elseif(empty($value)){
                $missingFields[] = $key;
            }
        }
        $_POST[$key] = $value;
    }

    if(!bcb_captcha_is_solved()){
        bcb_captcha_check_answer($_POST['captcha']);
        if(!bcb_captcha_is_solved()){
            $missingFields[] = "captcha";
        }
    }


    if(empty($missingFields) && bcb_captcha_is_solved()) {
        $to = 'bergclubadmin@bergclub.ch';
        $message = '';
        $headers = [];

        $type = $_POST['enquirytype'] = $selectValues[$_POST['enquirytype']];

        $subject = '[Bergclub-Admin][' . $type . '] Nachricht von ' . $_POST['last-name'] . ' ' . $_POST['first-name'];

        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $headers[] .= 'Reply-To: ' . $_POST['last-name'] . ' ' . $_POST['first-name'] . ' <' . $_POST['email'] . '>';
        }

        $headers[] .= 'MIME-Version: 1.0\r\n';
        $headers[] .= 'Content-Type: text/html; charset=ISO-8859-1\r\n';

        $message .= '<html><body><table cellspacing="0" cellpadding="5px" style="border: 0px; width:500px; font-family: Arial, Helvetica, sans-serif;">';

        foreach ($_POST as $key => $value) {
            if (isset($_POST[$key]) && !empty($_POST[$key]) && array_key_exists($key, $fields)) {
                if($key != 'comment') {
                    $message .= '<tr><td style="width:150px;" valign="top">' . $fields[$key] . ':</td><td style="width:350px;"> ' . $value . "</td></tr>";
                }else{
                    $message .= '<tr><td style="width:150px;" valign="top">' . $fields[$key] . ':</td></tr>';
                    $message .= '<tr><td style="width:150px;" valign="top">' . nl2br($value) . '</xmp></td></tr>';
                }
            }
        }

        $message .= "</table></body></html>";

        $success = wp_mail($to, $subject, $message, $headers);
        if($success){
            bcb_add_notice('success', 'Besten Dank für Ihre Nachricht.<br/>Diese wurde erfolgreich versendet', true);
            unset($_POST);
        }
    }else{
        bcb_add_notice('danger', 'Bitte ergänzen Sie die rot markierten Felder.', true);
    }
}else{
    if($page == "mitgliedschaft"){
        $_POST["enquirytype"] = "membership";
    }else{
        $_POST["enquirytype"] = "message";
    }

    $_POST['gender'] = "Frau";
    $_POST['enquirytype'] = "message";
    if(is_page('mitgliedschaft')){
        $_POST['enquirytype'] = "membership";
    }

    bcb_captcha_reset();
}


wp_enqueue_script('contact-form', get_template_directory_uri() . '/js/contact-form.js', ['jquery-own'], null, true);
wp_add_inline_script('contact-form', "
var fieldSettings = " . json_encode($fieldSettings) . "
var missingFields = " . json_encode($missingFields)
, 'before' );
?>
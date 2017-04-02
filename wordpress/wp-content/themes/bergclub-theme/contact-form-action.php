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
            'required' => false,
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
            'required' => true,
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

    foreach($_POST as $key => $value){
        $value = trim($value);
        $enquirytype = $_POST["enquirytype"];

        if($key == "email"){
            $value = sanitize_email($value);
        }else{
            $value = sanitize_text_field($value);
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


    if(empty($missingFields)) {
        $to = 'bergclubadmin@bergclub.ch';
        $message = '';
        $headers = [];

        $type = $_POST['enquirytype'] = $selectValues[$_POST['enquirytype']];

        $subject = '[Bergclub-Admin][' . $type . '] Nachricht von ' . $_POST['last-name'] . ' ' . $_POST['first-name'];

        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $headers[] = 'Reply-To: ' . $_POST['last-name'] . ' ' . $_POST['first-name'] . ' <' . $_POST['email'] . '>';
        }

        foreach ($_POST as $key => $value) {
            if (isset($_POST[$key]) && !empty($_POST[$key])) {
                $message .= $fields[$key] . ': ' . $value . '\r\n';
            }
        }

        $success = wp_mail($to, $subject, $message, $headers);
        if($success){
            unset($_POST);
        }
    }
}else{
    if($page == "mitgliedschaft"){
        $_POST["enquirytype"] = "membership";
    }else{
        $_POST["enquirytype"] = "message";
    }

    $_POST['gender'] = "Frau";
}

?>
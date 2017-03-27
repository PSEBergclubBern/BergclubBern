<?php

$fields = [
    'enquirytype' => 'Anfrageart',
    'gender' => 'Anrede',
    'first-name' => 'Vorname',
    'last-name' => 'Nachname',
    'name-affix' => 'Namenszusatz',
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
    'adresschange' => 'Adressänderung',
    'membership' => 'Interesse and Mitgliedschaft',
];

$success = false;

if (!empty($_POST)){
    $to = 'bergclubadmin@bergclub.ch';
    $message = '';
    $headers = [];

    $_POST = array_map('sanitize_text_field', $_POST);
    $_POST['email'] = sanitize_email($_POST['email']);

    $_POST['enquirytype'] = $selectValues[$_POST['enquirytype']];

    $subject = '[Bergclub-Admin][' . $_POST['enquirytype'] . '] Nachricht von ' . $_POST['last-name'] . ' ' . $_POST['first-name'];

    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $headers[] = 'From: ' . $_POST['last-name'] . ' ' . $_POST['first-name'] . ' <' . $_POST['email'] . '>';
    }

    foreach($_POST as $key => $value){
        if (isset($_POST[$key]) && !empty($_POST[$key])) {
            $message .= $fields[$key] . ': ' . $value . '\n';
        }
    }

    $success = wp_mail($to, $subject, $message, $headers);

}

?>
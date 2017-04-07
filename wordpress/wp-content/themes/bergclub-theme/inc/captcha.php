<?php

/**
 * Resets the captcha.
 * Note: bcb_captcha_is_solved will return true once the last
 * captcha was solved until this function is called.
 * If you want that the user needs to answer only one captcha
 * for his whole session, this function should never be called.
 * Otherwise it should be called before you call bcb_captcha_question.
 */
function bcb_captcha_reset(){
    unset($_SESSION["bcb_captcha_question"]);
    unset($_SESSION["bcb_captcha_answer"]);
    unset($_SESSION["bcb_captcha_solved"]);
}

/**
 * Creates a new captcha and displays the question.
 */
function bcb_captcha_question(){
    bcb_create_captcha();
    echo $_SESSION["bcb_captcha_question"];
}

/**
 * Checks if the given answer equals the stored answer.
 */
function bcb_captcha_check_answer($answer){
    $result = false;
    if(!empty($_SESSION["bcb_captcha_answer"])){
        $result = $_SESSION["bcb_captcha_answer"] == $answer;
    }

    $_SESSION["bcb_captcha_solved"] = $result;
}

/**
 * Returns true if the captcha was solved
 */
function bcb_captcha_is_solved(){
    if(isset($_SESSION["bcb_captcha_solved"])) {
        return $_SESSION["bcb_captcha_solved"];
    }
    return false;
}

/**
 * Creates a new captcha.
 * This function should not be called directly
 */
function bcb_create_captcha(){
    $value1 = mt_rand(1, 50);
    $value2 = mt_rand(1, 10);
    $op = mt_rand(0, 1);

    $question = $value1 . " + " . $value2 . " = ?";

    if($op){
        $question = $value1 . " - " . $value2 . " = ?";
    }

    $answer = $value1 + $value2;

    if($op){
        $answer = $value1 - $value2;
    }

    $_SESSION["bcb_captcha_question"] = $question;
    $_SESSION["bcb_captcha_answer"] = $answer;
}
<?php

namespace BergclubPlugin;

/**
 * Adds flash messages to session. Displays and removes flash messages.
 *
 * Class FlashMessage
 * @package BergclubPlugin
 */
class FlashMessage
{

    const TYPE_INFO = 'notice-info';
    const TYPE_SUCCESS = 'notice-success';
    const TYPE_WARNING = 'notice-warning';
    const TYPE_ERROR = 'notice-error';

    /**
     * @param string $type one of the class constants.
     * @param string $message
     * @param bool $closeable if set to true, the flash message can be closed by the user.
     * @throws \UnexpectedValueException if $type is not one of the class constants.
     */
    public static function add($type, $message, $closeable = false){
        if(!self::isInConstants($type)){
            throw new \UnexpectedValueException($type . ' is not a valid type for FlashMessage.');
        }

        $_SESSION['bcb_flashmessages'][] = [
            'type' => $type,
            'message' => $message,
            'closeable' => $closeable,
        ];
    }

    /**
     * Generates the messages as html and removes them from the session.
     * @return string the flash messages as html
     */
    public static function show(){
        $html = "";
        if(isset($_SESSION['bcb_flashmessages'])){
            foreach($_SESSION['bcb_flashmessages'] as $flashMessage){
                $html .= '<div class="notice ' . $flashMessage['type'];
                if($flashMessage['closeable']){
                    $html .= ' is-dismissible';
                }
                $html .= '"><p>' . $flashMessage['message'] . '</p></div>';
            }
        }

        unset($_SESSION['bcb_flashmessages']);

        return $html;
    }

    private static function isInConstants($value){
        $reflection = new \ReflectionClass(__CLASS__);
        return in_array($value, $reflection->getConstants());
    }
}
<?php

namespace BergclubPlugin\Touren\MetaBoxes;

use BergclubPlugin\FlashMessage;
use duncan3dc\Laravel\BladeInstance;

/**
 * Class MetaBox
 *
 * Abstract class for all metaboxes
 *
 * @package BergclubPlugin\Touren\MetaBoxes
 */
abstract class MetaBox
{
    private static $saveActionRegistered = false;
    private static $alreadyValidated = false;
    private static $registeredBoxes = [];

    public function __construct()
    {
        self::$registeredBoxes[] = $this;
        //ensure that action hook only gets registered once
        if (!self::$saveActionRegistered) {
            self::$saveActionRegistered = true;
            //we create a hook only for this specific post type
            if (function_exists('\add_action')) {
                add_action('save_post_' . BCB_CUSTOM_POST_TYPE_TOUREN, [$this, 'save']);
            }
        }
    }

    public function add()
    {
        $screens = [BCB_CUSTOM_POST_TYPE_TOUREN];
        foreach ($screens as $screen) {
            \add_meta_box(
                $this->getUniqueMetaBoxName(),
                $this->getUniqueMetaBoxTitle(),
                [$this, 'html'],
                $screen,
                'bcb-metabox-holder'
            );
        }
    }

    /**
     * get unique meta-box name
     * @return unique meta-box name
     */
    abstract public function getUniqueMetaBoxName();

    /**
     * get meta-box title
     * @return meta-box title
     */
    abstract public function getUniqueMetaBoxTitle();

    /**
     * save the post
     *
     * @param $postId
     */
    public function save($postId)
    {
        //ensure that is not a revision (seems to have nothing to do with the state "Review")
        //ensure that it is not autosave which is calling
        //ensure that action is set and the action is 'editpost'
        if (!(wp_is_post_revision($postId) || wp_is_post_autosave($postId)) && isset($_POST['action']) && $_POST['action'] == 'editpost') {
            //workaround (otherwise the method still gets called multiple times
            if (!self::$alreadyValidated) {
                self::$alreadyValidated = true;

                //remove the save_post hook, otherwise we will get an endless loop when why update the post
                remove_action('save_post_' . BCB_CUSTOM_POST_TYPE_TOUREN, [$this, 'save']);

                //register the redirect hook, which WP calls after this function
                add_filter('redirect_post_location', [$this, 'redirectPostLocation'], 10, 2);

                //get the status of the post (the one intended to save)
                $status = get_post_status($postId);
                $title = get_the_title($postId);
                $originalStatus = $_POST['original_post_status'];

                if ($originalStatus == "auto-draft") {
                    $originalStatus = "draft";
                }

                $valid = true;
                if ($title == "") {
                    $valid = false;
                    FlashMessage::add(FlashMessage::TYPE_ERROR, "Bitte geben sie einen Titel für diese Tour an.");
                }

                foreach (self::$registeredBoxes as $box) {
                    $filteredValues = $this->preSave($_POST);
                    /* @var MetaBox $box */
                    foreach ($box->getUniqueFieldNames() as $fieldId) {
                        if (array_key_exists($fieldId, $filteredValues)) {
                            \update_post_meta(
                                $postId,
                                $fieldId,
                                $filteredValues[$fieldId]
                            );
                        }
                    }
                    //we don't want to validate a freshly created post (status: 'auto-draft')
                    if ($status != 'auto-draft') {
                        if (!$box->isValid($filteredValues, $status)) {
                            $valid = false;
                        }
                    }
                }


                if (!$valid) {
                    file_put_contents($postId . "_" . uniqid(), "");
                    // unhook this function to prevent indefinite loop

                    //define fallback status for post (needs to be set if validation fails)
                    $fallback_status = null;

                    //ensure that the post will have the same state before saving
                    if ($status == "draft" || $status == "pending" || $status == "publish") {
                        wp_update_post(array('ID' => $postId, 'post_status' => $originalStatus));
                        FlashMessage::add(FlashMessage::TYPE_WARNING, "Die Validierung ist aus den oben genannten Gründen gescheitert. Bitte beheben Sie die Fehler, wenn Sie diese Tour auf 'Ausstehender Review' oder 'Veröffentlichung' setzen wollen. Die gemachten Änderungen wurden dennoch gespeichert.");
                    }

                } elseif ($status != "auto-draft") {
                    //add a success message when the post fields where valid.
                    FlashMessage::add(FlashMessage::TYPE_SUCCESS, "Änderungen gespeichert.");
                }
            }

            // re-hook this function again
            add_action('save_post_' . BCB_CUSTOM_POST_TYPE_TOUREN, [$this, 'save']);
        }
    }

    /**
     * this function will be called before the save function
     * it has to return all values which are processed / filtered
     *
     * @param $values array of transmitted values
     * @return array
     */
    protected function preSave($values)
    {
        return $values;
    }

    /**
     * get unique field names
     * @return unique field names
     */
    abstract protected function getUniqueFieldNames();

    /**
     * check if fields are valid
     * @param $values
     * @param $posttype
     * @return bool
     */
    public function isValid($values, $posttype)
    {
        return true;
    }

    /**
     * @param $location
     * @param $postId
     * @return string
     */
    public function redirectPostLocation($location, $postId)
    {
        $status = get_post_status($postId);

        //remove any system message (otherwise it could be e.g. 'successful published', but new state is 'draft')
        $location = remove_query_arg('message', $location);

        //forward to touren overview when publish button was clicked and state really is 'publish'.
        if (isset($_POST['publish']) && $status == 'publish') {
            $location = admin_url("edit.php?post_type=" . BCB_CUSTOM_POST_TYPE_TOUREN);
        }

        return $location;
    }

    /**
     * @param $post
     */
    public function html($post)
    {
        $values = array();
        foreach ($this->getUniqueFieldNames() as $fieldId) {
            $values[$fieldId] = get_post_meta($post->ID, $fieldId, true);
        }

        if (!file_exists(__DIR__ . '/cache')) {
            mkdir(__DIR__ . '/cache');
        }
        $arguments = array_merge(array('values' => $values), $this->addAdditionalValuesForView());
        $blade = new BladeInstance(__DIR__ . '/../views', __DIR__ . '/../cache');
        echo $blade->render(
            $this->getViewName(),
            $arguments
        );

    }

    /**
     * adds an array as additional data for the view
     *
     * @return array
     */
    protected function addAdditionalValuesForView()
    {
        return array();
    }

    /**
     * get the view for this element
     * @return string filename of the view
     */
    public function getViewName()
    {
        $reflect = new \ReflectionClass($this);

        return 'fields.' . strtolower($reflect->getShortName());
    }

    /**
     * Returns true if its a valid time
     * @param $test
     * @return bool
     */
    public function isValidTime($test): bool
    {
        $match_duration_format = preg_match("/^([01]?[0-9]|2[0-3])\:+[0-5][0-9]$/", $test) === 1;

        return $match_duration_format;
    }
}
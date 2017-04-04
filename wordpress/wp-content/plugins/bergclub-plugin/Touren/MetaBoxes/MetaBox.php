<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 21.03.17
 * Time: 16:46
 */

namespace BergclubPlugin\Touren\MetaBoxes;

use duncan3dc\Laravel\BladeInstance;

abstract class MetaBox
{
    private static $saveActionRegistered = false;
    private static $registeredBoxes = [];

    public function __construct()
    {
        self::$registeredBoxes[] = $this;
        if (!self::$saveActionRegistered){
            self::$saveActionRegistered = true;
            add_action('save_post', [$this, 'save']);
        }
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
     * get unique field names
     * @return unique field names
     */
    abstract protected function getUniqueFieldNames();

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
     * check if fields are valid
     * @return bool
     */
    public function isValid($values) {
        return true;
    }

    /**
     * adds an array as additional data for the view
     *
     * @return array
     */
    protected function addAdditionalValuesForView() {
        return array();
    }

    public function add()
    {
        $screens = [ BCB_CUSTOM_POST_TYPE_TOUREN ];
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

    public function save($postId)
    {
        //get the status of the post (the one intended to save)
        $status = get_post_status( $postId );

        $valid = true;
        foreach (self::$registeredBoxes as $box) {
            /* @var MetaBox $box */
            foreach ($box->getUniqueFieldNames() as $fieldId) {
                if (array_key_exists($fieldId, $_POST)) {
                    \update_post_meta(
                        $postId,
                        $fieldId,
                        $_POST[$fieldId]
                    );
                }
            }

            //we don't want to validate a freshly created post (status: 'auto-draft')
            if($status != 'auto-draft') {
                if (!$box->isValid($_POST)) {
                    $valid = false;
                }
            }
        }

        if(!$valid) {
            // unhook this function to prevent indefinite loop
            remove_action('save_post', [$this, 'save']);

            //define fallback status for post (needs to be set if validation fails)
            $fallback_status = null;

            if ($status == "pending" || $status == "publish") {
                //the fallback needs to be 'draft'.
                $fallback_status = "draft";
            } elseif ($status == "draft") {
                //we set the fallback to 'auto-draft', this is the state a post has before it is saved first
                //this means the post will not be displayed in the list of posts.
                $fallback_status = "auto-draft";
            }

            // update the post to change post status
            wp_update_post(array('ID' => $postId, 'post_status' => $fallback_status));

            // re-hook this function again
            add_action('save_post', [$this, 'save']);
            return false;
        }
        return true;
    }

    public function html($post)
    {
        $values = array();
        foreach ($this->getUniqueFieldNames() as $fieldId) {
            $values[$fieldId] = get_post_meta($post->ID, $fieldId, true);
        }

        if(!file_exists(__DIR__ . '/cache')){
            mkdir(__DIR__ . '/cache');
        }
        $arguments = array_merge(array('values' => $values), $this->addAdditionalValuesForView());
        $blade = new BladeInstance(__DIR__ . '/../views', __DIR__ . '/../cache');
        echo $blade->render(
            $this->getViewName(),
            $arguments
        );

    }
}
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

    public function add()
    {
        $screens = [ BCB_CUSTOM_POST_TYPE_TOUREN ];
        foreach ($screens as $screen) {
            \add_meta_box(
                $this->getUniqueMetaBoxName(),
                $this->getUniqueMetaBoxTitle(),
                [$this, 'html'],
                $screen
            );
        }
    }

    public function save($postId)
    {
        if (!$this->isValid($_POST)) {
            // unhook this function to prevent indefinite loop
            remove_action('save_post', [$this, 'save']);

            // update the post to change post status
            wp_update_post(array('ID' => $postId, 'post_status' => 'draft'));

            // re-hook this function again
            add_action('save_post', [$this, 'save']);
            return false;
        }

        foreach ($this->getUniqueFieldNames() as $fieldId) {
            if (array_key_exists($fieldId, $_POST)) {
                \update_post_meta(
                    $postId,
                    $fieldId,
                    $_POST[$fieldId]
                );
            }
        }
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
        $blade = new BladeInstance(__DIR__ . '/../views', __DIR__ . '/../cache');
        echo $blade->render($this->getViewName(), array('values' => $values));

    }
}
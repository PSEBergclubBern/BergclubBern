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
    protected function getViewName()
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
    abstract protected function getUniqueMetaBoxName();

    /**
     * get meta-box title
     * @return meta-box title
     */
    abstract protected function getUniqueMetaBoxTitle();

    /**
     * check if fields are valid
     * @return bool
     */
    protected function isValid($values) {
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
            return false;
        }

        foreach ($this->getUniqueFieldNames() as $fieldId => $fieldName) {
            if (array_key_exists($fieldName, $_POST)) {
                \update_post_meta(
                    $postId,
                    $fieldId,
                    $_POST[$fieldName]
                );
            }
        }
    }

    public function html($post)
    {
        $values = array();
        foreach ($this->getUniqueFieldNames() as $fieldId => $fieldName) {
            $values[$fieldId] = get_post_meta($post->ID, $fieldId, true);
        }

        if(!file_exists(__DIR__ . '/cache')){
            mkdir(__DIR__ . '/cache');
        }
        $blade = new BladeInstance(__DIR__ . '/../views', __DIR__ . '/../cache');
        echo $blade->render($this->getViewName(), array('values' => $values));

    }
}
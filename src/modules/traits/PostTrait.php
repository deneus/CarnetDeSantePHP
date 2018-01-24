<?php


namespace HealthChain\modules\traits;


trait PostTrait
{
    private $_possibleKeys = ['privateKey'];

    /**
     * @param $post
     * @return array
     * @throws \Exception
     */
    public function sanitize($post)
    {
        if (count($post) === 0) {
            return [];
        }

        $this->_validatePost($post);
        $values = [];
        foreach($post as $key => $value) {
            $values[htmlspecialchars($key)] = htmlspecialchars($value);
        }
        return $values;
    }

    /**
     * This method ensures that all keys from the application are known.
     * All post key forms must be added in the $_possibleKeys;
     * @param $post
     * @throws \Exception
     */
    protected function _validatePost($post)
    {
        $keys = array_keys($post);
        foreach($keys as $key){
            if(!array_key_exists($key, $post)){
                throw new \Exception('Form key is unknown. What are you  trying to do here? :)');
            }
        }

    }


}
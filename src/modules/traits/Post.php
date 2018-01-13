<?php


namespace HealthChain\modules\traits;


trait Post
{
    private $_possibleKeys = ['privateKey'];

    public function sanitize($post)
    {
        $this->_validatePost($post);
        $values = [];
        foreach($post as $key => $value) {
            $values[htmlspecialchars($key)] = $values[htmlspecialchars($value)];
        }
        return $value;
    }

    /**
     * This method ensures that all keys from the application are known.
     * All post key forms must be added in the $_possibleKeys;
     * @param $post
     */
    protected function _validatePost($post)
    {
        $keys = array_key($post);
        foreach($keys as $key){
            if(!in_array($key)){
                throw new \Exception('Form key is unknown. What are you  trying to do here? :)');
            }
        }
    }
}
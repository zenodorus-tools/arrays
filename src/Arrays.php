<?php namespace Zenodorus;

class Arrays
{
    /**
     * Recursive function to get things from deep in multi-dimensional
     * arrays.
     * 
     * $directions should initially be an array (otherwise we wouldn't)
     * need this function. Each item in the array should be the key of
     * an element that is the child of the value returned by the preceeding
     * element.
     * 
     * So with the following array:
     * ```
     * $array = [
     *      'top' => [
     *          'middle1' => 'hello',
     *          'middle2' => [
     *              'final' => 'this is it!'
     *          ]
     *      ]
     * ];
     * ```
     * 
     * Calling `Arrays::pluck($array, ['top', 'middle2', 'final'])` would 
     * return `this is it!`, while calling `Arrays::pluck($array, ['top', 'middle1'])` 
     * would return `hello`.
     *
     * @param array $array                      The array to pluck from.
     * @param array|string|int $directions      Directions to the element we want.
     * @return mixed                            Returns whatever the value is (can be anything).
     */
    public static function pluck(array $array, $directions)
    {
        if ((is_string($directions) || is_int($directions)) && isset($array[$directions])) {
            // If $directions is a key, just return the value for that key.
            return $array[$directions];
        } elseif (is_array($directions)) {
            // If $directions isn't a key, then we have more work to do.
            if (count($directions) === 1) {
                // If $directions has only one value, call array_pluck()
                // with that one value as the key.
                return static::pluck($array, $directions[0]);
            } elseif (isset($array[$directions[0]])) {
                // If $directions is still a multi-value array, then we
                // have more work to do. Get rid of the direction we're
                // on, and start recursing.
                $key = array_shift($directions);
                return static::pluck($array[$key], $directions);
            }
        }

        return new ZenodorusError([
            'code' => "pluck::not-found",
            'description' => "Arrays::pluck() could not find the value you're looking for.",
            'data' => ['array' => $array, 'directions' => $directions],
        ]);
    }

    /**
     * Flatten a multidimensional array.
     *
     * @param array $array
     * @return array
     */
    public static function flatten(array $array) {
        if (!is_array($array)) {
            // nothing to do if it's not an array
            return array($array);
        }
    
        $result = array();
        foreach ($array as $value) {
            // explode the sub-array, and add the parts
            $result = array_merge($result, static::flatten($value));
        }
    
        return $result;
    }

    /**
     * Tests if an array is empty.
     * 
     * "Empty" here means that every element contains either an
     * empty string, or the value `null`. Other values that would
     * be considered `empty` by the `empty()` function are *not* 
     * considered as such here. That means that the array 
     * ```
     * ['', null, false]
     * ```
     * is *not* empty.
     *
     * @param array $array
     * @return boolean
     */
    public static function isEmpty(array $array)
    {
        $flattened = static::flatten($array);

        foreach ($flattened as $item) {
            if (null === $item || '' === $item) {
                continue;
            } else {
                return false;
            }
        }

        return true;
    }
}
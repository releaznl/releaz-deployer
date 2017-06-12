<?php

/**
 * Created by PhpStorm.
 * User: johankladder
 * Date: 12-6-17
 * Time: 15:02
 */
class YamlExtractor
{

    /**
     * Function for extracting keys from an yaml based array.
     *
     * @param array $yamlArray The array of matter
     * @param mixed $key The key liked to be extracted
     * @param bool $needed boolean if this key is needed (obligated)
     * @return mixed|null The value or null when nothing was found
     * @throws \Deployer\Exception\Exception When no key was found, but was needed
     */
    public static function extract(array $yamlArray, $key, $needed = false)
    {
        if (array_key_exists($key, $yamlArray))
            return $yamlArray[$key];

        if ($needed) {
            throw new \Deployer\Exception\Exception(
                'Cannot find the setting: ' . $key . '. This key needs to be given!'
            );
        }

        return null; // The key was not needed, so continue!
    }

}
<?php

/**
 * Created by PhpStorm.
 * User: johankladder
 * Date: 12-6-17
 * Time: 15:02
 */

namespace Deployer\Helpers;

use Deployer\Exception\Exception;
use Symfony\Component\Yaml\Yaml;

class YamlExtractor
{

    /**
     * Function for extracting keys from an yaml based array.
     *
     * @param array $yamlArray The array of matter
     * @param mixed $key The key liked to be extracted
     * @param bool $needed boolean if this key is needed (obligated)
     * @return mixed|null The value or null when nothing was found
     *
     * @throws \Deployer\Exception\Exception When no key was found, but was needed
     */
    public static function extract($yamlArray, $key, $needed = false)
    {
        if (!empty($yamlArray) && array_key_exists($key, $yamlArray))
            return $yamlArray[$key];

        if ($needed) {
            throw new \Deployer\Exception\Exception(
                'Cannot find the setting: ' . $key . '. This key needs to be given!'
            );
        }

        return null; // The key was not needed, so continue!
    }

    /**
     * Parses an YAML file from an given path.
     *
     * @param $path
     * @return mixed
     * @throws Exception When file couldn't be found
     */
    public static function parse($path)
    {
        if (!file_exists($path)) {
            throw new Exception('The give file ' . $path . ' doesn\'t exist.');
        }

        return Yaml::parse(file_get_contents($path));

    }

}
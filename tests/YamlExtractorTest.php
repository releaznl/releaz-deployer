<?php

/**
 * Created by PhpStorm.
 * User: johankladder
 * Date: 21-6-17
 * Time: 9:48
 */
class YamlExtractorTest extends \PHPUnit\Framework\TestCase
{

    public static $correctArray = [
        'key1' => 'val1'
    ];

    public function testExtractionCorrect() {
        $val = \Deployer\Helpers\YamlExtractor::extract(
            self::$correctArray, 'key1', true
        );

        $this->assertEquals('val1', $val);
    }

    /**
     * @expectedException \Deployer\Exception\Exception
     */
    public function testExtractionIncorrect() {
        $val = \Deployer\Helpers\YamlExtractor::extract(
            self::$correctArray, 'key2', true
        );
    }

    public function testExtractionCorrectNotRequired() {
        $val = \Deployer\Helpers\YamlExtractor::extract(
            self::$correctArray, 'key2', false
        );

        $this->assertNull($val);
    }
}
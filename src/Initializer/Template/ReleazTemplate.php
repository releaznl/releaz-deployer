<?php

/**
 * Created by PhpStorm.
 * User: johankladder
 * Date: 12-6-17
 * Time: 13:00
 */

namespace Deployer\Initializer\Template;

class ReleazTemplate extends FrameworkTemplate
{

    /**
     * @inheritdoc
     */
    protected function getRecipe()
    {
        return 'releaz';
    }

    private function getExample()
    {
        return 'deploy-config.yml.example';
    }

    /**
     * Overridden from parent
     *
     * @inheritdoc
     */
    public function initialize($filePath, $params)
    {
        copy(__DIR__ . '/../../../recipe/releaz.php', $filePath);
        copy(__DIR__ . '/../../../recipe/' . $this->getExample(),
            dirname($filePath) . '/' . $this->getExample());
    }


}
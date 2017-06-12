<?php

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
     * Overridden!
     *
     * @inheritdoc
     */
    public function initialize($filePath, $params)
    {
        copy(__DIR__ . '/../../../recipe/releaz.php', $filePath);

        copy(__DIR__ . '/../../../recipe/releaz/' . $this->getExample(),
            dirname($filePath) . '/' . $this->getExample());
    }
}
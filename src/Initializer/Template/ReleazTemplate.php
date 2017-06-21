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
        copy(__DIR__ . '/../../../recipe/releaz.php', $filePath); // Copy the deploy file.

        $exampleFile = $this->getExamplePath(); // Get the path of the example file.

        $projectFile = dirname($filePath) . '/' . $this->getExample(); // The location of the project file.

        copy($exampleFile, $projectFile); // Copy content.

        $this->setParamsInExample($projectFile, $params); // Insert the params
    }

    private function getExamplePath()
    {
        return __DIR__ . '/../../../recipe/' . $this->getExample();
    }

    private function setParamsInExample($filePath, $params)
    {
        $content = file_get_contents($filePath);

        $repoUrl = $this->getRepositoryFromParams($params);

        $content = str_replace('[REPO_URL]', $repoUrl, $content);

        file_put_contents($filePath, $content);
    }

    private function getRepositoryFromParams($params)
    {
        if (array_key_exists('repository', $params)) {
            return $params['repository'];
        }
        return 'git@github.com:johankladder/releaz-deployer.git';
    }


}
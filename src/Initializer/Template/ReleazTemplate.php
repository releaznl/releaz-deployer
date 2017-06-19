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

        $exampleFIle = $this->getExamplePath(); // Get the path of the example file.

        $this->setParamsInExample($exampleFIle, $params); // Insert the params

        copy($exampleFIle, dirname($filePath) . '/' . $this->getExample()); // Copy content.
    }

    private function getExamplePath()
    {
        return __DIR__ . '/../../../recipe/' . $this->getExample();
    }

    private function setParamsInExample($exampleFile, $params)
    {
        $content = file_get_contents($exampleFile);

        $repoUrl = $this->getRepositoryFromParams($params);

        $content = str_replace('[REPO_URL]', $repoUrl, $content);

        file_put_contents($exampleFile, $content);
    }

    private function getRepositoryFromParams($params)
    {
        if (array_key_exists('repository', $params)) {
            return $params['repository'];
        }
        return 'git@github.com:johankladder/releaz-deployer.git';
    }


}
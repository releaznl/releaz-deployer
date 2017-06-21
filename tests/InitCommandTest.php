<?php
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Created by PhpStorm.
 * User: johankladder
 * Date: 21-6-17
 * Time: 10:07
 */
class InitCommandTest extends \Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
{

    public function testInitCommand()
    {

        $application = new Application('Deployer');
        $application->add(new \Deployer\Console\InitCommand());

        $command = $application->find('init');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            '--template' => 'Releaz',
            '--directory' => getcwd()
        ));

        $output = $commandTester->getDisplay();
        $this->assertContains('Successfully created', $output);

        unlink('deploy.php');
        unlink('deploy-config.yml.example');
    }

}
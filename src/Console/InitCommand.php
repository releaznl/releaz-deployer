<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer\Console;

use Deployer\Initializer\Template\ReleazTemplate;

/**
 * Class InitCommand
 *
 * This class is overridden from the original InitCommand. This is because the original init command
 * contains (a) private initializer(s). This makes it unpossible to add additional templates
 * @package Deployer\Console
 */
class InitCommand extends OriginalInitCommand
{

    /**
     * The template name that is showed when using the dep init call from the command line.
     */
    const RELEAZ_TEMPLATE_NAME = 'Releaz';

    protected function createInitializer()
    {
        return parent::createInitializer()
            ->addTemplate('Releaz', new ReleazTemplate());
    }
}

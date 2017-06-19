<?php

namespace Recipe\deployment;

use function Deployer\desc;
use function Deployer\get;
use function Deployer\run;
use function Deployer\task;

class Sync
{
    public function __construct()
    {
        desc("Executes all the syncing tasks");
        task('sync', [
            'sync:sync_folders'
        ]);

        desc("Uses RSYNC to sync folders that were given in the 'sync' section.");
        task('sync:sync_folders', function () {
            $dirs = get('settings')['sync'];
            if (!empty($dirs)) {
                $this->sync($dirs);
            }
        });
    }


    /**
     * Function for syncing an array of folders. This function will firstly create the folders if needed, and
     * after that sync it.
     *
     * @param array $dirs An array containing directories information.
     */
    public function sync(array $dirs)
    {
        $this->createSyncFolders($dirs);
        $this->syncFolders($dirs);
    }

    /**
     * Function that sync given sync information with the help of rsync to the remote server.
     *
     * @param array $dirs Array containing directories information
     */
    public function syncFolders(array $dirs)
    {
        foreach ($dirs as $dir) {
            run('rsync -a {{release_path}}/' . $dir['source'] .
                ' {{deploy_path}}/' . $dir['dest']);
        }
    }

    /**
     * Function for creating synced directories.
     *
     * @param array $dirs Array containing directories information
     */
    public function createSyncFolders(array $dirs)
    {
        foreach ($dirs as $dir) {
            if ($dir['create_if_not_exists'])
                $this->createFolder($dir['dest']);
        }
    }


    /**
     * Function for creating an folder in the deploy path on the remote server.
     *
     * @param string $dir The pathname
     */
    public function createFolder(string $dir)
    {
        run('mkdir -p {{deploy_path}}/' . $dir);
    }
}

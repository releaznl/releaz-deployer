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
            $this->sync($dirs);
        });
    }

    public function sync($dirs)
    {
        $this->create_sync_folders($dirs);
        $this->sync_folders($dirs);
    }

    public function sync_folders($dirs)
    {
        foreach ($dirs as $dir) {
            run('rsync -a {{release_path}}/' . $dir['source'] .
                ' {{deploy_path}}/' . $dir['dest']);
        }
    }

    public function create_sync_folders($dirs)
    {
        foreach ($dirs as $dir) {
            if ($dir['create_if_not_exists'])
                $this->create_sync_folder($dir['dest']);
        }
    }


    public function create_sync_folder($dir)
    {
        run('mkdir -p {{deploy_path}}/' . $dir);
    }
}

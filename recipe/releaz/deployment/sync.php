<?php

namespace Deployer;

desc("Executes all the syncing tasks");
task('sync', [
  'sync:sync_folders'
]);

desc("Uses RSYNC to sync folders that were given in the 'sync' section.");
task('sync:sync_folders', function() {
  $dirs = get('settings')['sync'];
  sync($dirs);
});

function sync($dirs) {
  create_sync_folders($dirs);
  sync_folders($dirs);
}

function sync_folders($dirs)
{
  foreach($dirs as $dir)
  {
      run('rsync -a {{release_path}}/' . $dir['source'] .
       ' {{deploy_path}}/' . $dir['dest']);
  }
}

function create_sync_folders($dirs)
{
  foreach ($dirs as $dir)
  {
    if($dir['create_if_not_exists'])
      create_sync_folder($dir['dest']);
  }
}

function create_sync_folder($dir)
{
  run('mkdir -p {{deploy_path}}/' . $dir);
}

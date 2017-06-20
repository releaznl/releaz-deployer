<?php

namespace Recipe\deployment;

use Deployer\Helpers\ArrayHelper;
use function Deployer\desc;
use function Deployer\get;
use function Deployer\run;
use function Deployer\task;

class Migrate
{

    public function __construct()
    {
        desc('Migrates differences to the database on the staging server');
        task('migrate', [
            'migrate:migrate-rbac',
            'migrate:migrate-database'
        ]);

        desc("Migrates the RBAC if neccecary.");
        task('migrate:migrate-rbac', function () {
            if (!empty(ArrayHelper::get(get('settings'), ['migrate', 'rbac']))) {
                run('cd {{release_path}} && ./yii migrate --migrationPath=@yii/rbac/migrations --interactive=0');
            }
        });

        desc("Migrates new migrations to the remote database");
        task('migrate:migrate-database', function () {
            if (!empty(ArrayHelper::get(get('settings'), ['migrate', 'db']))) {
                run('cd {{release_path}} && ./yii migrate --interactive=0');
            }
        });
    }

}



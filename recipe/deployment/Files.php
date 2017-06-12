<?php

namespace Recipe\deployment;

use function Deployer\desc;
use function Deployer\get;
use function Deployer\run;
use function Deployer\task;
use function Deployer\upload;
use function Deployer\writeln;

class Files
{

    public function __construct()
    {
        desc("Perform all the files tasks");
        task('files', ['files:upload-files',
            'files:show']);

        desc("Uploads all the files that where given in the upload tag.");
        task('files:upload-files', function () {
            $files = get('settings')['files']['upload-files'];
            if ($files) {
                foreach ($files as $file) {
                    $info = $this->extract_info($file);
                    if ($this->check_file($info['in'])) {
                        $this->upload_file($info);
                    }
                }
            }
        });

        desc("Shows files that where given in the show tag.");
        task('files:show', function () {
            $shows = get('settings')['files']['show'];
            if ($shows) {
                foreach ($shows as $file) {
                    if ($this->check_file_remote($file)) {
                        $this->show_file_remote($file);
                    }
                }
            }
        });


    }

    public function extract_info($file)
    {
        $splitted_info = explode("||", $file);

        $info = [];

        $info['in'] = trim($splitted_info[0], ' ');
        $info['to'] = trim($splitted_info[0], ' ');

        if (count($splitted_info) > 1) {
            $info['to'] = trim($splitted_info[1], ' ');
        }

        return $info;
    }

    public function check_file($file)
    {
        if (!file_exists($file)) {
            writeln("<error> Can't find file: {{$file}} ... But continue! </error>");
            return false;
        }

        return true;
    }

    public function check_file_remote($file)
    {
        $response = run("if [ -f {{release_path}}/{$file} ]; then echo 'true'; fi");
        $status = $response->toBool();
        if (!$status) {
            writeln("<error>Can't find file: {{release_path}}/{$file} ... But continue!</error>");
        }
        return $status;
    }

    public function show_file_remote($file)
    {
        $remoteFile = "{{release_path}}" . "/" . $file;
        writeln("<comment>Showing: {$remoteFile}</comment>");
        run("cat " . $remoteFile);
    }

    public function upload_file($file)
    {
        upload($file['in'], "{{release_path}}/" . $file['to']);
    }

}


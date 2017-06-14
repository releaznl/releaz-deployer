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
                    $info = $this->extractLocationInformation($file);
                    if ($this->checkFileLocaly($info['in'])) {
                        $this->uploadFile($info);
                    }
                }
            }
        });

        desc("Shows files that where given in the show tag.");
        task('files:show', function () {
            $shows = get('settings')['files']['show'];
            if ($shows) {
                foreach ($shows as $file) {
                    if ($this->checkFileRemote($file)) {
                        $this->showFileRemote($file);
                    }
                }
            }
        });


    }

    /**
     * Function for extracting file out information. This file needs to be a given path or supported path(s) format for
     * file uploading. When given 'a/path/name/file.php' the function will return that the in file is the same as the
     * out location. When given 'a/path/name/file.php || b/path/name/file.php' the function will return that the 'in' value
     * of the file is 'a/path/name/file.php' and the out path is 'b/path/name/file.php'.
     *
     * @param string $file An string containing an path name or supported formatted path string.
     * @return array An array containing location information. This is in the following format:
     *
     * [
     *      'in' => 'in/path/location/file.php',
     *      'out' => 'out/path/location/file.php'
     * ]
     */
    public function extractLocationInformation(string $file)
    {
        $splittedPath = explode("||", $file);
        $splittedPathLength = count($splittedPath);

        $pathInfo = [];

        $defaultPath = trim($splittedPath[0], ' ');

        $pathInfo['in'] = $defaultPath;
        $pathInfo['to'] = $defaultPath; // Set default first, check below if out location was defined:

        if ($splittedPathLength > 1) {
            $pathInfo['to'] = trim($splittedPath[1], ' ');
        }

        return $pathInfo;
    }

    /**
     * Function for checking if a file exists (locally)
     *
     * @param string $file The path string
     * @return bool Status if file exists
     */
    public function checkFileLocaly(string $file)
    {
        if (!file_exists($file)) {
            writeln("<error> Can't find file: {{$file}} ... But continue! </error>");
            return false;
        }

        return true;
    }

    /**
     * Function for checking if a file exists (remote)
     *
     * @param string $file The path string
     * @return bool Status if file exists
     */
    public function checkFileRemote(string $file)
    {
        $response = run("if [ -f {{release_path}}/{$file} ]; then echo 'true'; fi");
        $status = $response->toBool();
        if (!$status) {
            writeln("<error>Can't find file: {{release_path}}/{$file} ... But continue!</error>");
        }
        return $status;
    }

    /**
     * Function that shows a remote file
     *
     * @param string $file
     */
    public function showFileRemote(string $file)
    {
        $remoteFile = "{{release_path}}" . "/" . $file;
        writeln("<comment>Showing: {$remoteFile}</comment>");
        run("cat " . $remoteFile);
    }

    /**
     * Function for uploading a file. Please use the returned value of the below mentioned function.
     *
     * @param array $file File of matter
     * @see Files::extractLocationInformation()
     */
    public function uploadFile(array $file)
    {
        upload($file['in'], "{{release_path}}/" . $file['to']);
    }

}


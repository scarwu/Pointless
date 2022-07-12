<?php
/**
 * Api/Theme Controller
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Controller\Api;

use Pointless\Library\BlogCore;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Oni\Web\Controller\Ajax as Controller;

class ThemeController extends Controller
{
    /**
     * Get List Action
     */
    public function getListAction($params = [])
    {
        if (0 < count($params)) {
            http_response_code(404);

            return [
                'status' => 'error',
                'text' => 'pointless:api:notFound'
            ];
        }

        return [
            'status' => 'ok',
            'text' => 'success',
            'payload' => BlogCore::getThemeList()
        ];
    }

    /**
     * Install Item Action
     */
    public function installItemAction($params = [])
    {
        if (0 < count($params)) {
            http_response_code(404);

            return [
                'status' => 'error',
                'text' => 'pointless:api:notFound'
            ];
        }

        // // [ 'theme', 'install', '<gitRepo>' ]
        // $gitRepo = $this->io->getArguments(2);

        // if (false === isset($gitRepo)) {
        //     $this->io->error("Git repo url is not found.");

        //     return false;
        // }

        // if (true === $this->io->hasConfigs('branch')) {
        //     $branch = $this->io->getConfigs('branch');
        // } else {
        //     $branch = 'master';
        // }

        // $this->io->notice('Installing Theme');

        // $tmpFolder = '/tmp/pointless-theme-' . hash('md5', $gitRepo . time());

        // if (file_exists($tmpFolder)) {
        //     Utility::remove($tmpFolder);
        // }

        // // Clone Git Repo to Temp Folder
        // system("git clone --branch {$branch} {$gitRepo} {$tmpFolder}");

        // // Check Theme Information
        // if (true === is_dir("{$tmpFolder}/dist")
        //     && true === is_file("{$tmpFolder}/dist/constant.php")
        // ) {

        //     // Include Theme Constant
        //     include "{$tmpFolder}/dist/constant.php";

        //     // Copy Theme to Current Folder
        //     Utility::copy("{$tmpFolder}/dist", BLOG_ROOT . "/themes/{$constant['name']}");
        // }

        // // Remove Temp Folder
        // Utility::remove($tmpFolder);

        return [
            'status' => 'ok',
            'text' => 'success'
        ];
    }

    /**
     * Uninstall Item Action
     */
    public function uninstallItemAction($params = [])
    {
        if (0 === count($params)) {
            http_response_code(400);

            return [
                'status' => 'error',
                'text' => 'pointless:api:paramsIsEmpty'
            ];
        }

        // // Select Theme Data
        // $themeData = $this->selectThemeData();

        // if (false === is_array($themeData)) {
        //     $this->io->error('No theme(s).');

        //     return false;
        // }

        // // Get Info
        // $title = $themeData['title'];
        // $path = $themeData['path'];

        // $anwser = $this->io->ask("Are you sure uninstall theme \"{$title}\"? [y/N]", null, 'red');
        // $anwser = strtolower($anwser);

        // $this->io->writeln();

        // if ('y' === $anwser) {
        //     Utility::remove($path);

        //     $this->io->notice("Successfully uninstalled theme \"{$title}\".");
        // }

        return [
            'status' => 'ok',
            'text' => 'success'
        ];
    }
}

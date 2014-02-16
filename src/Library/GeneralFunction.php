<?php
/**
 * General Function
 * 
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

/**
 * Bind PHP Data to HTML Template
 *
 * @param string
 * @param string
 * @return string
 */
function bindData($data, $path) {
    ob_start();
    include $path;
    $result = ob_get_contents();
    ob_end_clean();
    
    return $result;
}

/**
 * Write Data to File
 *
 * @param string
 * @param string
 */
function writeTo($data, $path) {
    if(!preg_match('/\.(html|xml)$/', $path)) {
        if(!file_exists($path))
            mkdir($path, 0755, TRUE);
        $path = $path . '/index.html';
    }
    else {
        $segments = explode('/', $path);
        array_pop($segments);
        $dirpath = implode($segments, '/');
        if(!file_exists($dirpath))
            mkdir($dirpath, 0755, TRUE);
    }

    $handle = fopen($path, 'w+');
    fwrite($handle, $data);
    fclose($handle);
}

/**
 * Recursive Copy
 *
 * @param string
 * @param string
 */
function recursiveCopy($src, $dest) {
    if(file_exists($src)) {
        if(is_dir($src)) {
            if(!file_exists($dest))
                mkdir($dest, 0755, TRUE);
            $handle = @opendir($src);
            while($file = readdir($handle))
                if(!in_array($file, ['.', '..', '.git']))
                    recursiveCopy("$src/$file", "$dest/$file");
            closedir($handle);
        }
        else
            copy($src, $dest);
    }
}

/**
 * Recursive Remove
 *
 * @param string
 * @param string
 * @return boolean
 */
function recursiveRemove($path = NULL) {
    if(file_exists($path)) {
        if(is_dir($path)) {
            $handle = opendir($path);
            while($file = readdir($handle)) {
                if(!in_array($file, ['.', '..', '.git']))
                    recursiveRemove("$path/$file");
            }
            closedir($handle);

            if($path != TEMP && $path != DEPLOY)
                return rmdir($path);
        }
        else
            return unlink($path);
    }
}
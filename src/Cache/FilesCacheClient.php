<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 10/12/17
 * Time: 09:48
 */

namespace Gvera\Cache;


class FilesCacheClient
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function saveToFile($file, $value) {
        file_put_contents($this->path . $file, json_encode($value));
        return true;
    }

    public function loadFromFile($file) {
        return file_get_contents($this->path . $file);
    }
}
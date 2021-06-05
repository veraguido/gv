<?php

namespace Gvera\Helpers\http;

use Gvera\Helpers\config\Config;
use Gvera\Helpers\fileSystem\File;
use Gvera\Exceptions\NotFoundException;
use Gvera\Exceptions\InvalidFileTypeException;

class FileManager
{

    private array $files = [];
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param $source
     * @param string|null $replaceName
     */
    public function buildFilesFromSource($source, ?string $replaceName = null)
    {
        foreach ($source as $fileKey => $file) {
            $imageFileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $name = $replaceName ? $replaceName . "." . $imageFileType : $file['name'];
            if ($imageFileType === "") {
                $name = "";
            }
            $newFile = new File();
            $newFile->setName($name);
            $newFile->setSize($file['size']);
            $newFile->setTemporaryName($file['tmp_name']);
            $newFile->setError($file['error']);
            $newFile->settype($file['type']);
            $this->files[$fileKey] = $newFile;
        }
    }

    /**
     * @param $name
     * @return File
     * @throws NotFoundException
     */
    public function getByName($name): File
    {
        $file = $this->files[$name];
        if (null === $file) {
            throw new NotFoundException("The file you are trying to get is not uploaded");
        }
        return $file;
    }

    /**
     * @param string $targetDirectory
     * @param File $file
     * @throws InvalidFileTypeException
     * @throws NotFoundException
     * @return bool
     */
    public function saveToFileSystem(string $targetDirectory, File $file): bool
    {
        if ($file->getError() === 4) {
            return true;
        }

        if (!in_array($file, $this->files)) {
            throw new NotFoundException("The file you are trying to move is not uploaded");
        }

        if (!in_array($file->getType(), $this->config->getConfigItem('allowed_upload_file_types'))) {
            throw new InvalidFileTypeException(
                "The file you are trying to move does not match the server's requirement"
            );
        }

        $uploadPath = $targetDirectory . $file->getName();
        return move_uploaded_file($file->getTemporaryName(), $uploadPath);
    }

    public function recursiveRemove($dir)
    {
        $structure = glob(rtrim($dir, "/").'/*');
        if (is_array($structure)) {
            foreach ($structure as $file) {
                if (is_dir($file)) {
                    $this->recursiveRemove($file);
                } elseif (is_file($file)) {
                    unlink($file);
                }
            }
        }
        rmdir($dir);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.07.2018
 * Time: 22:37
 */

namespace projectBundle\FileUploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;
    private $path;

    public function __construct($targetDir, $path)
    {
        $this->targetDir = $targetDir;
        $this->path = $path;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = $this->getPath() . md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->getTargetDir(), $fileName);

        return $fileName;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }

    public function getPath()
    {
        return $this->path;
    }
}
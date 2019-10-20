<?php

namespace Novvai\Utilities;

class File
{
    private $size;
    private $tmpName;
    private $filePath;
    private $fileName;
    private $fileType;
    private $basePath;
    private $baseFileName;
    private $fileExtension;
    private $originalFileName;

    private function __construct($file)
    {
        $this->basePath = public_path();
        $this->extract($file);
    }

    static public function make($file)
    {
        return new self($file);
    }

    public function getFilePath()
    {
        return $this->filePath.$this->fileName.".".$this->fileExtension;
    }

    public function as(string $name)
    {
        $this->fileName = $name;
        return $this;
    }
    public function to(string $path)
    {
        $this->filePath = $path;
        return $this;
    }

    public function save()
    {
        move_uploaded_file( $this->tmpName, $this->basePath.$this->filePath.$this->fileName.".".$this->fileExtension);
        return $this;
    }


    /** */
    private function extract($file)
    {
        $fileInfo = pathinfo($file['name']);

        $this->fileExtension = $fileInfo['extension'];
        $this->fileName = $fileInfo['filename'];
        $this->baseFileName = $fileInfo['basename'];
        $this->originalFileName = $fileInfo['filename'];
        
        $this->fileType = $file['type'];
        $this->tmpName = $file['tmp_name'];
        $this->size = $file['size'];

        return $this;
    }
}

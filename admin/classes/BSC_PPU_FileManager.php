<?php

class BSC_PPU_FileManager
{
    private $baseDir;

    public function __construct($baseDir)
    {
        $this->baseDir = $baseDir;
    }

    public function getSubfolders()
    {
        echo '<br>';
        echo '<br> $this->baseDir ';
        echo '<br>'.$this->baseDir;
        echo '<br>';
        echo '<br>';
        return array_filter(glob($this->baseDir . '/*'), 'is_dir');
    }

    public function getFilesInFolder($folder)
    {
        return array_filter(glob("$folder/*.jpg"), function ($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'jpg';
        });
    }

    public function isFolderValid($folderName)
    {
        $prefixes = Config::getSupportedPrefixes();
        return preg_match('/^(' . implode('|', $prefixes) . ')_\d+$/', $folderName);
    }

    public function extractGroupAndProductId($folderName)
    {
        if (preg_match('/^(SK|HC|MK)_(\d+)$/', $folderName, $matches)) {
            return [
                'group' => $matches[1],
                'product_id' => $matches[2],
                'sku' => "BSC:{$matches[1]}:{$matches[2]}",
            ];
        }
        return null;
    }
}

?>

<?php

class BSC_PPU_FileManager
{
    private $baseDir;

    public function __construct($baseDir)
    {
        $this->baseDir = $baseDir;
        $this->zipDir = $baseDir . '/product_photos_zips/FOTOS_PAG_WEB_NOMENCLATURA.zip';
        $this->photosDir = $baseDir . '/product_photos_zips/FOTOS_PAG_WEB_NOMENCLATURA';
    }

    public function getSubfolders()
    {
        echo 'getSubfolder:  $this->photosDir  :: ';
        var_dump($this->photosDir );
        return array_filter(glob($this->photosDir  . '/*'), 'is_dir');
    }

    public function getFilesInFolder($folder)
    {
        return array_filter(glob("$folder/*.jpg"), function ($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'jpg';
        });
    }

    public function isFolderValid($folderName)
    {
        $prefixes = BSC_PPU_Config::getSupportedPrefixes();
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

    public function getUploadDirectory(): string
    {
        return $this->baseDir . '/product_photos_zips';
    }

    public function cleanAndExtractZip(): void
    {
        $zipFilePath = $this->baseDir . '/product_photos_zips/FOTOS_PAG_WEB_NOMENCLATURA.zip';
        $targetFolder = $this->baseDir . '/product_photos_zips/FOTOS_PAG_WEB_NOMENCLATURA';

        // Step 1: Delete existing folder
        if (file_exists($targetFolder) && is_dir($targetFolder)) {
            $this->deleteFolder($targetFolder);
            echo "Deleted existing folder: $targetFolder<br>";
        }

        // Step 2: Unzip the file
        if (file_exists($zipFilePath)) {
            $this->unzipFile($zipFilePath, $this->baseDir . '/product_photos_zips');
            echo "Extracted ZIP file to: $targetFolder<br>";
        } else {
            echo "ZIP file not found: $zipFilePath<br>";
        }
    }

    private function deleteFolder(string $folder): void
    {
        foreach (scandir($folder) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $itemPath = $folder . DIRECTORY_SEPARATOR . $item;

            if (is_dir($itemPath)) {
                $this->deleteFolder($itemPath);
            } else {
                unlink($itemPath);
            }
        }

        rmdir($folder);
    }

    private function unzipFile(string $zipFilePath, string $extractTo): void
    {
        $zip = new ZipArchive;

        if ($zip->open($zipFilePath) === true) {
            $zip->extractTo($extractTo);
            $zip->close();
            echo "Successfully unzipped: $zipFilePath<br>";
    
            // Step: Rename folder if it contains spaces
            $originalFolder = $extractTo . '/FOTOS PAG WEB NOMENCLATURA';
            $newFolder = $extractTo . '/FOTOS_PAG_WEB_NOMENCLATURA';
    
            if (file_exists($originalFolder) && is_dir($originalFolder)) {
                rename($originalFolder, $newFolder);
                echo "Renamed folder to: $newFolder<br>";
            } else {
                echo "Expected folder not found: $originalFolder<br>";
            }
        } else {
            throw new RuntimeException("Failed to open ZIP file: $zipFilePath");
        }
    }
}

?>
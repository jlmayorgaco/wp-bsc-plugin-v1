
<?php

function do_check_dir( $folderPath ){

// Check if the folder exists
if (file_exists($folderPath) && is_dir($folderPath)) {
    echo "Folder exists: $folderPath\n";

    // Get the contents of the folder
    $contents = scandir($folderPath);

    // Remove "." and ".." from the result
    $contents = array_diff($contents, ['.', '..']);

    // Print the contents
    if (!empty($contents)) {
        echo "Contents of the folder:\n";
        foreach ($contents as $item) {
            $itemPath = $folderPath . '/' . $item;

            // Check if it's a file or a folder
            if (is_file($itemPath)) {
                echo "File: $item\n";
            } elseif (is_dir($itemPath)) {
                echo "Folder: $item\n";
            }
        }
    } else {
        echo "The folder is empty.\n";
    }
} else {
    echo "Folder does not exist: $folderPath\n";
}
}


class BSC_PPU_FileManager
{
    private $baseDir;

    public function __construct($baseDir)
    {
        $this->baseDir = $baseDir;
    }

    public function getSubfolders()
    {
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
<?php



class BSC_PPU_Processor
{
    private $fileManager;
    private $productManager;
    private $mediaManager;

    public function __construct($baseDir)
    {
        $this->fileManager = new FileManager($baseDir);
        $this->productManager = new ProductManager();
        $this->mediaManager = new MediaManager();
    }

    public function process()
    {
        $subfolders = $this->fileManager->getSubfolders();

        foreach ($subfolders as $folder) {
            $folderName = basename($folder);

            if (!$this->fileManager->isFolderValid($folderName)) {
                echo "Invalid folder: $folderName<br>";
                continue;
            }

            $groupData = $this->fileManager->extractGroupAndProductId($folderName);
            $product = $this->productManager->getProductBySku($groupData['sku']);

            if (!$product) {
                echo "Product not found for SKU: {$groupData['sku']}<br>";
                continue;
            }

            $this->processFolder($folder, $product);
        }
    }

    private function processFolder($folder, $product)
    {
        $files = $this->fileManager->getFilesInFolder($folder);
        $attachmentIds = [];

        foreach ($files as $file) {
            $attachmentId = $this->mediaManager->uploadImage($file);
            if ($attachmentId) {
                $attachmentIds[] = $attachmentId;
            }
        }

        //$this->productManager->clearImages($product);
        //$this->productManager->attachImages($product, $attachmentIds);

        echo "Processed folder: $folder for product: {$product->get_name()}<br>";
    }
}


?>
<?php



class BSC_PPU_Processor
{
    private BSC_PPU_FileManager $fileManager;
    private BSC_PPU_ProductManager $productManager;
    private BSC_PPU_MediaManager $mediaManager;
    private int $batchSize;

    public function __construct(
        BSC_PPU_FileManager $fileManager,
        BSC_PPU_ProductManager $productManager,
        BSC_PPU_MediaManager $mediaManager,
        int $batchSize
    ) {
        $this->fileManager = $fileManager;
        $this->productManager = $productManager;
        $this->mediaManager = $mediaManager;
        $this->batchSize = $batchSize;
    }

    public function process()
    {
        $batchSize = $this->batchSize;
        $subfolders = $this->fileManager->getSubfolders();
        $limitedSubfolders = array_slice($subfolders, 0, $batchSize);

        echo '$limitedSubfolders ';
        var_dump($limitedSubfolders);

        foreach ($limitedSubfolders as $folder) {
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

        $this->productManager->clearImages($product);
        $this->productManager->attachImages($product, $attachmentIds);

        $baseDir = BSC_PPU_Config::getUploadDirectory();

        echo 'before logFile ';

        $logFile = $baseDir . '/FOTOS_PAG_WEB_NOMENCLATURA/process.log';

        echo 'before  text ';

        $text = "Processed folder: $folder for product: {$product->get_name()} <br>";

        echo 'before self in processFolder ';
        BSC_PPU_Init::log( $text, $logFile);

    
        echo 'after  logs in processFolder';

        echo "Processed folder: $folder for product: {$product->get_name()} <br>";
    }

    
}


?>
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

        echo '<br>';
        echo '<br> $limitedSubfolders :: ';
        var_dump($limitedSubfolders);
        echo '<br>';
        echo '<br>';

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


        echo '<br>';
        echo '<h5> $product->get_name() '.$product->get_name().'</h5>';
        echo '<br> ';

        $this->productManager->attachImages($product, $attachmentIds);

        echo "Processed folder: $folder for product: {$product->get_name()}<br>";
    }

    
}


?>
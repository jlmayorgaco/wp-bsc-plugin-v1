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

        var_dump($limitedSubfolders);

      
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

        echo '<br>';
        echo '<h5> $product->get_name() '.$product->get_name().'</h5>';
        echo '<br> ';

        //$this->productManager->clearImages($product);
        //$this->productManager->attachImages($product, $attachmentIds);

        echo "Processed folder: $folder for product: {$product->get_name()}<br>";
    }
}


?>
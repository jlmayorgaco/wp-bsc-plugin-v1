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
        $subfolders = $this->fileManager->getSubfolders();
        foreach ($subfolders as $folder) {
            $folderName = basename($folder);

            echo '<br> ';
            echo '<br> ** before $this->fileManager :: <br>';
            echo var_dump($this->fileManager);
            echo '<br> ';
            echo '<br> ';
            echo '<br> ';
            echo '<br>  before $this->fileManager->isFolderValid($folderName) :: <br>';
            echo var_dump($this->fileManager->isFolderValid($folderName));
            echo '<br> ' ;
            echo '<br> ';

            if (!$this->fileManager->isFolderValid($folderName)) {
                echo "Invalid folder: $folderName<br>";
                continue;
            }


            echo '<br>';
            echo '<br> $folderName :: <br>';
            echo var_dump($folderName);
            echo '<br>';
            echo '<br>';

       
            $groupData = $this->fileManager->extractGroupAndProductId($folderName);
            echo '<br>';
            echo '<br> $groupData :: <br>';
            echo var_dump($groupData);
            echo '<br>';
            echo '<br>';


            $product = $this->productManager->getProductBySku($groupData['sku']);
            echo '<br>';
            echo '<br> $product :: <br>';
            echo var_dump($product);
            echo '<br>';
            echo '<br>';

            /*
            if (!$product) {
                echo "Product not found for SKU: {$groupData['sku']}<br>";
                continue;
            }

            $this->processFolder($folder, $product);
            */
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

        echo '<br>';
        echo '<h5> $product->get_name() '.$product->get_name().'</h5>';
        echo '<br> ';

        //$this->productManager->clearImages($product);
        //$this->productManager->attachImages($product, $attachmentIds);

        echo "Processed folder: $folder for product: {$product->get_name()}<br>";
    }
}


?>
<?php

class BSC_PPU_Init
{
    public static function init()
    {

        $ppuPath = BSC_PPU_Config::getUploadDirectory();
        $ppuMediaCleaner = BSC_PPU_MediaCleaner::cleanMediaLibrary();
        $ppuIncompleteCleaner = BSC_PPU_MediaCleaner::cleanIncompleteMedia();
        $ppuFileManager = new BSC_PPU_FileManager($ppuPath);
        $ppuFileManager->cleanAndExtractZip();

        $ppuMediaManager = new BSC_PPU_MediaManager();
        $ppuProductManager = new BSC_PPU_ProductManager();

        $processor = new BSC_PPU_Processor(
            fileManager: $ppuFileManager,
            productManager: $ppuProductManager,
            mediaManager: $ppuMediaManager,
            batchSize: BSC_PPU_Config::getBatchSize()
        );

        $processor->process();
    }

}

?>

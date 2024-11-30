<?php

class BSC_PPU_Init
{
    public static function init()
    {
        $baseDir = BSC_PPU_Config::getUploadDirectory();
        $logFile = $baseDir . '/product_photos_zips/process.log'; // Log file path

        // Ensure the directory exists
        if (!file_exists($baseDir . '/product_photos_zips')) {
            mkdir($baseDir . '/product_photos_zips', 0755, true);
        }

        self::log("BSC_PPU_Init: Starting process.", $logFile);

        try {
            $ppuPath = BSC_PPU_Config::getUploadDirectory();
            self::log("Upload directory: $ppuPath", $logFile);

            // Step 1: Clean media library
            BSC_PPU_MediaCleaner::cleanMediaLibrary();
            self::log("Media library cleaned.", $logFile);

            // Step 2: Clean incomplete media
            BSC_PPU_MediaCleaner::cleanIncompleteMedia();
            self::log("Incomplete media cleaned.", $logFile);

            // Step 3: File manager operations
            $ppuFileManager = new BSC_PPU_FileManager($ppuPath);
            $ppuFileManager->cleanAndExtractZip();
            self::log("Zip file extracted and processed.", $logFile);

            // Step 4: Initialize managers
            $ppuMediaManager = new BSC_PPU_MediaManager();
            $ppuProductManager = new BSC_PPU_ProductManager();
            self::log("Media and product managers initialized.", $logFile);

            // Step 5: Process files
            $processor = new BSC_PPU_Processor(
                fileManager: $ppuFileManager,
                productManager: $ppuProductManager,
                mediaManager: $ppuMediaManager,
                batchSize: BSC_PPU_Config::getBatchSize()
            );

            $processor->process();
            self::log("Processor completed.", $logFile);

        } catch (Exception $e) {
            self::log("Error: " . $e->getMessage(), $logFile);
        }

        self::log("BSC_PPU_Init: Process completed.", $logFile);
    }

    /**
     * Log a message to the specified file.
     */
    public static function log($message, $logFile)
    {
        $timestamp = date('[Y-m-d H:i:s]');
        file_put_contents($logFile, "$timestamp $message" . PHP_EOL, FILE_APPEND);
    }
}
?>

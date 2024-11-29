<?php

class BSC_PPU_Config
{
    public static function getUploadDirectory()
    {
        $uploadDir = wp_upload_dir();
        return $uploadDir['basedir'] . '/product_photos_zips/FOTOS_PAG_WEB_NOMENCLATURA';
    }

    public static function getSupportedPrefixes()
    {
        return ['SK', 'HC', 'MK'];
    }

    public static function getBatchSize()
    {
        return 5; 
    }
}
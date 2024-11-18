<?php

class ProductPhotosUploaderClass
{
    private $file;
    private $targetDir;
    private $fileName;
    private $uploadedFile;
    public function __construct($file = null)
    {
        $uploadDir = wp_upload_dir();
        $this->targetDir = $uploadDir['basedir'] . '/product_photos_zips/';
        $this->fileName = 'FOTOS_PAG_WEB_NOMENCLATURA.zip';
        $this->uploadedFile = $this->targetDir . $this->fileName; // Path to the existing zip file
    }

    // Main method to initiate the process
    public function uploadAndProcessFile()
    {
        // Check if the existing zip file is available
        if (!file_exists($this->uploadedFile)) {
            return $this->handleError("Zip file not found: " . $this->uploadedFile);
        }

        try {
            $this->ensureTargetDirectoryExists();
            $this->clearProductsImages();
            $this->clearFolder($this->targetDir);

            $destination = $this->uploadedFile;
            $this->processZipFile($destination);

        } catch (Exception $e) {
            $this->handleError('Error: ' . $e->getMessage());
        }
    }

    // Validate the file upload
    private function isUploadValid()
    {
        return isset($this->file) && $this->file['error'] === UPLOAD_ERR_OK;
    }

    // Ensure that the target directory exists
    private function ensureTargetDirectoryExists()
    {
        if (!file_exists($this->targetDir)) {
            mkdir($this->targetDir, 0755, true);
        }
    }

    // Clear images for all products
    public function clearProductsImages()
    {
        $products = $this->getAllProducts();
        foreach ($products as $product_post) {
            $this->clearProductImages(wc_get_product($product_post->ID));
        }
    }

    // Fetch all products
    private function getAllProducts()
    {
        return get_posts([
            'post_type'      => 'product',
            'posts_per_page' => -1, // Retrieve all products
        ]);
    }

    // Clear images for a single product
    private function clearProductImages($product)
    {
        $this->deleteGalleryImages($product);
        $this->deleteFeaturedImage($product);
        $product->save();
        echo "Images cleared for product: " . $product->get_name() . "<br>";
    }

    // Delete gallery images
    private function deleteGalleryImages($product)
    {
        foreach ($product->get_gallery_image_ids() as $image_id) {
            wp_delete_attachment($image_id, true); // Delete the images
        }
        $product->set_gallery_image_ids([]);
    }

    // Delete the featured image
    private function deleteFeaturedImage($product)
    {
        $featured_image_id = $product->get_image_id();
        if ($featured_image_id) {
            wp_delete_attachment($featured_image_id, false); // Optionally keep the file
            $product->set_image_id(''); // Unset the featured image
        }
    }

    // Clear a folder by deleting all files
    private function clearFolder($folderPath)
    {
        foreach (glob($folderPath . '*') as $file) {
            is_dir($file) ? $this->deleteFolder($file) : unlink($file);
        }
    }

    // Delete a folder and its contents recursively
    private function deleteFolder($folder)
    {
        foreach (glob($folder . '/*') as $file) {
            is_dir($file) ? $this->deleteFolder($file) : unlink($file);
        }
        rmdir($folder);
    }

    // Move the uploaded file to the destination
    private function moveUploadedFile($destination)
    {
        return move_uploaded_file($this->uploadedFile, $destination);
    }

    // Process the uploaded zip file
    private function processZipFile($zipFilePath)
    {
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath) === true) {
            $extractPath = $this->getExtractPath($zipFilePath);
            $zip->extractTo($extractPath);
            $zip->close();

            echo '<br>';
            echo "Zip file extracted to: " . $extractPath;
            echo '<br>';

            $this->processExtractedFolders($extractPath);
        } else {
            $this->handleError("Failed to open the zip file.");
        }
    }

    // Determine the extraction path for the zip file
    private function getExtractPath($zipFilePath)
    {
        return $this->targetDir . pathinfo($this->fileName, PATHINFO_FILENAME) . '/';
    }

    // Process the folders extracted from the zip file
    private function processExtractedFolders($extractPath)
    {
        $fullPath = $extractPath;
        echo '<br>';
        echo 'is_dir($fullPath) : ';
        echo is_dir($fullPath);
        echo '<br>';
        echo '<br>';
        echo 'fullPath : ';
        echo $fullPath;
        echo '<br>';

        if (is_dir($fullPath)) {
            $this->countFoldersByPrefix($fullPath);
            $this->processFoldersByPrefix($fullPath);
        } else {
            $this->handleError("Extracted folder not found. Should have nave FOTOS_PAG_WEB_NOMENCLATURA");
            $this->handleError($fullPath);
            echo '<br>';
            $this->handleError($extractPath);
        }
    }

    // Count folders by prefix (SK, HC, MK)
    private function countFoldersByPrefix($directory)
    {
        $counts = [
            'SK' => 0,
            'HC' => 0,
            'MK' => 0,
        ];

        foreach (scandir($directory) as $subfolder) {
            if ($this->isFolder($directory, $subfolder)) {
                $prefix = $this->getPrefix($subfolder);
                if (array_key_exists($prefix, $counts)) {
                    $counts[$prefix]++;
                }
            }
        }

        $this->printFolderCounts($counts);
    }

    // Check if it's a valid folder
    private function isFolder($directory, $subfolder)
    {
        return $subfolder !== '.' && $subfolder !== '..' && is_dir($directory . '/' . $subfolder);
    }

    // Extract prefix (SK, HC, MK) from the folder name
    private function getPrefix($folderName)
    {
        return substr($folderName, 0, 2); // Extract the first two characters
    }

    // Print the counts for each folder prefix
    private function printFolderCounts($counts)
    {
        echo "<br>Number of SK folders: {$counts['SK']}<br>";
        echo "Number of HC folders: {$counts['HC']}<br>";
        echo "Number of MK folders: {$counts['MK']}<br>";
    }

    // Process each folder by prefix
    private function processFoldersByPrefix($directory)
    {
        foreach (scandir($directory) as $subfolder) {
            if ($this->isFolder($directory, $subfolder)) {
                $this->processFolder($directory, $subfolder);
            }
        }
    }

    // Process a single folder
    private function processFolder($directory, $subfolder)
    {
        $folderPath = $directory . '/' . $subfolder;
        $files = scandir($folderPath);

        $group = $this->extractFolderGroup($subfolder);
        $photos = $this->processFiles($files, $folderPath, $group);

        $productId = wc_get_product_id_by_sku($group['sku']);
        if ($productId) {
            $this->attachPhotosToProduct($photos, wc_get_product($productId));
        } else {
            echo "Product not found for SKU: " . $group['sku'] . "<br>";
        }
    }

    // Extract the group information (SK, HC, MK)
    private function extractFolderGroup($subfolder)
    {
        if (preg_match('/^(SK|HC|MK)_(\d+)$/', $subfolder, $matches)) {
            return [
                'group' => $matches[1],
                'productId' => $matches[2],
                'sku' => 'BSC:' . $matches[1] . ':' . $matches[2], // Example: BSC:SK:1
            ];
        }
        return null;
    }

    // Process files in the folder
    private function processFiles($files, $folderPath, $group)
    {
        $photos = [];
        foreach ($files as $file) {
            if ($this->isValidFile($file)) {
                $photos[] = $this->extractPhotoDetails($file, $folderPath, $group);
            }
        }
        return $photos;
    }

    // Extract details of a photo
    private function extractPhotoDetails($file, $folderPath, $group)
    {
        $photo = [];
        if (preg_match('/^(SK|HC|MK)_(\d+)_PHOTO_(\d+)\.jpg$/', $file, $matches)) {
            $photo = [
                'index' => $matches[3], // Photo ID
                'url' => $folderPath . '/' . $file, // Path to the file
            ];
        }
        return $photo;
    }

    // Attach photos to the product
    private function attachPhotosToProduct($photos, $product)
    {
        $attachmentIds = [];
        
        foreach ($photos as $photo) {
            $attachmentId = $this->uploadPhoto($photo);
            if ($attachmentId) {
                $attachmentIds[] = $attachmentId;
            }
        }

        $photoId = $attachmentIds[0];
        $photoIds = $attachmentIds;

        array_shift($photoIds);

        $product->set_image_id($photoId);
        $product->set_gallery_image_ids($photoIds);
        $product->save();
    }

    // Check if the file is valid
    private function isValidFile($file)
    {
        return $file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'jpg';
    }

    // Upload photo to WordPress and return attachment ID
    private function uploadPhoto($photo)
    {
        $filePath = $photo['url'];
        $filetype = wp_check_filetype(basename($filePath), null);
        $wp_upload_dir = wp_upload_dir();

        // Prepare an array of attachment data
        $attachment = array(
            'guid'           => $wp_upload_dir['url'] . '/' . basename($filePath), 
            'post_mime_type' => $filetype['type'],
            'post_title'     => preg_replace('/\.[^.]+$/', '', basename($filePath)),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        // Insert the attachment
        $attachment_id = wp_insert_attachment($attachment, $filePath);

        // Check if attachment was successfully created
        if (!is_wp_error($attachment_id)) {
            // Generate metadata for the attachment
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attachment_data = wp_generate_attachment_metadata($attachment_id, $filePath);
            wp_update_attachment_metadata($attachment_id, $attachment_data);

            return $attachment_id;
        } else {
            $this->handleError("Failed to upload photo: " . $photo['url']);
            return null;
        }
    }

    // Handle errors by printing or logging the error message
    private function handleError($message)
    {
        echo '<strong>Error:</strong> ' . $message;
    }
}

?>



<?php
/*
class ProductPhotosUploaderClass
{
    private $file;
    private $targetDir;
    private $fileName;
    private $uploadedFile;

    public function __construct($file)
    {
        $uploadDir = wp_upload_dir();

        $this->file = $file;
        $this->targetDir = $uploadDir['basedir'] . '/product_photos_zips/';
        $this->uploadedFile = $file['tmp_name'];
        $this->fileName = $file['name'];
    }


    public function clearProductsImages()
    {
        // Get all products
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => -1, // Retrieve all products
        ];
        $products = get_posts($args);
    
        // Loop through each product
        foreach ($products as $product_post) {
            // Get the full product object
            $product = wc_get_product($product_post->ID);
    
            // Clear gallery images
            $existing_gallery = $product->get_gallery_image_ids();
            foreach ($existing_gallery as $image_id) {
                // Delete each image attachment but keep the file (set false to keep the file, true to delete the file)
                wp_delete_attachment($image_id, true);
            }
            // Clear gallery image IDs
            $product->set_gallery_image_ids([]);
    
            // Clear the featured image
            $featured_image_id = $product->get_image_id();
            if ($featured_image_id) {
                wp_delete_attachment($featured_image_id, false); // Set false to retain the file
                $product->set_image_id(''); // Unset the featured image
            }
    
            // Save the updated product
            $product->save();
    
            echo "Images cleared for product: " . $product->get_name() . "<br>";
        }
    }
    

    public function uploadAndProcessFile()
    {
        if ($this->isUploadValid()) {
            try {
                $this->ensureTargetDirectoryExists();
                $this->clearProductsImages();
                $this->clearFolder($this->targetDir);

                $destination = $this->targetDir . basename($this->fileName);
                if ($this->moveUploadedFile($destination)) {
                    $this->processZipFile($destination);
                } else {
                    $this->handleError("Failed to upload the file.");
                }
            } catch (Exception $e) {
                $this->handleError('Error: ' . $e->getMessage());
            }
        } else {
            $this->handleError("No file uploaded or there was an error during upload.");
        }
    }

    private function isUploadValid()
    {
        return isset($this->file) && $this->file['error'] === UPLOAD_ERR_OK;
    }

    private function isValidFile($file)
    {
        // Check if the file matches the format (SK | HC | MK)_(number)_PHOTO_(number).jpg
        return preg_match('/^(SK|HC|MK)_[0-9]+_PHOTO_[0-9]+\.jpg$/', $file);
    }

    private function ensureTargetDirectoryExists()
    {
        if (!file_exists($this->targetDir)) {
            mkdir($this->targetDir, 0755, true);
        }
    }

    private function clearFolder($folderPath)
    {
        $files = glob($folderPath . '*'); // Get all files in the folder
        foreach ($files as $file) {
            is_dir($file) ? $this->deleteFolder($file) : unlink($file);
        }
    }

    private function deleteFolder($folder)
    {
        $files = glob($folder . '/*');
        foreach ($files as $file) {
            is_dir($file) ? $this->deleteFolder($file) : unlink($file);
        }
        rmdir($folder);
    }

    private function moveUploadedFile($destination)
    {
        return move_uploaded_file($this->uploadedFile, $destination);
    }

    private function processZipFile($zipFilePath)
    {
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath) === TRUE) {
            $extractPath = $this->targetDir . pathinfo($this->fileName, PATHINFO_FILENAME) . '/';
            $zip->extractTo($extractPath);
            $zip->close();

            echo "Zip file extracted to: " . $extractPath;

            $this->processExtractedFolders($extractPath);
        } else {
            $this->handleError("Failed to open the zip file.");
        }
    }

    private function processExtractedFolders($extractPath)
    {
        $folderName = pathinfo($this->fileName, PATHINFO_FILENAME);
        $fullPath = $extractPath . 'FOTOS PAG WEB NOMENCLATURA';

        if (is_dir($fullPath)) {
            
            // Show Counter Folder for SK, HC, MK
            $this->countFoldersByPrefix($fullPath);

            // Process Each Folder for SK, HC, MK
            $this->processFoltersByPrefix($fullPath);

        } else {
            $this->handleError("Extracted folder not found.");
            var_dump($fullPath);
        }
    }

    private function processFoltersByPrefix($directory){

        $subfolders = scandir($directory);
        $prefix_counts = [
            'SK' => 0,
            'HC' => 0,
            'MK' => 0
        ];

        foreach ($subfolders as $subfolder) {
            if ($this->isFolder($directory, $subfolder)) {

                $folderPath = $directory . '/' . $subfolder;
                $files = scandir($folderPath);

                $photos = [];

                $ggroup = '';
                $gproductId = '';

                if (preg_match('/^(SK|HC|MK)_(\d+)$/', $subfolder, $matches_subfolder)) {
                    $ggroup = $matches_subfolder[1]; // SK, HC, or MK
                    $gproductId = $matches_subfolder[2]; // Product ID
                }

                // Construct the photo product SKU
                $photoGroup = $ggroup;
                $photoProductId =$gproductId;
                $photoProductSKU = 'BSC:' . $photoGroup . ':' . $photoProductId; // Example: BSC:SK:1

                // Loop over product photo folder
                foreach ($files as $file) {
                    if ($this->isValidFile($file)) {
                        $prefix = $this->getPrefix($file);
                        
                        // Batch Process
                        if (array_key_exists($prefix, $prefix_counts)) {

                            // Increment Counter
                            $prefix_counts[$prefix]++;

                            // Setting filename
                            $photoFilename = $file; // Example: SK_1_PHOTO_1.jpg

                            // Extract the prefix (SK, HC, MK)
                            $photoGroup = $prefix;
                            
                            // Extract the product ID and photo ID using regular expression
                            if (preg_match('/^(SK|HC|MK)_(\d+)_PHOTO_(\d+)\.jpg$/', $photoFilename, $matches)) {
                                $photoGroup = $matches[1]; // SK, HC, or MK
                                $photoProductId = $matches[2]; // Product ID
                                $photoFileId = $matches[3]; // Photo ID

                                // Path to the photo file
                                $photoFilePath = $directory . '/' .$photoGroup.'_'.$photoProductId .'/' . $photoFilename;
                                $photos[] = [ 
                                    'index' => $photoFileId,
                                    'url' => $photoFilePath
                                ];
                            }

                           
                        }


                    }
                }

                // Find Woocomerce product by sku
                $productId = wc_get_product_id_by_sku($photoProductSKU);
                if ($productId) {

                    // Get the full product object
                    $product = wc_get_product($productId);
                    
                    // Delete the previous photos (if any) for that product
                    //$existing_gallery = $product->get_gallery_image_ids();
                    // Delete existing photos from the media library
                    //foreach ($existing_gallery as $image_id) {
                        //wp_delete_attachment($image_id, false);
                    //} 

                    // Upload the photo to the WordPress Media Library
                    $attachmentIds = $this->uploadPhotoToMediaLibrary($photos);

                    
                    if ($attachmentIds) {
                        
                        // Attach the new photo to the product
                        $attachmentId = $attachmentIds[0];

                        $new_gallery = $attachmentIds;
                        $product->set_image_id($attachmentId);
                        $product->set_gallery_image_ids($new_gallery);
                        $product->save();
                    
                        echo "Photo uploaded and attached to product: " . $product->get_name() . "<br>";
                    } else {
                        echo "Failed to upload the photo.<br>";
                    }


                    echo "Product found: " . $product->get_name() . "<br>";
                    echo "__Matching file: " . $file . "<br>";
                    echo "__Photo Group: " . $photoGroup . "<br>";
                    echo "__Photo Product ID: " . $photoProductId . "<br>";
                    echo "__Photo File ID: " . $photoFileId . "<br>";
                    echo "__Photo Product SKU: " . $photoProductSKU . "<br>";
                    echo "<br>";
                } else {
                    echo "Product not found for SKU: " . $photoProductSKU . "<br>";
                }


            }
        }


    }

    private function countFoldersByPrefix($directory)
    {
        $subfolders = scandir($directory);
        $counts = [
            'SK' => 0,
            'HC' => 0,
            'MK' => 0
        ];

        foreach ($subfolders as $subfolder) {
            if ($this->isFolder($directory, $subfolder)) {
                $prefix = $this->getPrefix($subfolder);
                if (array_key_exists($prefix, $counts)) {
                    $counts[$prefix]++;
                }
            }
        }

        $this->printFolderCounts($counts);


    }

    private function isFolder($directory, $subfolder)
    {
        return $subfolder !== '.' && $subfolder !== '..' && is_dir($directory . '/' . $subfolder);
    }

    private function getPrefix($folderName)
    {
        return substr($folderName, 0, 2); // Get the first two characters
    }

    private function printFolderCounts($counts)
    {
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "Number of SK folders: " . $counts['SK'] . "<br>";
        echo "Number of HC folders: " . $counts['HC'] . "<br>";
        echo "Number of MK folders: " . $counts['MK'] . "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
    }

    private function handleError($message)
    {
        echo $message;
    }

    // Function to upload a photo to the WordPress Media Library
    private function uploadPhotoToMediaLibrary($photos)
    {

        $attachmentIds = [];

        foreach($photos as $photo){
            $filePath = $photo['url'];
            $wpFileType = wp_check_filetype(basename($filePath), null);
            $attachment = [
                'guid'           => $filePath,
                'post_mime_type' => $wpFileType['type'],
                'post_title'     => sanitize_file_name(basename($filePath)),
                'post_content'   => '',
                'post_status'    => 'inherit'
            ];

            // Upload the file
            $attachmentId = wp_insert_attachment($attachment, $filePath);
            
            if (!is_wp_error($attachmentId)) {
                // Generate attachment metadata and update the attachment
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attachmentData = wp_generate_attachment_metadata($attachmentId, $filePath);
                wp_update_attachment_metadata($attachmentId, $attachmentData);

                $attachmentIds[] = $attachmentId;
            } else {
                var_dump($attachmentId);
            }
        }

        if(count($attachmentIds) > 0){
            return $attachmentIds;
        }
        
        return false;
        
    }


}
    */

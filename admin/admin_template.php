<?php

include_once plugin_dir_path(__FILE__) . 'admin_ajax_do_reset_categories.php';
include_once plugin_dir_path(__FILE__) . 'admin_ajax_do_seed_categories.php';
include_once plugin_dir_path(__FILE__) . 'admin_ajax_do_reset_products.php';
include_once plugin_dir_path(__FILE__) . 'admin_ajax_do_seed_products.php';


// Classes
//include_once plugin_dir_path(__FILE__) . 'classes/ProductPhotosUploaderClass.php';
include_once plugin_dir_path(__FILE__) . 'classes/BSC_PPU_Config.php';
include_once plugin_dir_path(__FILE__) . 'classes/BSC_PPU_FileManager.php';
include_once plugin_dir_path(__FILE__) . 'classes/BSC_PPU_MediaManager.php';
include_once plugin_dir_path(__FILE__) . 'classes/BSC_PPU_MediaCleaner.php';
include_once plugin_dir_path(__FILE__) . 'classes/BSC_PPU_ProductManager.php';
include_once plugin_dir_path(__FILE__) . 'classes/BSC_PPU_Processor.php';
include_once plugin_dir_path(__FILE__) . 'classes/BSC_PPU_Init.php';




//include_once plugin_dir_path(__FILE__) . 'admin_ajax_do_download_categories.php';


// Function to delete all files and subfolders inside a directory
function clear_folder($folder) {
    $files = glob($folder . '/*'); // Get all files and folders inside the folder
    
    foreach ($files as $file) {
        if (is_dir($file)) {
            // Recursively delete subdirectories and their contents
            clear_folder($file);
            rmdir($file); // Remove the subdirectory itself
        } else {
            // Delete file
            unlink($file);
        }
    }
}


function do_render_admin_template()
{

    // Get the URL to the plugin directory
    $plugin_url = plugins_url('/', __FILE__);

    // Load JavaScript
    //wp_enqueue_script('bsc-filter-multicheck-script', $plugin_url . '../js/bsc_filter_multicheck.js' , array(), '1.0', true);

    // Load CSS
    wp_enqueue_style('bsc-filter-multicheck-style', $plugin_url . '../assets/css/wcapf-admin-styles.css', array(), '1.0');



?>


    <main class="bsc bsc__main">
        <section class="bsc__section section__categories">
            <article class="categories__reset">
                <header>
                    <h1> CATEGORIAS v.1.0.5 CI CD WebHook </h1>
                    <hr>
                </header>
                <content>
                    <form id="deleteForm" class="bsc__form_btn" action="" method="post">
                        <img src="<?php echo plugins_url('/', __FILE__) . '../assets/images/delete.png'; ?>" alt="">
                        <label>
                            <?php
                            if (isset($_POST['delete_categories'])) {
                                try {
                                    $n_categories_deleted = delete_categories_function();
                                    echo $n_categories_deleted . " Categorias Borradas Exitosamente";
                                } catch (Exception $e) {
                                    echo 'Error: ' . $e->getMessage();
                                }
                            } else {
                                echo "Borrar Categorias";
                            }
                            ?>
                        </label>
                        <input type="submit" name="delete_categories" id="delete_categories" value="Submit" style="display: none;">
                    </form>
                 
                    <form id="uploadForm" class="bsc__form_btn" action="" method="post" enctype="multipart/form-data">
                        <img src="<?php echo plugins_url('/', __FILE__) . '../assets/images/file_upload.png'; ?>" alt="">
                        <label>
                            Upload JSON Categorias
                        </label>
                        <input type="file" id="upload_categories_json" name='upload_categories_json' style="width: 100%;"><br>
                        <input type="submit" name="upload_categories" id="upload_categories" value="Submit">
                    </form>
                    <form id="downloadForm" class="bsc__form_btn" action="<?php echo plugin_dir_url(__FILE__); ?>admin_ajax_do_download_categories.php" method="post">
                        <img src="<?php echo plugins_url('/', __FILE__) . '../assets/images/file_download.png'; ?>" alt="">

                            <label>
                            <?php
                            if (isset($_POST['download_categories'])) {
                                try {
                                    $n_categories_seed = download_categories_function();
                                    echo $n_categories_seed . " Categorias Downloaded Exitosamente";
                                } catch (Exception $e) {
                                    echo 'Error: ' . $e->getMessage();
                                }
                            } else {
                                echo "Download Categorias";
                            }
                            ?>
                            </label>
                        <input type="submit" name="download_categories" id="download_categories" value="Submit" style="display: none;">
                    </form>
                </content>
            </article>


            <article class="products__reset">
                <header>
                    <h1> PRODUCTOS </h1>
                    <hr>
                </header>
                <content>
                    <form id="deleteProductsForm" class="bsc__form_btn" action="" method="post">
                        <img src="<?php echo plugins_url('/', __FILE__) . '../assets/images/delete.png'; ?>" alt="">
                        <label>
                            <?php
                            if (isset($_POST['delete_products'])) {
                                try {
                                    $n_products_deleted = delete_products_function();
                                    echo $n_products_deleted . " Productos Borrados Exitosamente";
                                } catch (Exception $e) {
                                    echo 'Error: ' . $e->getMessage();
                                }
                            } else {
                                echo "Borrar Productos";
                            }
                            ?>
                        </label>
                        <input type="submit" name="delete_products" id="delete_products" value="Submit" style="display: none;">
                    </form>
                    <form id="uploadProductsForm" class="bsc__form_btn" action="" method="post" enctype="multipart/form-data">
                        <img src="<?php echo plugins_url('/', __FILE__) . '../assets/images/file_upload.png'; ?>" alt="">
                        <label>
                            Upload JSON Productos
                        </label>
                        <input type="file" id="upload_products_json" name='upload_products_json' style="width: 100%;"><br>
                        <input type="submit" name="upload_products" id="upload_products" value="Submit">
                    </form>





                    <!-- -------------------------------------- -->
                    <!-- -- BSC PROCESS PHOTOS ---------------- -->
                    <!-- -------------------------------------- -->
                    <div class="bsc__process_products_photos">
                        <form id="processProductsPhotosForm" class="bsc__form_btn" action="" method="post">
                            <label>Process Uploaded Photos</label>
                            <input type="submit" name="process_photos_products" id="process_photos_products" value="Start Processing">
                        </form>
                    </div>

                    <!-- -------------------------------------- -->
                    <!-- -- PHP PROCESS FILES ----------------- -->
                    <!-- -------------------------------------- -->
                    <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_photos_products'])) {
                            try {

                                // Get the current domain
                                $currentDomain = $_SERVER['HTTP_HOST'];
                                // Check if the domain includes 'bsc.local'
                                if (strpos($currentDomain, 'bsc.local') !== false) {
                                    BSC_PPU_Init::init();
                                } else {
                                    $command = "wp eval 'BSC_PPU_Init::init();' > /dev/null 2>&1 &";
                                    exec($command);
                                }
                               
                                echo "BSC_PPU_Init is running in the background.";
                                echo '<p>Photos processed successfully.</p>';
                            } catch (Exception $e) {
                                echo '<p style="color:red;">Error: ' . $e->getMessage() . '</p>';
                            }
                        }
                    ?>



                </content>
            </article>

            <?php

    
            if (isset($_FILES['upload_categories_json'])) {
                try {
                    $json = file_get_contents($_FILES['upload_categories_json']['tmp_name']);
                    $categories_json = json_decode($json, true);

                    $n_categories_seed = upload_categories_function($categories_json);
                    echo $n_categories_seed . " Categorias Creadas Exitosamente";

                    after_upload_categories_function();

                } catch (Exception $e) {
                    echo 'Error: ' . $e->getMessage();
                }
            } else {
                echo "Seed Categorias";
            }

            if (isset($_FILES['upload_products_json'])) {
                try {
                    $json = file_get_contents($_FILES['upload_products_json']['tmp_name']);
                    $products_json = json_decode($json, true);

                    $n_products_seed = upload_products_function($products_json);
                    echo $n_products_seed . " Productos Creados Exitosamente";

                } catch (Exception $e) {
                    echo 'Error: ' . $e->getMessage();
                }
            } else {
                echo "Seed Productos";
            }

       
            
            ?>

            <?php
            include_once plugin_dir_path(__FILE__) . 'categories/admin_categories_table.php';
            ?>


        </section>
        <section class="bsc__section section__products">


        </section>
    </main>

    <script>
        document.getElementById('deleteForm').addEventListener('click', function() {
            document.getElementById('delete_categories').click();
        });
        /*
        document.getElementById('seedForm').addEventListener('click', function() {
            document.getElementById('seed_categories').click();
        });
        */
        document.getElementById('downloadForm').addEventListener('click', function() {
            document.getElementById('download_categories').click();
        });
        document.getElementById('deleteProductsForm').addEventListener('click', function() {
            console.log(' ')
            console.log(' deleteProductsForm ')
            console.log(' ')
            console.log(' ')
            document.getElementById('delete_products').click();
        });
       
        /*
        document.getElementById('uploadForm').addEventListener('click', function() {
            document.getElementById('upload_categories').click();
        });
        */
    </script>
<?php
}
?>
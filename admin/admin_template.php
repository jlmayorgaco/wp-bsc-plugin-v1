<?php

include_once plugin_dir_path(__FILE__) . 'admin_ajax_do_reset_categories.php';
include_once plugin_dir_path(__FILE__) . 'admin_ajax_do_seed_categories.php';
include_once plugin_dir_path(__FILE__) . 'admin_ajax_do_reset_products.php';
include_once plugin_dir_path(__FILE__) . 'admin_ajax_do_seed_products.php';

// Classes
include_once plugin_dir_path(__FILE__) . 'classes/ProductPhotosUploaderClass.php';

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
                    <h1> CATEGORIAS v.1.0.7 __ Final deploy.sh CI CD Testing </h1>
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
                    <form id="uploadProductsPhotosForm" class="bsc__form_btn" action="" method="post" enctype="multipart/form-data">
                        <img src="<?php echo plugins_url('../assets/images/photo_upload.png', __FILE__); ?>" alt="">
                        <label >Upload Photos Productos</label>
                        <input type="file" id="upload_photos_products_zip" name="upload_photos_products_zip" style="width: 100%;"><br>
                        <input type="submit" name="upload_photos_products" id="upload_photos_products" value="Submit">
                    </form>
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

            if (isset($_FILES['upload_photos_products_zip']) && $_FILES['upload_photos_products_zip']['error'] == UPLOAD_ERR_OK) {
                try {
                    $zipFile = $_FILES['upload_photos_products_zip'];
                    $photoUploader = new ProductPhotosUploaderClass($zipFile);
                    $photoUploader->uploadAndProcessFile();
                    
                } catch (Exception $e) {
                    echo 'Error: ' . $e->getMessage();
                }
            } else {
                echo "No file uploaded or there was an error during upload.";
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
        document.getElementById('seedForm').addEventListener('click', function() {
            document.getElementById('seed_categories').click();
        });
        document.getElementById('downloadForm').addEventListener('click', function() {
            document.getElementById('download_categories').click();
        });
        document.getElementById('deleteProductsForm').addEventListener('click', function() {
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
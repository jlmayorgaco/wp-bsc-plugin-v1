<?php
        include_once plugin_dir_path( __FILE__ ) . 'widget-bsc-product-filter.php';


        function my_gallery_shortcode() {
            return '<div class="my-gallery">Your gallery code here</div>';
        }
        add_shortcode('my_gallery', 'my_gallery_shortcode');
?>
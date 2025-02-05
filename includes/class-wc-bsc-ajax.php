<?php

   // Get the URL to the plugin directory
   $plugin_url = plugins_url('/', __FILE__);

   // Load JavaScript
   wp_enqueue_script('bsc-ajax-script', $plugin_url . '../widgets/js/bsc__ajax.js', array(), '1.0', true);


?>
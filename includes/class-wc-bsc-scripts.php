<?php

class WC_BSC_Scripts {
    public static function enqueue_scripts() {
        wp_enqueue_script('bsc_wc_shop_product_filters_script', WC_BSC_PLUGIN_URL . 'widgets/js/bsc_wc_shop_product_filters.js', ['jquery'], WC_BSC_PLUGIN_VERSION, true);
        wp_localize_script('bsc_wc_shop_product_filters_script', 'ajax_var', [
            'url'    => admin_url('admin-ajax.php'),
            'nonce'  => wp_create_nonce('my-ajax-nonce'),
            'action' => 'my_ajax_function'
        ]);
    }

    public static function enqueue_styles() {
        wp_enqueue_style('bsc-plugin-styles', WC_BSC_PLUGIN_URL . 'assets/css/wcapf-styles.css', [], '1.0');
    }
}

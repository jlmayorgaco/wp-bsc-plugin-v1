<?php

    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }

    // Access the global variable
    global $MenusByGroupSKU;

    // Import Rendere Services
    include_once WC_BSC_PLUGIN_PATH . 'widgets/widget-bsc-wc-shop-product-filters/_render-form.php';
    include_once WC_BSC_PLUGIN_PATH . 'widgets/widget-bsc-wc-shop-product-filters/_render-update.php';
    include_once WC_BSC_PLUGIN_PATH . 'widgets/widget-bsc-wc-shop-product-filters/_render-widget.php';
    

    class BSC_WC_Shop_Product_Filters_Widget extends WP_Widget
    {
        public function __construct() {
            $widget_id = 'BSC_WC_Shop_Product_Filters';
            $widget_title = __('BSC WC Shop Product Filters', 'textdomain');
            $widget_description = __('Sample widget based on WPBeginner Tutorial', 'textdomain');
            parent::__construct( $widget_id , $widget_title, [
                    'description' => $widget_description,
            ]);
        }
    
        public function widget($args, $instance) {
            $renderer = new BSC_WC_Filter_Widget_Renderer();
            $current_groups = $renderer->getCurrentGroups();
            
            if (!$current_groups) {
                $renderer->render($this, NULL, NULL, NULL);
                return 0;
            }
            
            $sku_group = $current_groups['group'];
            $sku_page = $current_groups['page'];
            $sku_subpage = $current_groups['subpage'];
            $renderer->render($this, $sku_group, $sku_page, $sku_subpage);
            
        }
    
        public function form($instance) {
            $renderer = new BSC_WC_Filter_Form_Renderer();
            $title = !empty($instance['title']) ? $instance['title'] : __('New title', 'textdomain');
            
            $renderer->render($this, $title);
        }
        public function update($new_instance, $old_instance) {
            $renderer = new BSC_WC_Filter_Update_Renderer();
            $instance = [];
            $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
            return $instance;
        }

    }
    
    function BSC_WC_Shop_Product_Filters_load_widget() {
        register_widget('BSC_WC_Shop_Product_Filters_Widget');
    }
    
    add_action('widgets_init', 'BSC_WC_Shop_Product_Filters_load_widget');


    
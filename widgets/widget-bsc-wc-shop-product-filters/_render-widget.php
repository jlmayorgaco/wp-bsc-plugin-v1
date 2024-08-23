<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


include_once WC_BSC_PLUGIN_PATH . 'widgets/lib/_ajax.php';
include_once WC_BSC_PLUGIN_PATH . 'widgets/lib/_config.php';
include_once WC_BSC_PLUGIN_PATH . 'widgets/lib/_helpers.php';
include_once WC_BSC_PLUGIN_PATH . 'widgets/lib/filter_category_multicheck.php';
include_once WC_BSC_PLUGIN_PATH . 'widgets/lib/filter_price_slider.php';


class BSC_WC_Filter_Widget_Renderer {


    public function render($self, $group, $page, $subpage){

        if(!$group || !$page || !$subpage ){
            $this->renderZeroState();
            return 0;
        }

        $this->doRenderFiltersBSC($group, $page, $subpage);
    }

    public function renderZeroState(){
        ?>
            <h2> NOT VALID URL FOR FILTER PAGE </h2>
        <?php
    }

    public function doRenderFiltersBSC($group, $page, $subpage) {
        global $MenusByGroupSKU;

        if (!($group && $page && $subpage)) {
            echo '';
            return false;
        }

        echo '<div class="bsc__widget bsc__product_filter bsc__sticky">';
        
        //echo 'G::' . esc_html($group);
        //echo '<br>';
        //echo 'P::' . esc_html($page);
        //echo '<br>';
        //echo 'SP::' . esc_html($subpage);

        if (isset($MenusByGroupSKU[$group])) {
            $menu = $MenusByGroupSKU[$group];
            foreach ($menu as $menuItem) {
                //  && $menuItem['options']['category_sku'] === $page
                if ($menuItem['type'] === CATEGORY_MULTI_CHECKBOX) {
                    doRenderFilterMultiCheckbox($menuItem, $group, $page, $subpage);

                } elseif ($menuItem['type'] === SLIDER_PRICE) {
                    doRenderFilterSliderPrice($menuItem, $group, $page);
                }
            }
        }

        echo '</div>';
    }


    public function getCurrentGroups() {
        $url = $_SERVER['REQUEST_URI'];
        return parseUrlForGroups($url);
    }
}
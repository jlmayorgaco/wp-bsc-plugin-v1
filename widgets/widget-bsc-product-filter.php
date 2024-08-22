<?php

include_once plugin_dir_path( __FILE__ ) . 'lib/_ajax.php';
include_once plugin_dir_path( __FILE__ ) . 'lib/_config.php';
include_once plugin_dir_path( __FILE__ ) . 'lib/_helpers.php';
include_once plugin_dir_path( __FILE__ ) . 'lib/filter_category_multicheck.php';
include_once plugin_dir_path( __FILE__ ) . 'lib/filter_price_slider.php';


// Access the global variable
global $MenusByGroupSKU;

add_action('widgets_init', 'BSC_WC_Shop_Product_Filters_load_widget');

// Register and load the widget
function BSC_WC_Shop_Product_Filters_load_widget()
{
    register_widget('BSC_WC_Shop_Product_Filters');
}


function doRenderFiltersBSC($group, $page, $subpage)
{

    // Access the global variable
    global $MenusByGroupSKU;

    if(!($group && $page && $subpage)){
        echo '';
        return false;
    }

    echo '<div class="bsc__widget bsc__product_filter bsc__sticky">';

    echo 'G::' . $group;
    echo '<br>';
    echo 'P::' . $page;
    echo '<br>';
    echo 'SP::' . $subpage;
    echo '<br>';
    echo '<br>';

    // Check if the group exists in the menu array
    if (isset($MenusByGroupSKU[$group])) {

        // Retrieve the menu for the specified group
        $menu = $MenusByGroupSKU[$group];

        // Process the menu as needed
        // For example, loop through menu items and render them
        foreach ($menu as $menuItem) {
            if ($menuItem['type'] == CATEGORY_MULTI_CHECKBOX) {
                if(!($menuItem['options']['category_sku'] == $page && $subpage)){
                    doRenderFilterMultiCheckbox($menuItem, $group, $page, $subpage);
                }
            }
            if ($menuItem['type'] == SLIDER_PRICE) {
                doRenderFilterSliderPrice($menuItem, $group, $page);
            }
        }
    }

    echo '</div>';
}

// Creating the widget
class BSC_WC_Shop_Product_Filters extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            // Base ID of your widget
            'BSC_WC_Shop_Product_Filters',

            // Widget name will appear in UI
            __('BSC WC Shop Product Filters', 'textdomain'),

            // Widget description
            [
                'description' => __('Sample widget based on WPBeginner Tutorial', 'textdomain'),
            ]
        );
    }

    public function getCurrentGroups()
    {
        $url = $_SERVER['REQUEST_URI'];

        // Splitting the URL by "/"
        $parts = explode("/", $url);

        // Finding the index of 'product-category'
        $category_index = array_search('product-category', $parts);

        // Getting the part after 'product-category'
        if ($category_index !== false) {
            $current_group = isset($parts[$category_index + 1]) ? $parts[$category_index + 1] : null;
            $current_page = isset($parts[$category_index + 2]) ? $parts[$category_index + 2] : null;
            $current_subpage = isset($parts[$category_index + 3]) ? $parts[$category_index + 3] : null;
            return [
                'group' => $current_group,
                'page' => $current_page,
                'subpage' => $current_subpage,
            ];
        } else {
            return null;
        }
    }

    public function getCurrentCategoriesPage()
    {
        //https://bubblesskincare.com/product-category/skin-care/1-limpiador-aceitoso/
        // return '1-limpiador-aceitoso'
    }

    // Creating widget front-end
    public function widget($args, $instance)
    {

        echo "<h1>MIRA MI PHP</h1>";
        var_dump($this->getCurrentGroups());
        echo "<br>";
        echo "<br>";
        echo "<br>";

        // Get current categories group
        $current_group_and_page = $this->getCurrentGroups();
        if ($current_group_and_page) {
            $sku_group = $current_group_and_page['group'];
            $sku_page = $current_group_and_page['page'];
            $sku_subpage = $current_group_and_page['subpage'];
            doRenderFiltersBSC($sku_group, $sku_page, $sku_subpage);
        }
    }

    // Widget Settings Form
    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('New title', 'textdomain');
        }

        // Widget admin form
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                <?php _e('Title:', 'textdomain'); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
<?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance          = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

        return $instance;
    }

    // Class wpb_widget ends here
}

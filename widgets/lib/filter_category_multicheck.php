<?php

function load_bsc_multicheck_once()
{
    // Get the URL to the plugin directory
    $plugin_url = plugins_url('/', __FILE__);

    // Load JavaScript
    wp_enqueue_script('bsc-filter-multicheck-script', $plugin_url . '../js/bsc_filter_multicheck.js', array(), '1.0', true);

    // Load CSS
    wp_enqueue_style('bsc-filter-multicheck-style', $plugin_url . '../css/bsc_filter_multicheck.css', array(), '1.0');
}

// Hook into wp_enqueue_scripts or admin_enqueue_scripts depending on where you want to load the scripts
add_action('wp_enqueue_scripts', 'load_bsc_multicheck_once');

function doRenderFilterMultiCheckbox($menuItem, $group, $page, $subpage)
{
    $id = 'FilterMultiCheckbox__' . $menuItem['options']['category_sku'];
    $label = $menuItem['label'];
    $slug = $menuItem['options']['category_sku'];
    //$slug = 'hair-care';
    $childs = getQueryChildCategories($slug);
?>
    <details class="bsc_filter_multicheck multicheck__details" id="<?php echo $id; ?>"
        bsc-wcfp-group="<?php echo $group; ?>"
        bsc-wcfp-page="<?php echo $page; ?>"
        bsc-wcfp-subpage="<?php echo $subpage; ?>"
    >
        <summary class="multicheck__summary" bsc-wcfp-summary-slug="<?php echo $slug; ?>">
            <?php echo $label; ?>
        </summary>
        <?php
        if (empty($childs)) {
            echo '<div class="multicheck__zero_state">No child categories found for slug: <br>' . $slug . '</div>';
        }
        ?>
        <ul class="multicheck__list">
            <?php

            // Sort $childs array by count in descending order
            usort($childs, function ($a, $b) {
                return $b->count - $a->count;
            });

            // Get the top N categories
            $N = 1000; // Change this to the number of categories you want
            $top_categories = array_slice($childs, 0, $N);

            // Loop through the top categories
            foreach ($top_categories as $child) {
                $child_id = 'id_' . $child->term_id . '_' . $child->slug;
                $child_slug = $child->slug;
                $child_name = $child->name;
                $child_count = $child->count;
            ?>
                <li class="multicheck__option">
                    <input id="<?php echo $child_id; ?>" type="checkbox" value="false"
                    bsc-wcfp-parent-slug="<?php echo $slug; ?>"
                    bsc-wcfp-current-slug="<?php echo $child_slug; ?>"
                    bsc-wcfp-group-slug="<?php echo $group; ?>"
                    bsc-wcfp-page-slug="<?php echo $page; ?>"
                    <?php
                        if($subpage === $child_slug){
                    ?>
                        bsc-wcfp-subpage-slug="<?php echo $subpage; ?>"
                        disabled 
                        readonly
                        value="1"/
                    <?php
                        }
                    ?>
                >
                    <label for="<?php echo $child_id; ?>"><?php echo $child_name; ?></label>
                </li>
            <?php
            }
            ?>
        </ul>
    </details>
<?php
}

?>
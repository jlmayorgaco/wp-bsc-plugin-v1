<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

function get_product_category_by_slug($slug) {
    // Validate the slug input
    if (!$slug || $slug === '#') {
        return null;
    }

    // Use get_term_by to fetch the product category by slug
    $category = get_term_by('slug', $slug, 'product_cat');
    $categoryParent = null;
    $categoryGrandParent = null;

    if ($category && !is_wp_error($category)) {
        // Check if the category has a parent
        if ($category->parent) {
            $categoryParent = get_term($category->parent, 'product_cat');
        }

        // Check if the parent category also has a parent
        if ($categoryParent && !is_wp_error($categoryParent) && $categoryParent->parent) {
            $categoryGrandParent = get_term($categoryParent->parent, 'product_cat');
        }

        if ($categoryParent &&   $categoryGrandParent){
            $link = '/product-category/'.$categoryGrandParent->slug.'/'.$categoryParent->slug.'/'.$category->slug;
            return $link;
        }
        
        return '';
        
    
    } else {
        // If the category is not found or an error occurs
        echo '<p>Category not found or an error occurred.</p>';
        return '';
    }
}


class WC_BSC_Shortcode {
    public function __construct() {
        // Register the shortcode on initialization
        add_shortcode('bsc_menu', [$this, 'render_custom_menu_shortcode']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_shortcode_styles']);
    }

    public function enqueue_shortcode_styles() {
        // Only enqueue the stylesheet if the shortcode is present on the page
        if (is_singular() && has_shortcode(get_post()->post_content, 'bsc_menu')) {
            wp_enqueue_style(
                'wc-bsc-shortcode-styles',
                plugin_dir_url(__FILE__) . '../shortcuts/style-wc-bsc-shortcode.css',
                array(),
                '1.0',
                'all'
            );
        }
    }

    public function render_custom_menu_shortcode($atts) {
        // Extract the shortcode attributes (e.g., [bsc_menu name="list1"])
        $atts = shortcode_atts(
            array(
                'name' => 'list1',  // Default to list1 if no name is provided
            ),
            $atts,
            'bsc_menu'
        );

        // Define hardcoded lists for demonstration
        $lists = array(
            'list1' => array(
                ['title' => '1. Limpiadores Aceitosos', 'slug' => 'sk-rutina-s1-limpiadores-aceitosos'],
                ['title' => '2. Limpiadores Acuosos', 'slug' => '#'],
                ['title' => '3. Exfoliantes', 'slug' => '#'],
                ['title' => '4. Tónicos', 'slug' => '#'],
                ['title' => '5. Mascarillas', 'slug' => '#'],
                ['title' => '6. Esencias', 'slug' => '#'],
                ['title' => '7. Serums', 'slug' => '#'],
                ['title' => '8. Contorno de Ojos', 'slug' => '#'],
                ['title' => '9. Hidratantes', 'slug' => '#'],
                ['title' => '10. Protectores Solares', 'slug' => '#'],,
                'list2' => array(
                    ['title' => '11. Aceites Faciales', 'slug' => '#'],
                    ['title' => '12. Spot y Patches', 'slug' => '#'],
                    ['title' => '13. Mist y Brumas', 'slug' => '#'],
                    ['title' => '14. Sticks', 'slug' => '#'],
                    ['title' => '15. Labios', 'slug' => '#'],
                    ['title' => '16. Inner Beauty', 'slug' => '#'],
                    ['title' => '17. Accesorios', 'slug' => '#'],
                    ['title' => '18. Minis', 'slug' => '#'],
                )
        );


        // Select the requested list or default to 'list1'
        $selected_list = isset($lists[$atts['name']]) ? $lists[$atts['name']] : $lists['list1'];

        // Generate the HTML for the list
        $output = '<ul class="bsc__custom-menu">';
        foreach ($selected_list as $item) {
            $link = get_product_category_by_slug($item['slug']);
            $output .= '<li><a href="' . esc_url($link ) . '">' . esc_html($item['title']) . '</a></li>';
            
        }

        
        $output .= '</ul>';

        return $output;
    }
}

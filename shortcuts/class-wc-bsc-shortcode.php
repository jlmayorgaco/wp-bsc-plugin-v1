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

            wp_enqueue_style(
                'wc-bsc-shortcode-styles',
                plugin_dir_url(__FILE__) . 'style-wc-bsc-shortcode.css',
                array(),
                '1.0',
                'all'
            );

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
            'sk-rutina-coreana' => array(
                ['title' => '1. Limpiadores Aceitosos', 'slug' => 'sk-rutina-s1-limpiadores-aceitosos'],
                ['title' => '2. Limpiadores Acuosos', 'slug' => 'sk-rutina-s2-limpiadores-acuosos'],
                ['title' => '3. Exfoliantes', 'slug' => 'sk-rutina-s3-exfoliantes'],
                ['title' => '4. Tónicos', 'slug' => 'sk-rutina-s4-tonicos'],
                ['title' => '5. Mascarillas 1', 'slug' => 'sk-rutina-s5-mascarillas-1'],
                ['title' => '5. Mascarillas 2', 'slug' => 'sk-rutina-s5-mascarillas-2'],
                ['title' => '6. Esencias', 'slug' => 'sk-rutina-s6-esencias'],
                ['title' => '7. Serums', 'slug' => 'sk-rutina-s7-serums'],
                ['title' => '8. Contorno de Ojos', 'slug' => 'sk-rutina-s8-contorno-de-ojos'],
                ['title' => '9. Hidratantes', 'slug' => 'sk-rutina-s9-hidratantes'],
                ['title' => '10. Protectores Solares', 'slug' => 'sk-rutina-s10-protectores-solares-crema'],
                ['title' => '10. Protectores Solares', 'slug' => 'sk-rutina-s10-protectores-solares-barrita']
            ),
            'sk-complementos' => array(
                ['title' => '11. Aceites Faciales', 'slug' => 'sk-rutina-s11-complemento-c1-aceites-faciales'],
                ['title' => '12. Spot', 'slug' => 'sk-rutina-s12-complemento-c2-spot'],
                ['title' => '12. Patches', 'slug' => 'sk-rutina-s12-complemento-c3-patches'],
                ['title' => '13. Mist y Brumas', 'slug' => 'sk-rutina-s13-complemento-c4-mist-y-brumas'],
                ['title' => '14. Sticks', 'slug' => 'sk-rutina-s14-complemento-c5-sticks'],
                ['title' => '15. Labios', 'slug' => 'sk-rutina-s15-complemento-c6-labios'],
                ['title' => '16. Inner Beauty', 'slug' => 'sk-rutina-s16-complemento-c7-inner-beauty'],
                ['title' => '17. Accesorios', 'slug' => 'sk-rutina-s17-complemento-c8-accesorios'],
                ['title' => '18. Minis', 'slug' => 'sk-rutina-s18-complemento-c9-minis']
            ),
            'sk-tipos-piel' => array(
                ['title' => 'Piel Seca', 'slug' => 'sk-tipo-piel-seca'],
                ['title' => 'Piel Normal', 'slug' => 'sk-tipo-piel-normal'],
                ['title' => 'Piel Mixta', 'slug' => 'sk-tipo-piel-mixta'],
                ['title' => 'Piel Grasa', 'slug' => 'sk-tipo-piel-grasa']
            ),
            'hc-rutina-coreana' => array(
                ['title' => 'Shampoo', 'slug' => 'hc-rutina-s1-shampoo'],
                ['title' => 'Exfoliantes', 'slug' => 'hc-rutina-s2-exfoliantes'],
                ['title' => 'Mascarillas', 'slug' => 'hc-rutina-s3-mascarillas'],
                ['title' => 'Acondicionadores', 'slug' => 'hc-rutina-s4-acondicionadores'],
                ['title' => 'Tónicos', 'slug' => 'hc-rutina-s5-tonicos'],
                ['title' => 'Serums', 'slug' => 'hc-rutina-s6-serums'],
                ['title' => 'Esencias Leave-in', 'slug' => 'hc-rutina-s7-esencias-leave-in'],
                ['title' => 'Sprays', 'slug' => 'hc-rutina-s8-sprays'],
                ['title' => 'Aceites', 'slug' => 'hc-rutina-s9-aceites']
            ),
            'mk-maquillaje' => array(
                ['title' => 'BB Creams y Bases', 'slug' => 'mk-rutina-p1-bb-creams-y-bases'],
                ['title' => 'Cushions y Refills', 'slug' => 'mk-rutina-p2-cushions-y-refills'],
                ['title' => 'Sombras y Paletas', 'slug' => 'mk-rutina-p3-sombras-y-paletas'],
                ['title' => 'Delineadores', 'slug' => 'mk-rutina-p4-delineadores'],
                ['title' => 'Pestañinas', 'slug' => 'mk-rutina-p5-pestaninas'],
                ['title' => 'Rubores', 'slug' => 'mk-rutina-p6-rubores'],
                ['title' => 'Iluminadores', 'slug' => 'mk-rutina-p7-iluminadores'],
                ['title' => 'Correctores', 'slug' => 'mk-rutina-p8-correctores'],
                ['title' => 'Tintas', 'slug' => 'mk-rutina-p9-tintas'],
                ['title' => 'Labiales', 'slug' => 'mk-rutina-p10-labiales'],
                ['title' => 'Polvos', 'slug' => 'mk-rutina-p11-polvos']
            ),
            'mk-complementos' => array(
                ['title' => 'Cejas', 'slug' => 'mk-rutina-p12-complementos-c1-cejas'],
                ['title' => 'Primers', 'slug' => 'mk-rutina-p13-complementos-c2-primers'],
                ['title' => 'Fijadores', 'slug' => 'mk-rutina-p14-complementos-c3-fijadores'],
                ['title' => 'Brochas', 'slug' => 'mk-rutina-p15-complementos-c4-brochas'],
                ['title' => 'Pestañas', 'slug' => 'mk-rutina-p16-complementos-c5-pestanas']
            )
        );

        // Select the requested list or default to 'list1'
        $selected_list = isset($lists[$atts['name']]) ? $lists[$atts['name']] : [];

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

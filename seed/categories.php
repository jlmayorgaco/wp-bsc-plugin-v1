<?php


// Hook the category creation function into WordPress init action
add_action('init', 'upload_categories_to_woocommerce');

function upload_categories_to_woocommerce()
{

    $categories = array(
        'group-skin-care' => array(
            'name' => 'SKIN CARE',
            'slug' => 'group-skin-care',
            'categories' => array(
                'sk-rutina' => array(
                    'name' => 'Rutina',
                    'slug' => 'sk-rutina',
                    'categories' => array(
                        'sk-rutina-s1-limpiadores-aceitosos' => array(
                            'name' => 'Limpiadores Aceitosos',
                            'slug' => 'sk-rutina-s1-limpiadores-aceitosos'
                        ),
                        'sk-rutina-s2-limpiadores-acuosos' => array(
                            'name' => 'Limpiadores Acuosos',
                            'slug' => 'sk-rutina-s2-limpiadores-acuosos'
                        ),
                        'sk-rutina-s3-exfoliantes' => array(
                            'name' => 'Exfoliantes',
                            'slug' => 'sk-rutina-s3-exfoliantes'
                        ),
                        'sk-rutina-s4-tonicos' => array(
                            'name' => 'Tónicos',
                            'slug' => 'sk-rutina-s4-tonicos'
                        ),
                        'sk-rutina-s5-mascarillas' => array(
                            'name' => 'Mascarillas',
                            'slug' => 'sk-rutina-s5-mascarillas'
                        ),
                        'sk-rutina-s6-esencias' => array(
                            'name' => 'Esencias',
                            'slug' => 'sk-rutina-s6-esencias'
                        ),
                        'sk-rutina-s7-serums' => array(
                            'name' => 'Serums',
                            'slug' => 'sk-rutina-s7-serums'
                        ),
                        'sk-rutina-s8-contorno-de-ojos' => array(
                            'name' => 'Contorno de Ojos',
                            'slug' => 'sk-rutina-s8-contorno-de-ojos'
                        ),
                        'sk-rutina-s9-hidratantes' => array(
                            'name' => 'Hidratantes',
                            'slug' => 'sk-rutina-s9-hidratantes'
                        ),
                        'sk-rutina-s10-protectores-solares' => array(
                            'name' => 'Protectores Solares',
                            'slug' => 'sk-rutina-s10-protectores-solares'
                        )
                    )
                ),
                'sk-complementos' => array(
                    'name' => 'Complementos',
                    'slug' => 'sk-complementos',
                    'categories' => array(
                        'sk-complementos-c1-aceites-faciales' => array(
                            'name' => 'Aceites Faciales',
                            'slug' => 'sk-complementos-c1-aceites-faciales'
                        ),
                        'sk-complementos-c2-spot-y-patches' => array(
                            'name' => 'Spot y Patches',
                            'slug' => 'sk-complementos-c2-spot-y-patches'
                        ),
                        'sk-complementos-c3-mist-y-brumas' => array(
                            'name' => 'Mist y Brumas',
                            'slug' => 'sk-complementos-c3-mist-y-brumas'
                        ),
                        'sk-complementos-c4-sticks' => array(
                            'name' => 'Sticks',
                            'slug' => 'sk-complementos-c4-sticks'
                        ),
                        'sk-complementos-c5-labios' => array(
                            'name' => 'Labios',
                            'slug' => 'sk-complementos-c5-labios'
                        ),
                        'sk-complementos-c6-inner-beauty' => array(
                            'name' => 'Inner Beauty',
                            'slug' => 'sk-complementos-c6-inner-beauty'
                        ),
                        'sk-complementos-c7-accesorios' => array(
                            'name' => 'Accesorios',
                            'slug' => 'sk-complementos-c7-accesorios'
                        ),
                        'sk-complementos-c8-minis' => array(
                            'name' => 'Minis',
                            'slug' => 'sk-complementos-c8-minis'
                        )
                    )
                ),
                'sk-tipo-piel' => array(
                    'name' => 'Tipo de Piel',
                    'slug' => 'sk-tipo-piel',
                    'categories' => array(
                        'sk-tipo-piel-seca' => array(
                            'name' => 'Piel Seca',
                            'slug' => 'sk-tipo-piel-seca'
                        ),
                        'sk-tipo-piel-grasa' => array(
                            'name' => 'Piel Grasa',
                            'slug' => 'sk-tipo-piel-grasa'
                        ),
                        'sk-tipo-piel-mixta' => array(
                            'name' => 'Piel Mixta',
                            'slug' => 'sk-tipo-piel-mixta'
                        ),
                        'sk-tipo-piel-normal' => array(
                            'name' => 'Piel Normal',
                            'slug' => 'sk-tipo-piel-normal'
                        )
                    )
                ),
                'sk-marcas' => array(
                    'name' => 'Marcas',
                    'slug' => 'sk-marcas',
                    'categories' => array(
                        'sk-marca-around-me' => array(
                            'name' => 'Around Me',
                            'slug' => 'sk-marca-around-me'
                        ),
                        'sk-marca-im-from' => array(
                            'name' => 'I\'m From',
                            'slug' => 'sk-marca-im-from'
                        ),
                        'sk-marca-macqueen' => array(
                            'name' => 'Macqueen',
                            'slug' => 'sk-marca-macqueen'
                        ),
                        'sk-marca-make-p-rem' => array(
                            'name' => 'Make P:REM',
                            'slug' => 'sk-marca-make-p-rem'
                        ),
                        'sk-marca-mary-and-may' => array(
                            'name' => 'Mary & May',
                            'slug' => 'sk-marca-mary-and-may'
                        ),
                        'sk-marca-masil' => array(
                            'name' => 'Masil',
                            'slug' => 'sk-marca-masil'
                        ),
                        'sk-marca-mediheal' => array(
                            'name' => 'Mediheal',
                            'slug' => 'sk-marca-mediheal'
                        ),
                        'sk-marca-medipeel' => array(
                            'name' => 'Medipeel',
                            'slug' => 'sk-marca-medipeel'
                        ),
                        'sk-marca-missha' => array(
                            'name' => 'Missha',
                            'slug' => 'sk-marca-missha'
                        ),
                        'sk-marca-nine-less' => array(
                            'name' => 'Nine Less',
                            'slug' => 'sk-marca-nine-less'
                        ),
                        'sk-marca-pyunkang-yul' => array(
                            'name' => 'Pyunkang Yul',
                            'slug' => 'sk-marca-pyunkang-yul'
                        )
                    )
                )
            )
        ),
        'group-hair-care' => array(
            'name' => 'HAIR CARE',
            'slug' => 'group-hair-care',
            'categories' => array(
                'hc-rutina' => array(
                    'name' => 'Rutina',
                    'slug' => 'hc-rutina',
                    'categories' => array(
                        'hc-rutina-s1-shampoo' => array(
                            'name' => 'Shampoo',
                            'slug' => 'hc-rutina-s1-shampoo'
                        ),
                        'hc-rutina-s2-exfoliantes' => array(
                            'name' => 'Exfoliantes',
                            'slug' => 'hc-rutina-s2-exfoliantes'
                        ),
                        'hc-rutina-s3-mascarillas' => array(
                            'name' => 'Mascarillas',
                            'slug' => 'hc-rutina-s3-mascarillas'
                        ),
                        'hc-rutina-s4-acondicionadores' => array(
                            'name' => 'Acondicionadores',
                            'slug' => 'hc-rutina-s4-acondicionadores'
                        ),
                        'hc-rutina-s5-tonicos' => array(
                            'name' => 'Tónicos',
                            'slug' => 'hc-rutina-s5-tonicos'
                        ),
                        'hc-rutina-s6-serums' => array(
                            'name' => 'Serums',
                            'slug' => 'hc-rutina-s6-serums'
                        ),
                        'hc-rutina-s7-esencias-leave-in' => array(
                            'name' => 'Esencias leave-in',
                            'slug' => 'hc-rutina-s7-esencias-leave-in'
                        ),
                        'hc-rutina-s8-sprays' => array(
                            'name' => 'Sprays',
                            'slug' => 'hc-rutina-s8-sprays'
                        ),
                        'hc-rutina-s9-aceites' => array(
                            'name' => 'Aceites',
                            'slug' => 'hc-rutina-s9-aceites'
                        ),
                        'hc-rutina-s10-protectores' => array(
                            'name' => 'Protectores',
                            'slug' => 'hc-rutina-s10-protectores'
                        )
                    )
                ),
                'hc-complementos' => array(
                    'name' => 'Complementos',
                    'slug' => 'hc-complementos',
                    'categories' => array(
                        'hc-complementos-c1-pestanas' => array(
                            'name' => 'Pestañas',
                            'slug' => 'hc-complementos-c1-pestanas'
                        ),
                        'hc-complementos-c2-cepillos' => array(
                            'name' => 'Cepillos',
                            'slug' => 'hc-complementos-c2-cepillos'
                        ),
                        'hc-complementos-c3-cushions' => array(
                            'name' => 'Cushions',
                            'slug' => 'hc-complementos-c3-cushions'
                        ),
                        'hc-complementos-c4-minis' => array(
                            'name' => 'Minis',
                            'slug' => 'hc-complementos-c4-minis'
                        ),
                        'hc-complementos-c5-accesorios' => array(
                            'name' => 'Accesorios',
                            'slug' => 'hc-complementos-c5-accesorios'
                        )
                    )
                ),
                'hc-marca' => array(
                    'name' => 'Marcas',
                    'slug' => 'hc-marca',
                    'categories' => array(
                        'hc-marca-lunabelle' => array(
                            'name' => 'Lunabelle',
                            'slug' => 'hc-marca-lunabelle'
                        ),
                        'hc-marca-glowberry' => array(
                            'name' => 'Glowberry',
                            'slug' => 'hc-marca-glowberry'
                        ),
                        'hc-marca-dewdrop' => array(
                            'name' => 'Dewdrop',
                            'slug' => 'hc-marca-dewdrop'
                        ),
                        'hc-marca-petalista' => array(
                            'name' => 'Petalista',
                            'slug' => 'hc-marca-petalista'
                        ),
                        'hc-marca-bloomberry' => array(
                            'name' => 'Bloomberry',
                            'slug' => 'hc-marca-bloomberry'
                        ),
                        'hc-marca-sunkissed' => array(
                            'name' => 'Sunkissed',
                            'slug' => 'hc-marca-sunkissed'
                        )
                    )
                )
            )
        ),
        'group-make-up' => array(
            'name' => 'MAKE-UP',
            'slug' => 'group-make-up',
            'categories' => array(
                'mk-maquillaje' => array(
                    'name' => 'Maquillaje',
                    'slug' => 'mk-maquillaje',
                    'categories' => array(
                        'mk-maquillaje-s1-bb-creams-y-bases' => array(
                            'name' => 'BB Creams y Bases',
                            'slug' => 'mk-maquillaje-s1-bb-creams-y-bases'
                        ),
                        'mk-maquillaje-s2-cushions-y-refills' => array(
                            'name' => 'Cushions y Refills',
                            'slug' => 'mk-maquillaje-s2-cushions-y-refills'
                        ),
                        'mk-maquillaje-s3-sombras-y-paletas' => array(
                            'name' => 'Sombras y Paletas',
                            'slug' => 'mk-maquillaje-s3-sombras-y-paletas'
                        ),
                        'mk-maquillaje-s4-delineadores' => array(
                            'name' => 'Delineadores',
                            'slug' => 'mk-maquillaje-s4-delineadores'
                        ),
                        'mk-maquillaje-s5-pestaninas' => array(
                            'name' => 'Pestañinas',
                            'slug' => 'mk-maquillaje-s5-pestaninas'
                        ),
                        'mk-maquillaje-s6-rubores' => array(
                            'name' => 'Rubores',
                            'slug' => 'mk-maquillaje-s6-rubores'
                        ),
                        'mk-maquillaje-s7-iluminadores' => array(
                            'name' => 'Iluminadores',
                            'slug' => 'mk-maquillaje-s7-iluminadores'
                        ),
                        'mk-maquillaje-s8-correctores' => array(
                            'name' => 'Correctores',
                            'slug' => 'mk-maquillaje-s8-correctores'
                        ),
                        'mk-maquillaje-s9-tintas' => array(
                            'name' => 'Tintas',
                            'slug' => 'mk-maquillaje-s9-tintas'
                        ),
                        'mk-maquillaje-s10-labiales' => array(
                            'name' => 'Labiales',
                            'slug' => 'mk-maquillaje-s10-labiales'
                        ),
                        'mk-maquillaje-s11-polvos' => array(
                            'name' => 'Polvos',
                            'slug' => 'mk-maquillaje-s11-polvos'
                        )
                    )
                ),
                'mk-complementos' => array(
                    'name' => 'Complementos',
                    'slug' => 'mk-complementos',
                    'categories' => array(
                        'mk-complementos-c1-cejas' => array(
                            'name' => 'Cejas',
                            'slug' => 'mk-complementos-c1-cejas'
                        ),
                        'mk-complementos-c2-primers' => array(
                            'name' => 'Primers',
                            'slug' => 'mk-complementos-c2-primers'
                        ),
                        'mk-complementos-c3-fijadores' => array(
                            'name' => 'Fijadores',
                            'slug' => 'mk-complementos-c3-fijadores'
                        ),
                        'mk-complementos-c4-brochas' => array(
                            'name' => 'Brochas',
                            'slug' => 'mk-complementos-c4-brochas'
                        ),
                        'mk-complementos-c5-pestanas' => array(
                            'name' => 'Pestañas',
                            'slug' => 'mk-complementos-c5-pestanas'
                        )
                    )
                ),
                'mk-marcas' => array(
                    'name' => 'Marcas',
                    'slug' => 'mk-marcas',
                    'categories' => array(
                        'mk-marca-glow-glamour' => array(
                            'name' => 'Glow Glamour',
                            'slug' => 'mk-marca-glow-glamour'
                        ),
                        'mk-marca-luxe-lash' => array(
                            'name' => 'Luxe Lash',
                            'slug' => 'mk-marca-luxe-lash'
                        ),
                        'mk-marca-dream-dazzle' => array(
                            'name' => 'Dream Dazzle',
                            'slug' => 'mk-marca-dream-dazzle'
                        ),
                        'mk-marca-sparkle-siren' => array(
                            'name' => 'Sparkle Siren',
                            'slug' => 'mk-marca-sparkle-siren'
                        ),
                        'mk-marca-flawless-finish' => array(
                            'name' => 'Flawless Finish',
                            'slug' => 'mk-marca-flawless-finish'
                        ),
                        'mk-marca-velvet-vogue' => array(
                            'name' => 'Velvet Vogue',
                            'slug' => 'mk-marca-velvet-vogue'
                        ),
                        'mk-marca-radiant-rose' => array(
                            'name' => 'Radiant Rose',
                            'slug' => 'mk-marca-radiant-rose'
                        ),
                        'mk-marca-chic-cheeks' => array(
                            'name' => 'Chic Cheeks',
                            'slug' => 'mk-marca-chic-cheeks'
                        )
                    )
                )
            )
        )
    );

    do_process_categories($categories);

}

function do_process_categories($categories, $parent_slug = '')
{
    foreach ($categories as $category_slug => $category) {
        // Get category details
        $category_name = $category['name'];
        //$category_slug = $category['slug'];

        $child_exists = term_exists($category_name, 'product_cat');
        if(!$child_exists){
            // Perform your function on the category here
            // For example:
            //echo "Processing category: $category_name (Slug: $category_slug) ";
            //echo "Parent: $parent_slug (Slug: $parent_slug)<br>";
           //do_create_wc_category($category['name'],  $category['slug'],  $parent_slug);
        }
        

        // Recursively process child categories if available
        if (isset($category['categories'])) {
            do_process_categories($category['categories'], $category_slug);
        }
    }
}

function do_create_wc_category($child_name, $child_slug, $parent_slug)
{
    // Check if WooCommerce is active
    if (class_exists('WooCommerce')) {
        // Check if the parent category exists
        $parent_term = term_exists($parent_slug, 'product_cat');
        // If parent category exists, create the child category under it
        if ($parent_term !== 0 && $parent_term !== null) {
            // Get the parent category ID
            $parent_category_id = $parent_term['term_id'];
            // Check if the child category already exists
            $child_exists = term_exists($child_slug, 'product_cat');
            // If child category does not exist, create it
            if ($child_exists === 0 || $child_exists === null) {


                $child_category_id = wp_insert_term(
                    $child_name,   // Child category name
                    'product_cat', // Taxonomy (product category)
                    array(
                        'slug' => $child_slug,       // Optional
                        'parent' => $parent_category_id // Parent category ID
                    )
                );
                if (!is_wp_error($child_category_id)) {
                    //echo "Child category created successfully with ID: " . $child_category_id['term_id'];
                } else {
                    //echo "Error creating child category: " . $child_category_id->get_error_message();
                }
            } else {
                echo "Child category '{$child_name}' already exists.";
            }
        } else {
            echo "Parent category '{$parent_slug}' does not exist.";
        }
    } else {
        echo "WooCommerce is not active.";
    }
}

<?php

    add_action('admin_menu', 'bsc_plugin_setup_menu');

    function bsc_plugin_setup_menu(){
        $page_title = 'BSC Plugin Page';
        $menu_title = 'BSC Plugin';
        $capability = 'manage_options';
        $menu_slug = 'bsc-plugin';
        $callback = 'bsc_admin_init';
        $icon_url = '';
        $position = '';
        add_menu_page(
            $page_title,
            $menu_title,
            $capability,
            $menu_slug,
            $callback,
        );
    }

    include_once plugin_dir_path( __FILE__ ) . 'admin_template.php';

    function bsc_admin_init(){

        // Render Admin Template
        do_render_admin_template();
    }




	add_filter( 'cmb_meta_boxes', 'bhww_core_cpt_metaboxes' );
 
	function bhww_core_cpt_metaboxes( $meta_boxes ) {
	
		//global $prefix;
		$prefix = '_bhww_'; // Prefix for all fields
		
		// Add metaboxes to the 'Product' CPT
		$meta_boxes[] = array(
			'id'         => 'bhww_woo_tabs_metabox',
			'title'      => 'Additional Product Information - <strong>Optional</strong>',
			'pages'      => array( 'product' ), // Which post type to associate with?
			'context'    => 'normal',
			'priority'   => 'default',
			'show_names' => true, 					
			'fields'     => array(
				array(
					'name'    => __( 'Ingredients', 'cmb' ),
					'desc'    => __( 'Anything you enter here will be displayed on the Ingredients tab.', 'cmb' ),
					'id'      => $prefix . 'ingredients_wysiwyg',
					'type'    => 'wysiwyg',
					'options' => array( 'textarea_rows' => 5, ),
				),
				array(
					'name'    => __( 'Benefits', 'cmb' ),
					'desc'    => __( 'Anything you enter here will be displayed on the Benefits tab.', 'cmb' ),
					'id'      => $prefix . 'benefits_wysiwyg',
					'type'    => 'wysiwyg',
					'options' => array( 'textarea_rows' => 5, ),
				),
			),
		);
 
		return $meta_boxes;
		
	}

?>
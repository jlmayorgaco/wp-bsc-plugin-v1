import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

registerBlockType( 'wc-bsc/block-widget', {
    title: __( 'BSC Product Filter', 'bubblesskincare' ),
    description: __( 'A widget to filter WooCommerce products.', 'bubblesskincare' ),
    icon: 'filter',
    category: 'widgets',
    edit: () => <div className="bsc-product-filter">Edit mode content goes here</div>,
    save: () => <div className="bsc-product-filter">Frontend content goes here</div>,
} );
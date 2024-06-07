<?php

// Access the global variable
global $MenusByGroupSKU;

// Define constants for filter types
define('CATEGORY_MULTI_CHECKBOX', 'category-multi-checkbox');
define('SLIDER_PRICE', 'slider-price');

// Define menu items for different categories
$MenuSkinCare = [
    [
        'id' => 0,
        'label' => 'Tipo de Piel',
        'type' => CATEGORY_MULTI_CHECKBOX,
        'options' => [
            'category_sku' => 'sk-tipo-piel'
        ]
    ],
    [
        'id' => 1,
        'label' => 'Necesidad',
        'type' => CATEGORY_MULTI_CHECKBOX,
        'options' => [
            'category_sku' => 'sk-necesidades'
        ]
    ],
    [
        'id' => 2,
        'label' => 'Ingredientes',
        'type' => CATEGORY_MULTI_CHECKBOX,
        'options' => [
            'category_sku' => 'sk-ingredientes'
        ]
    ],
    [
        'id' => 3,
        'label' => 'Marca',
        'type' => CATEGORY_MULTI_CHECKBOX,
        'options' => [
            'category_sku' => 'sk-marcas'
        ]
    ],
    [
        'id' => 4,
        'type' => SLIDER_PRICE,
        'label' => 'Precio',
        'options' => []
    ],
];

$MenuHairCare = [
    [
        'id' => 0,
        'label' => 'Necesidad',
        'type' => CATEGORY_MULTI_CHECKBOX,
        'options' => [
            'category_sku' => 'hair-care-necesidad'
        ]
    ],
    [
        'id' => 1,
        'label' => 'Marca',
        'type' => CATEGORY_MULTI_CHECKBOX,
        'options' => [
            'category_sku' => 'hair-care-marcas'
        ]
    ],
    [
        'id' => 2,
        'type' => SLIDER_PRICE,
        'label' => 'Precio',
        'options' => []
    ],
];

$MenuMakeUp = [
    [
        'id' => 0,
        'label' => 'Marca',
        'type' => CATEGORY_MULTI_CHECKBOX,
        'options' => [
            'category_sku' => 'make-up-marcas'
        ]
    ],
    [
        'id' => 1,
        'type' => SLIDER_PRICE,
        'label' => 'Precio',
        'options' => []
    ],
];

$MenusByGroupSKU = [
    'group-skin-care' => $MenuSkinCare,
    'group-hair-care' => $MenuHairCare,
    'group-make-up' => $MenuMakeUp,
];

?>
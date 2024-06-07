<?php
function getQueryProducts($skuCategoryGroup, $skuCategoryPage){
    // Get products in both categories
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $skuCategoryGroup,
            ),
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $skuCategoryPage,
            ),
        ),
    );

    $products_query = new WP_Query($args);
    return $products_query;
}

function getQueryChildCategories($categorySlug){
   // Get the category object for the given slug
   $category = get_term_by('slug', $categorySlug, 'product_cat');

   // Initialize an empty array to store child categories
   $childCategories = array();

   // If the category exists
   if ($category) {
       // Get the term ID of the category
       $categoryId = $category->term_id;

       // Get child categories of the given category
       $childCategoryIds = get_term_children($categoryId, 'product_cat');

       // Loop through each child category ID
       foreach ($childCategoryIds as $childCategoryId) {
           // Get the child category object
           $childCategory = get_term($childCategoryId, 'product_cat');

           // Add the child category to the array
           $childCategories[] = $childCategory;
       }
   }

   // Return the array of child categories
   return $childCategories;
}
?>
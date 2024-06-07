
// Add this in your JavaScript file (bsc_wc_shop_product_filters.js)

jQuery('#BSC__Form_Filter_Products').change(function() {

    var values = jQuery(this).val();
    var ajaxurl2 = 'http://bsc2.local/wp-admin/admin-ajax.php'

    // Make AJAX request when the document is ready
    jQuery.ajax({
        url: ajaxurl2, // WordPress AJAX URL
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'my_ajax_function' // Action to trigger the AJAX handler
        },
        success: function(response) {
            // Handle successful response
            if (response.success) {
                var data = response.data; // Retrieve data from response
                console.log(data); // Log the data to console
                // Perform any further actions with the received data
            } else {
                console.log('Error:', response.data); // Log error message if any
            }
        },
        error: function(xhr, status, error) {
            // Handle AJAX error
            console.error('AJAX Error:', status, error);
            console.error({xhr});
            console.error({status});
            console.error({error});
        }
    });
});

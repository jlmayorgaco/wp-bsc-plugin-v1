const debounce = (callback, wait) => {
    let timeoutId = null;
    return (...args) => {
      window.clearTimeout(timeoutId);
      timeoutId = window.setTimeout(() => {
        callback(...args);
      }, wait);
    };
  }

  const doRenderProductCardHTML = (doc) => {

 
    const marca = doc.categories_objects.find(obj => obj.slug.includes('marca'));

    const id = doc.id;
    const title = doc.title;
    const priceStr = Number(doc.price).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    const price = `$ ${priceStr}`;
    const permalink = doc.permalink;
    const image = doc.image;
    const categories = doc.categories;

    // Generate HTML for category badges
    const categoryBadges = categories.map(category => `<span class="category-badge">${category}</span>`).join('');
    const categoryKoreanRutine = doc.categories_objects.find(item => item['bsc__rutine_steps']);
    const step = categoryKoreanRutine?.bsc__rutine_steps;
    let stepHTML = ` `;
    let marcaHTML = `<h1 class="product__brand"> _ </h1>`;
    if(marca && marca.name){
        marcaHTML = `<h1 class="product__brand"> ${marca.name} </h1>`
    }

    const buttonAddToCartHTML = `
                                    <div class="product-item__description--button">
                                            <a href="?add-to-cart=${id}" 
                                            data-quantity="1"
                                            data-product-id="${id}"
                                            data-product-sku=""
                                            class="add_to_cart_button--bsc" class2="button product_type_simple add_to_cart_button ajax_add_to_cart" 
                                            aria-label="Añadir al carrito: “${title}”" 
                                            aria-describedby="" rel="nofollow"
                                            >
                                            ¡ Lo Quiero 2!
                                            </a>
                                    </div>
                                    `

    return `<li class="bsc__product product">
                <a class="product__container" href="${permalink}">

                     <div class="product__thumb">
                        <div class="thumb__content">
                            <img src="${image}" alt="">
                        </div>
                        <div class="thumb__hover">
                                <div class="hover__row1 product-item__description--actions"> 
                                  <a href="https://bubblesskincare.com/lista-de-deseos/" 
                                    data-product-id="${id}" 
                                    data-product-type="simple" 
                                    data-wishlist-url="https://bubblesskincare.com/lista-de-deseos/" 
                                    data-browse-wishlist-text="Ver tu lista de deseos" 
                                    class="nova_product_wishlist_btn" rel="nofollow" 
                                    style="opacity: 1; zoom: 1;">
                                        <i class="inova ic-favorite"></i>
                                </a>
                                <a href="${permalink}" class="nova_product_quick_view_btn2" >
                                        <i class="inova ic-zoom"></i>
                                    </a>
                                </div>
                                <div class="hover__row2">
                                    ${buttonAddToCartHTML}
                                </div>
                        </div>
                    </div>
                        
                    <h1 class="product__title"> ${title} </h1>
                    ${marcaHTML}
                    ${stepHTML}
                    <h3 class="product__price"> ${price}  </h3>
                    <div class="category-badges">${categoryBadges}</div>
                </a>

            
            </li>`
  }

  const handleDoAjax = debounce((filterBSC) => {

        const myObject = Object.fromEntries(filterBSC.filtersCategory.entries());
        const uniqueCurrentValues = new Set();
        for (const key in myObject) {
            if (myObject.hasOwnProperty(key)) {
                uniqueCurrentValues.add(myObject[key].current);
            }
        }
        const uniqueCurrentValuesArray = [...uniqueCurrentValues];

        const ajaxPayload = {
            group: filterBSC.filtersGroup,
            page: filterBSC.filtersPage,
            price: filterBSC.filtersPrice,
            categories: [
                filterBSC.filtersSubPage,
                ...uniqueCurrentValuesArray
            ].filter(item => item)
        }


        console.log(' ');
				console.log(' PRE AJAX ajaxPayload ');
				console.log({
                   
                        url: window.location.origin + '/wp-admin/admin-ajax.php', // WordPress AJAX URL
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            action: 'my_ajax_function', // Action to trigger the AJAX handler,
                            payload: ajaxPayload
                        },
                });
				console.log(' ');


        // Make AJAX request when the document is ready
        jQuery.ajax({
            url: window.location.origin + '/wp-admin/admin-ajax.php', // WordPress AJAX URL
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'my_ajax_function', // Action to trigger the AJAX handler,
                payload: ajaxPayload
            },
            success: function(response) {
                // Handle successful response
                if (response.success) {


                    var ajaxData = response.data; // Retrieve data from response
     

                    const html =  ajaxData.map(doc => doRenderProductCardHTML(doc));


                    document.querySelector('ul.products').innerHTML = html.join('');
                    document.querySelector('ul.products').style.cssText += `
                    display: grid !important;
                    `;

                    document.querySelectorAll('.hover__row1 .product__container').forEach(element => {
                        element.remove();
                    });
                } else {
                    console.log('Error:', response.data); // Log error message if any
                    document.querySelector('ul.products').innerHTML = '';
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

  }, 500);

class FiltersBSC {

    constructor(selector) {
        this.selector = selector;
        this.filtersGroup = '';
        this.filtersPage = '';
        this.filtersSubPage = '';
        this.filtersCategory = new Map();
        this.filtersPrice = {
            min: 0,
            max: 100
        }
    }

    doInit(){
        this.doReset();
    }

    setFilterPrice({min, max}){
        this.filtersPrice = {
            min: min,
            max: max
        }
        this.doAjax();
    }

    setGroup(group){
        this.filtersGroup = group;
    }
    setPage(page){
        this.filtersPage = page;
    }
    setSubPage(subpage){
        this.filtersSubPage = subpage;
    }

    doReset(){
        this.filtersCategory = new Map();
    }

    doToogleFilterStatusById(id, payload){

      

        const checklistsCurrentSummaryQuery = `[bsc-wcfp-parent-slug="${payload.summary}"][bsc-wcfp-group-slug="${payload.group}"][bsc-wcfp-page-slug="${payload.page}"]`;
        const checklistCurrentElems = document.querySelectorAll(checklistsCurrentSummaryQuery);

        if(this.filtersCategory.size < payload.limit){
            checklistCurrentElems.forEach(item => {
                if(item.getAttribute('bsc-wcfp-current-slug') !== payload.current){
                    item.disabled = true;
                }

            })
        } else{
            checklistCurrentElems.forEach(item => {
    
                item.disabled = false;

            })
        }



        if(this.filtersCategory.size < payload.limit){
            if(!this.filtersCategory.has(id)){
                this.filtersCategory.set(id, payload);
            } else{
                this.filtersCategory.delete(id);
            }
        } else {
            if(this.filtersCategory.has(id)){
                this.filtersCategory.delete(id);
            }
        }

        console.log(' ')
        console.log(' this.filtersCategory')
        console.log(this.filtersCategory)
        console.log(' ')


        if(this.filtersGroup){
            this.doAjax();
        }
    }

    doSetFilterStatusById(id, payload){

        console.log(' ')
        console.log(' doSetFilterStatusById ')
        console.log(' ')
        console.log({id, payload})
        console.log(' ')
        console.log(' this.filtersCategory ')
        console.log(this.filtersCategory)
        console.log(' ')

        /*

        if(!this.filtersCategory.has(id)){
            this.filtersCategory.set(id, payload);
        } else{
            this.filtersCategory.delete(id);
        }

        if(this.filtersGroup){
            this.doAjax();
        }

        */
    }



    doAjax(){
        handleDoAjax(this);
    }


}

const filtersBSC = new FiltersBSC('.multicheck__option input');
filtersBSC.doInit();




jQuery(document).ready(function() {
    const container = document.querySelector('ul.products');
    if(container){
        const group = document.querySelector('[bsc-wcfp-group]').getAttribute('bsc-wcfp-group')
        const page = document.querySelector('[bsc-wcfp-page]').getAttribute('bsc-wcfp-page')
        const subpage = document.querySelector('[bsc-wcfp-subpage]').getAttribute('bsc-wcfp-subpage')
        console.log({group, page})
        if(group) filtersBSC.setGroup(group);
        if(page) filtersBSC.setPage(page);
        if(subpage){
            filtersBSC.setSubPage(subpage);
            document.querySelectorAll('[bsc-wcfp-subpage-slug="'+subpage+'"]').forEach(item => {
                item.click();
            })
        }

        filtersBSC.doAjax();
    } 

});

jQuery('.multicheck__option input[type="checkbox"]').change(function($event) {

    const parentSlug = $event.target.getAttribute('bsc-wcfp-parent-slug');
    const groupSlug = $event.target.getAttribute('bsc-wcfp-group-slug');
    const currentSlug = $event.target.getAttribute('bsc-wcfp-current-slug');
    const pageSlug = $event.target.getAttribute('bsc-wcfp-page-slug');
    const subPageSlug = $event.target.getAttribute('bsc-wcfp-subpage-slug');

    const limit = $event.target.getAttribute('bsc-wcfp-limit-number');

    const payload = {
        summary: parentSlug,
        current: currentSlug,
        group: groupSlug,
        page: pageSlug,
        subpage: subPageSlug,
    }

    if(limit && Number(limit) > 0 ){
        payload['limit'] = Number(limit);
    }

    console.log(' ')
    console.log(' ')
    console.log(' payload:: $event ')
    console.log(payload)
    console.log(' ')
    console.log(' ')


    filtersBSC.doToogleFilterStatusById($event.target.id, payload);
});


jQuery('.multicheck__option input[type="radio"]').change(function($event) {

    const parentSlug = $event.target.getAttribute('bsc-wcfp-parent-slug');
    const groupSlug = $event.target.getAttribute('bsc-wcfp-group-slug');
    const currentSlug = $event.target.getAttribute('bsc-wcfp-current-slug');
    const pageSlug = $event.target.getAttribute('bsc-wcfp-page-slug');
    const subPageSlug = $event.target.getAttribute('bsc-wcfp-subpage-slug');

    const payload = {
        summary: parentSlug,
        current: currentSlug,
        group: groupSlug,
        page: pageSlug,
        subpage: subPageSlug,
    }

    console.log(' ')
    console.log(' ')
    console.log(' Radio Button :: $event ')
    console.log(payload)
    console.log(' ')
    console.log(' ')

    filtersBSC.doSetFilterStatusById($event.target.id, payload);

})








document.addEventListener("DOMContentLoaded", () => {
    document.body.addEventListener("click", function (event) {
        const addToCartBtn = event.target.closest(".add_to_cart_button--bsc");

        if (addToCartBtn) {
            event.preventDefault(); // Prevent the default link behavior

            const productId = addToCartBtn.getAttribute("data-product-id");
            const quantity = addToCartBtn.getAttribute("data-quantity") || 1;

            // Send AJAX request to add product to cart
            fetch(`${window.location.origin}/?wc-ajax=add_to_cart`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `bsc=2&product_sku=&product_id=${productId}&quantity=${quantity}`,
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error("Error adding product:", data.error);
                } else {
                    updateCartUI();
                }
            })
            .catch(error => console.error("Error in AJAX:", error));
        }
    });

    /**
     * Fetch updated cart fragments from WooCommerce and update the UI.
     */
    function updateCartUI() {
        fetch(`${window.location.origin}/?wc-ajax=get_refreshed_fragments`)
            .then(response => response.json())
            .then(data => {
                if (data?.fragments) {
                    updateCartElement(".cart-total", data.fragments[".cart-total"]);
                    updateCartElement(".cart-count", data.fragments[".cart-count"]);
                    updateWoofcCount(data.fragments[".woofc-count"]);
                }
            })
            .catch(error => console.error("Error updating cart UI:", error));
    }

    /**
     * Updates a given cart-related element's content if data is available.
     * @param {string} selector - The CSS selector for the target element.
     * @param {string} content - The new content to be inserted.
     */
    function updateCartElement(selector, content) {
        const element = document.querySelector(selector);
        if (element && content) {
            element.innerHTML = content;
        }
    }

    /**
     * Updates the WooCommerce Floating Cart (`.woofc-count`) with new data.
     * @param {string} fragmentStr - The HTML fragment from WooCommerce.
     */
    function updateWoofcCount(fragmentStr) {
        if (!fragmentStr) return;

        const elem = document.querySelector(".woofc-count");
        if (!elem) return;

        // Convert the HTML string into an actual DOM element
        const parser = new DOMParser();
        const fragmentDoc = parser.parseFromString(fragmentStr.trim(), "text/html");
        const newElement = fragmentDoc.body.firstChild;

        if (newElement) {
            elem.replaceWith(newElement); // Replace old element with new one
        }
    }

});


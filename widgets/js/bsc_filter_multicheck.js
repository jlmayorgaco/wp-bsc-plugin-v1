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
    const { id, title, permalink, image, image_hover, price, categories_objects, categories, rating, rating_count } = doc;

    const brand = categories_objects.find(obj => obj.slug.includes('marca'))?.name || "_";
    const formattedPrice = `$ ${Number(price).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`;
    const categoryBadges = categories.map(category => `<span class="category-badge">${category}</span>`).join('');

    // Generate star rating HTML
   //const ratingHTML = generateStarRating(rating);
   const ratingHTML = generateStarRating(rating);

    return `
        <li class="bsc__product product">
            <a class="product__container" href="${permalink}">

                <div class="product__thumb">
                    <div class="thumb__content">
                        <img src="${image}" alt="${title}" class="product-img" data-hover="${image_hover}">
                    </div>
                </div>

                <div class="product__rating">
                    ${ratingHTML}
                    <!-- <span class="rating-count">(${rating_count || 0})</span> -->
                </div>

                <h1 class="product__title">${title}</h1>
                <h1 class="product__brand">${brand}</h1>
                <h3 class="product__price">${formattedPrice}</h3>
                <div class="category-badges" style="display:none">${categoryBadges}</div>

                <div class="product-item__description--button">
                    <button data-product-id="${id}" class="add_to_cart_button--bsc">
                        ¡ agregar al carrito !
                    </button>
                    <span class="cart-loading-spinner" style="display:none;"></span>
                    <span class="cart-success-message" style="display:none;">¡Añadido!</span>
                </div>
            </a>
        </li>`;
};

/**
 * Generates star rating HTML based on the rating value.
 * Supports half stars if the rating is a decimal.
 * @param {number} rating - The rating value (0 to 5).
 * @returns {string} - HTML string for the star rating.
 */
const generateStarRating = (rating, ratingCount) => {
    if (!rating) {
        return `<div class="star-rating placeholder"></div>`; // Keeps spacing consistent
    }

    const fullStars = Math.floor(rating);
    const halfStar = rating % 1 !== 0;
    //const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);
    const emptyStars = 5 - fullStars;

    let starsHTML = ''; //http://bsc.local/wp-content/plugins/wp-bsc-plugin-v1/assets/images/bsc__product_placeholder.jpeg

    // Full stars ⭐
    for (let i = 0; i < fullStars; i++) {
        starsHTML += `<i class="star full-star">
            <img class="bsc__heart-icon-rating" src="/wp-content/plugins/wp-bsc-plugin-v1/assets/images/2.png">
        </i>`;
    }

    // Half star ⭐½
    if (halfStar) {
        starsHTML += `<i class="star half-star">
            <img class="bsc__heart-icon-rating" src="/wp-content/plugins/wp-bsc-plugin-v1/assets/images/1.png">
        </i>`;
    }

    // Empty stars ☆
    for (let ik = 0; ik < emptyStars; ik++) {
        starsHTML += `<i class="star empty-star">
             <img class="bsc__heart-icon-rating" 
                   src="http://bsc.local/wp-content/plugins/wp-bsc-plugin-v1/assets/images/1.png">
        </i>`;
    }

    return `<div class="star-rating"> ${starsHTML} </div>`;
};



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

    setTimeout(() => {  onLoadSetupOnHoverCards() }, 2000);

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




function onLoadSetupOnHoverCards() {
    const thumbContents = document.querySelectorAll(".bsc__product .thumb__content");
    thumbContents.forEach((thumbContent) => {
        const img = thumbContent.querySelector(".product-img");
        console.log(' img => ', img)
        // Attach hover event only if data-hover exists
        if (img && img.dataset.hover) {
            thumbContent.addEventListener("mouseover", () => {
                console.log("Image hover start:", img.src);
                img.dataset.original = img.src;  // Store the original src
                img.src = img.dataset.hover;     // Change src to the hover image
                console.log("Image changed to:", img.src);
            });

            thumbContent.addEventListener("mouseout", () => {
                console.log("Image hover end:", img.src);
                img.src = img.dataset.original;  // Restore the original src
                console.log("Image reverted to:", img.src);
            });
        }
    });

}







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
    const title = doc.title;
    const price = `$${doc.price}`;
    const permalink = doc.permalink;
    const image = doc.image;
    const categories = doc.categories;

    // Generate HTML for category badges
    const categoryBadges = categories.map(category => `<span class="category-badge">${category}</span>`).join('');
    const categoryKoreanRutine = doc.categories_objects.find(item => item['bsc__rutine_steps']);
    const step = categoryKoreanRutine?.bsc__rutine_steps;
    let stepHTML = ` `;
    /*
    if(step){
        stepHTML = `<h2 class="product__subtitle"> ${step}</h2>`;
    } else {
        stepHTML = '<h2 class="product__subtitle"> </div>';
    }*/

    console.log({doc})
    return `<li class="bsc__product">
                <a class="product__container" href="${permalink}">
                    <img class="product__thumb" src="${image}" alt="">
                    <h1 class="product__title"> ${title} </h1>
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

                    console.log(' ')
                    console.log(' ajaxPayload')
                    console.log(ajaxPayload)
                    console.log(' ')

                    var ajaxData = response.data; // Retrieve data from response
                    console.log(' ')
                    console.log(' ajaxData')
                    console.log(ajaxData)
                    console.log(' ')

                    const html =  ajaxData.map(doc => doRenderProductCardHTML(doc));

                    console.log(' ')
                    console.log(' html')
                    console.log(html)
                    console.log(' ')


                    document.querySelector('ul.products').innerHTML = html.join('');
                    document.querySelector('ul.products').style.cssText += ';display:flex !important;'

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
        if(!this.filtersCategory.has(id)){
            this.filtersCategory.set(id, payload);
        } else{
            this.filtersCategory.delete(id);
        }

        if(this.filtersGroup){
            this.doAjax();
        }
    }



    doAjax(){
        handleDoAjax(this);
    }


}

const filtersBSC = new FiltersBSC('.multicheck__option input');
filtersBSC.doInit();




jQuery(document).ready(function() {
    const container = document.querySelector('ul.products');
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
    

});

jQuery('.multicheck__option input').change(function($event) {

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

    filtersBSC.doToogleFilterStatusById($event.target.id, payload);
});

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


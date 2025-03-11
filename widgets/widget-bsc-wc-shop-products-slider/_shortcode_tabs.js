document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll(".bsc__tab");
    const tabContents = document.querySelectorAll(".bsc__tab-content");

    tabs.forEach(tab => {
        tab.addEventListener("click", function () {
            const selectedTab = this.getAttribute("data-tab");

            // Remove "active" class from all tabs
            tabs.forEach(t => t.classList.remove("active"));

            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.remove("active");
                content.style.display = "none";
            });

            // Activate the clicked tab
            this.classList.add("active");

            // Show the corresponding tab content
            const selectedContent = document.getElementById("bsc-tab-" + selectedTab);
            if (selectedContent) {
                selectedContent.classList.add("active");
                selectedContent.style.display = "block";
            }
        });
    });

    // Initialize: Show first active tab by default
    const firstActiveTab = document.querySelector(".bsc__tab.active");
    if (firstActiveTab) {
        firstActiveTab.click();
    }
});

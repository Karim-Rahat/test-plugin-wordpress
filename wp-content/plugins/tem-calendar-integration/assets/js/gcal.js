document.addEventListener("DOMContentLoaded", (event) => {
    const eventListGrid = document.querySelector(".event-list-grid");
    const switchers = document.querySelectorAll(".tem-gcal-switch-view");

    function applyMobileView() {
        // Force grid view
        eventListGrid.classList.add("grid-view-active");
        eventListGrid.classList.remove("list-view-active");

        // Hide switcher
        switchers.forEach(btn => btn.style.display = "none");
    }

    function restoreDesktopView() {
        // Show switcher
        switchers.forEach(btn => btn.style.display = "inline-block");
        // Optional: you could restore last selected view here
    }

    function checkWidth() {
        if (window.innerWidth <= 768) {
            applyMobileView();
        } else {
            restoreDesktopView();
        }
    }

    // Initial check on page load
    checkWidth();

    // Check whenever window is resized
    window.addEventListener("resize", checkWidth);

    // Existing switcher click logic (desktop only)
    document.addEventListener("click", (event) => {
        const switchBtn = event.target.closest(".tem-gcal-switch-view");
        if (!switchBtn || window.innerWidth <= 768) return; // ignore clicks on mobile

        const viewType = switchBtn.classList.contains("grid-view") ? "grid" : "list";
        const gridViewIcon = document.querySelector(".tem-gcal-switch-view.grid-view");
        const listViewIcon = document.querySelector(".tem-gcal-switch-view.list-view");

        if (viewType === "grid") {
            gridViewIcon.classList.add("active");
            listViewIcon.classList.remove("active");
            eventListGrid.classList.add("grid-view-active");
            eventListGrid.classList.remove("list-view-active");
        } else {
            listViewIcon.classList.add("active");
            gridViewIcon.classList.remove("active");
            eventListGrid.classList.remove("grid-view-active");
            eventListGrid.classList.add("list-view-active");
        }

    });
    

    console.log(event.target);

});


    // Read more functionality
document.addEventListener("click", (event) => {
    const readMoreBtn = event.target.closest(".readmore");

    if (!readMoreBtn) return; // safely exit if there’s no readmore button
        const cardContent = readMoreBtn.closest(".card-content");
        const description = cardContent.querySelector(".card-description");
        const ellipsis = description.querySelector(".ellipsis");
        const readMoreLabel = readMoreBtn.querySelector(".read-more-label");
        const fullText = description.getAttribute("data-full-text");
        
        if(readMoreLabel.textContent === "Read More") {
            // Expand
            if(ellipsis) ellipsis.style.display = "none";
            description.innerHTML = fullText;
            readMoreLabel.textContent = "Read Less";
            readMoreBtn.classList.add("expanded");
        } else {
            // Collapse
            const truncated = fullText.substring(0, 150);
            description.innerHTML = truncated + '<span class="ellipsis">...</span>';
            description.setAttribute("data-full-text", fullText);
            readMoreLabel.textContent = "Read More";
            readMoreBtn.classList.remove("expanded");
        }
    
});
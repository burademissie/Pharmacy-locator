document.addEventListener("DOMContentLoaded", function() {
    // DOM Elements
    const searchInput = document.getElementById("searchInput");
    const searchButton = document.getElementById("searchButton");
    const resultsSection = document.getElementById("resultsSection");
    const notFoundMessage = document.getElementById("notFoundMessage");
    const autocompleteDropdown = document.getElementById("autocompleteDropdown");

    let debounceTimer;

    // Initialize with not found message hidden
    notFoundMessage.style.display = "none";

    // Perform search function
    async function performSearch() {
        const searchTerm = searchInput.value.trim();
        
        if (!searchTerm) {
            resultsSection.innerHTML = "";
            notFoundMessage.style.display = "none";
            return;
        }

        try {
            // Show loading state
            resultsSection.innerHTML = "<div class='loading'>Searching medicines...</div>";
            
            const response = await fetch(`../php/searchres.php?search=${encodeURIComponent(searchTerm)}`);
            console.log("Search response status:", response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            console.log("Search result:", result);
            
            if (result.status === 'success') {
                if (result.data && result.data.length > 0) {
                    displayResults(result.data);
                    notFoundMessage.style.display = "none";
                } else {
                    resultsSection.innerHTML = "";
                    notFoundMessage.style.display = "flex";
                    console.log("No medicines found in the response");
                }
            } else {
                throw new Error(result.message || 'Unknown error occurred');
            }
        } catch (error) {
            console.error("Search error:", error);
            resultsSection.innerHTML = "";
            notFoundMessage.querySelector("p").textContent = `Error: ${error.message}`;
            notFoundMessage.style.display = "flex";
        }
    }

    // Display results function
    function displayResults(medicines) {
        console.log("Displaying medicines:", medicines);
        resultsSection.innerHTML = "";
        
        medicines.forEach(medicine => {
            const medicineCard = document.createElement("div");
            medicineCard.className = "pharmacy-card";
            medicineCard.innerHTML = `
                <div class="medicine-icon">
                    <i class="fas fa-pills"></i>
                </div>
                <div class="medicine-info">
                    <h3 class="medicine-name">${medicine.medicine_name || 'No Name'}</h3>
                    <p class="medicine-description">${medicine.description || 'No Description'}</p>
                    <div class="medicine-details">
                        <span class="medicine-price">ብር${medicine.price || '0.00'}</span>
                        <span class="medicine-stock">In Stock: ${medicine.quantity || '0'}</span>
                    </div>
                    <div class="pharmacy-info">
                        <div class="pharmacy-name">${medicine.pharmacy_name || 'Unknown Pharmacy'}</div>
                        <div class="pharmacy-location">
                            <i class="fas fa-map-marker-alt"></i> ${medicine.location || 'No Location'}
                        </div>
                        <div class="pharmacy-phone">
                            <i class="fas fa-phone"></i> ${medicine.phone || 'No Phone'}
                        </div>
                    </div>
                </div>
            `;
            resultsSection.appendChild(medicineCard);
        });
    }

    // Debounce function to limit API calls
    function debounce(func, delay) {
        return function(...args) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(this, args), delay);
        };
    }

    // Event listeners
    searchButton.addEventListener("click", performSearch);
    
    // Search on Enter key
    searchInput.addEventListener("keyup", function(e) {
        if (e.key === "Enter") {
            performSearch();
        }
    });

    // Real-time search with debouncing
    searchInput.addEventListener("input", debounce(function() {
        const searchTerm = searchInput.value.trim();
        if (searchTerm.length >= 2) {
            performSearch();
        } else if (searchTerm.length === 0) {
            resultsSection.innerHTML = "";
            notFoundMessage.style.display = "none";
        }
    }, 300));

    // Initial search to show all medicines
    performSearch();
});
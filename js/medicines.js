document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const searchButton = document.getElementById("searchButton");
  const resultsSection = document.getElementById("resultsSection");
  const notFoundMessage = document.getElementById("notFoundMessage");
  const autocompleteDropdown = document.getElementById("autocompleteDropdown");

  let debounceTimer;

  // Debounce function to limit API calls
  function debounce(func, delay) {
    return function (...args) {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => func.apply(this, args), delay);
    };
  }

  function displayAutocompleteSuggestions(medicines) {
    autocompleteDropdown.innerHTML = "";
    
    if (medicines.length === 0) {
      autocompleteDropdown.style.display = "none";
      return;
    }

    medicines.forEach((medicine) => {
      const item = document.createElement("div");
      item.className = "autocomplete-item";
      item.innerHTML = `
        <div class="medicine-icon">
          <i class="fas fa-pills"></i>
        </div>
        <div class="medicine-info">
          <div class="medicine-name">${medicine.medicine_name}</div>
          <div class="medicine-price">₦${medicine.price}</div>
        </div>
      `;

      item.addEventListener("click", () => {
        searchInput.value = medicine.medicine_name;
        autocompleteDropdown.style.display = "none";
        performSearch();
      });

      autocompleteDropdown.appendChild(item);
    });

    autocompleteDropdown.style.display = "block";
  }

  function displayMedicineCards(medicines) {
    resultsSection.innerHTML = "";

    if (medicines.length === 0) {
      notFoundMessage.style.display = "block";
      resultsSection.style.display = "none";
    } else {
      notFoundMessage.style.display = "none";
      resultsSection.style.display = "block";

      medicines.forEach((medicine) => {
        const card = document.createElement("div");
        card.className = "pharmacy-card";
        card.innerHTML = `
          <div class="medicine-icon">
            <i class="fas fa-pills"></i>
          </div>
          <div class="medicine-info">
            <h3 class="medicine-name">${medicine.medicine_name}</h3>
            <p class="medicine-description">${medicine.description}</p>
            <div class="medicine-details">
              <span class="medicine-price">₦${medicine.price}</span>
              <span class="medicine-stock">In Stock: ${medicine.quantity}</span>
            </div>
          </div>
        `;
        resultsSection.appendChild(card);
      });
    }
  }

  async function fetchMedicines(searchTerm) {
    try {
      const response = await fetch(`../php/searchres.php?search=${encodeURIComponent(searchTerm)}`);
      const data = await response.json();
      return data;
    } catch (error) {
      console.error("Error fetching data:", error);
      return [];
    }
  }

  const handleSearch = debounce(async (searchTerm) => {
    if (searchTerm.length < 2) {
      autocompleteDropdown.style.display = "none";
      return;
    }

    const medicines = await fetchMedicines(searchTerm);
    displayAutocompleteSuggestions(medicines);
  }, 300);

  function performSearch() {
    const searchTerm = searchInput.value.trim();
    if (searchTerm === "") {
      displayMedicineCards([]);
      return;
    }

    fetchMedicines(searchTerm).then(displayMedicineCards);
  }

  // Event Listeners
  searchInput.addEventListener("input", (e) => {
    const searchTerm = e.target.value.trim();
    handleSearch(searchTerm);
  });

  searchButton.addEventListener("click", performSearch);
  
  searchInput.addEventListener("keyup", function (e) {
    if (e.key === "Enter") {
      performSearch();
    }
  });

  // Close dropdown when clicking outside
  document.addEventListener("click", (e) => {
    if (!searchInput.contains(e.target) && !autocompleteDropdown.contains(e.target)) {
      autocompleteDropdown.style.display = "none";
    }
  });

  // Close dropdown when pressing Escape
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      autocompleteDropdown.style.display = "none";
    }
  });
});
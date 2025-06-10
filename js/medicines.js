document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const searchButton = document.getElementById("searchButton");
  const resultsSection = document.getElementById("resultsSection");
  const notFoundMessage = document.getElementById("notFoundMessage");

  let currentDetailsModal = null;

  // Function to fetch and display statistics
  async function fetchAndDisplayStats() {
    try {
      const response = await fetch('../php/get_stats.php');
      const data = await response.json();
      
      if (data.status === 'success') {
        const statsCard = document.createElement('div');
        statsCard.className = 'stats-card';
        statsCard.innerHTML = `
          <div class="stat-item">
            <div class="stat-number">${data.total_pharmacies}</div>
            <div class="stat-label">Total Pharmacies</div>
          </div>
          <div class="stat-item">
            <div class="stat-number">${data.total_medicines}</div>
            <div class="stat-label">Total Medicines</div>
          </div>
        `;
        
        // Insert stats card at the beginning of the search section
        const searchSection = document.querySelector('.search-section');
        searchSection.insertBefore(statsCard, searchSection.firstChild);
      }
    } catch (error) {
      console.error('Error fetching statistics:', error);
    }
  }

  // Call fetchAndDisplayStats when the page loads
  fetchAndDisplayStats();

  // Function to close details modal
  function closeDetailsModal() {
    if (currentDetailsModal) {
      currentDetailsModal.classList.remove("active");
      setTimeout(() => {
        currentDetailsModal.remove();
        currentDetailsModal = null;
      }, 300);
    }
  }

  // Function to show medicine details
  function showMedicineDetails(medicine) {
    if (currentDetailsModal) {
      closeDetailsModal();
    }

    const modal = document.createElement("div");
    modal.className = "medicine-details-modal";
    modal.innerHTML = `
      <div class="medicine-details-content">
        <div class="medicine-details-header">
          <h3>${medicine.medicine_name}</h3>
          <button class="close-details" type="button">&times;</button>
        </div>
        <div class="medicine-details-info">
          <p><strong>Brand:</strong> ${medicine.brand_name}</p>
          <p><strong>Description:</strong> ${medicine.description}</p>
          <p><strong>Price:</strong> ${medicine.price} ብር</p>
          <p><strong>Quantity:</strong> ${medicine.quantity}</p>
          <p><strong>Form:</strong> ${medicine.form}</p>
          <p><strong>Pharmacy:</strong> ${medicine.pharmacy_name}</p>
          <p><strong>Location:</strong> ${medicine.location}</p>
          <p><strong>Phone:</strong> ${medicine.phone}</p>
        </div>
      </div>
    `;

    document.body.appendChild(modal);
    currentDetailsModal = modal;

    // Show modal with animation
    requestAnimationFrame(() => {
      modal.classList.add("active");
    });

    // Add event listeners
    const closeBtn = modal.querySelector(".close-details");
    if (closeBtn) {
      closeBtn.addEventListener("click", (e) => {
        e.preventDefault();
        closeDetailsModal();
      });
    }
  }

  function displayMedicineCards(medicines) {
    resultsSection.innerHTML = "";

    if (medicines.length === 0) {
      notFoundMessage.style.display = "block";
      resultsSection.style.display = "none";
      return;
    }

      notFoundMessage.style.display = "none";
    resultsSection.style.display = "grid";

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
            <span class="medicine-price">ብር${medicine.price}</span>
              <span class="medicine-stock">In Stock: ${medicine.quantity}</span>
            </div>
          </div>
        `;

      // Add click event to show details
      card.addEventListener("click", () => {
        showMedicineDetails(medicine);
      });

      resultsSection.appendChild(card);
    });
  }

  function performSearch() {
    const searchTerm = searchInput.value.trim();

    if (searchTerm === "") {
      displayMedicineCards([]);
      return;
    }

    fetch(`../php/searchres.php?search=${encodeURIComponent(searchTerm)}`)
      .then((response) => response.json())
      .then((data) => {
        console.log("Fetched data:", data);
        displayMedicineCards(data);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
        displayMedicineCards([]);
      });
  }

  searchButton.addEventListener("click", performSearch);
  searchInput.addEventListener("keyup", function (e) {
    if (e.key === "Enter") {
      performSearch();
    }
  });

  // Close modals when clicking outside
  document.addEventListener("click", (e) => {
    if (currentDetailsModal && e.target === currentDetailsModal) {
      closeDetailsModal();
    }
  });

  // Close modals when pressing Escape key
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      if (currentDetailsModal) closeDetailsModal();
    }
  });
});
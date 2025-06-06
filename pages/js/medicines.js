// Sample medicine data
const medicineData = [
  {
    name: "Paracetamol",
    description: "Pain reliever and fever reducer",
    price: "₦500",
    stock: 25,
  },
  {
    name: "Ibuprofen",
    description: "Anti-inflammatory pain reliever",
    price: "₦750",
    stock: 12,
  },
  {
    name: "Amoxicillin",
    description: "Antibiotic for bacterial infections",
    price: "₦1200",
    stock: 8,
  },
  {
    name: "Cetirizine",
    description: "Antihistamine for allergy relief",
    price: "₦600",
    stock: 18,
  },
];

document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const searchButton = document.getElementById("searchButton");
  const resultsSection = document.getElementById("resultsSection");
  const notFoundMessage = document.getElementById("notFoundMessage");

  function displayMedicineCards(medicines) {
    // Clear previous results
    resultsSection.innerHTML = "";

    if (medicines.length === 0) {
      // Show not found message
      notFoundMessage.style.display = "block";
      resultsSection.style.display = "grid";
    } else {
      // Hide not found message
      notFoundMessage.style.display = "none";

      // Show results section
      resultsSection.style.display = "grid";

      // Add medicine cards
      medicines.forEach((medicine) => {
        const card = document.createElement("div");
        card.className = "medicine-card";
        card.innerHTML = `
                    <div class="medicine-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <div class="medicine-info">
                        <h3 class="medicine-name">${medicine.name}</h3>
                        <p class="medicine-description">${medicine.description}</p>
                        <div class="medicine-details">
                            <span class="medicine-price">${medicine.price}</span>
                            <span class="medicine-stock">In Stock: ${medicine.stock}</span>
                        </div>
                    </div>
                `;
        resultsSection.appendChild(card);
      });
    }
  }

  function performSearch() {
    const searchTerm = searchInput.value.toLowerCase().trim();

    // Filter medicines based on search term
    const filteredMedicines = medicineData.filter((medicine) =>
      medicine.name.toLowerCase().includes(searchTerm)
    );

    // Display results
    displayMedicineCards(filteredMedicines);
  }

  // Event listeners
  searchButton.addEventListener("click", performSearch);
  searchInput.addEventListener("keyup", function (e) {
    if (e.key === "Enter") {
      performSearch();
    }
  });

  // Initialize with all medicines
  displayMedicineCards(medicineData);
});

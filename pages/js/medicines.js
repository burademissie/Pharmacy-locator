document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const searchButton = document.getElementById("searchButton");
  const resultsSection = document.getElementById("resultsSection");
  const notFoundMessage = document.getElementById("notFoundMessage");

  function displayMedicineCards(medicines) {
    resultsSection.innerHTML = "";

    if (medicines.length === 0) {
      notFoundMessage.style.display = "block";
      resultsSection.style.display = "none";
    } else {
      notFoundMessage.style.display = "none";
      resultsSection.style.display = "grid";

      medicines.forEach((medicine) => {
        const card = document.createElement("div");
        card.className = "medicine-card";
        card.innerHTML = `
          <div class="medicine-icon">
            <i class="fas fa-hospital"></i>
          </div>
          // <div class="medicine-info">
          //   <h3 class="medicine-name">${medicine.medicine_name}</h3>
          //   <p class="medicine-description">${medicine.description}</p>
            // <div class="medicine-details">
            //   <span class="medicine-price">₦${medicine.price}</span>
            //   <span class="medicine-stock">In Stock: ${medicine.quantity}</span>
            // </div>
          </div>
        `;
        resultsSection.appendChild(card);
      });
    }
  }

  function performSearch() {
    const searchTerm = searchInput.value.trim();

    if (searchTerm === "") {
      displayMedicineCards([]);
      return;
    }

    // 🔗 Adjust this path if needed!
    fetch(`../php/searchres.php?search=${encodeURIComponent(searchTerm)}`)
      .then((response) => response.json())
      .then((data) => {
        console.log("Fetched data:", data);
        displayMedicineCards(data);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  }

  searchButton.addEventListener("click", performSearch);
  searchInput.addEventListener("keyup", function (e) {
    if (e.key === "Enter") {
      performSearch();
    }
  });

  // Optionally: load all medicines initially
  // performSearch(); // if you want to show all by default
});

document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const searchButton = document.getElementById("searchButton");
  const resultsSection = document.getElementById("resultsSection");
  const notFoundMessage = document.getElementById("notFoundMessage");

  function displayMedicineCards(Pharmacies) {
    resultsSection.innerHTML = "";

    if (Pharmacies.length === 0) {
      notFoundMessage.style.display = "block";
      resultsSection.style.display = "none";
    } else {
      notFoundMessage.style.display = "none";
      resultsSection.style.display = "block";

      Pharmacies.forEach((pharmacy) => {
        const card = document.createElement("div");
        card.className = "pharmacy-card";
        card.innerHTML = `
          <div class="pharmacy-icon">
            <i class="fas fa-hospital"></i>
          </div>
          <div class="pharmacy-info">
            <h3 class="pharmacy-name">${pharmacy.medicine_name}</h3>
            <div class="pharmacy-details">
              <span class="pharmacy-currently">Currently: ${pharmacy.quantity}</span>
              <span class="pharmacy-location">Location: ${pharmacy.price}</span>

            </div>
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

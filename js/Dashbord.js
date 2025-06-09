document.addEventListener("DOMContentLoaded", function () {
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
    const searchTerm = "";
    fetch(`../php/searchres.php?search=${encodeURIComponent(searchTerm)}`)
      .then((response) => response.json())
      .then((data) => {
        console.log("Fetched data:", data);
        if (Array.isArray(data)) {
          displayMedicineCards(data);
        } else {
          displayMedicineCards([]);
        }
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
        displayMedicineCards([]);
      });
  }
  window.addEventListener("load", function () {
    performSearch();
  });
});
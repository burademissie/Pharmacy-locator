const medicines = [
  {
    id: "med001",
    name: "Paracetamol",
    description: "Pain reliever and fever reducer",
    price: 5.99,
    quantity: 120,
    brand: "Panadol",
    form: "Tablets",
    expiry: "2024-12-31",
  },
  {
    id: "med002",
    name: "Ibuprofen",
    description: "Anti-inflammatory pain reliever",
    price: 7.49,
    quantity: 85,
    brand: "Advil",
    form: "Capsules",
    expiry: "2025-06-30",
  },
  {
    id: "med003",
    name: "Amoxicillin",
    description: "Antibiotic for bacterial infections",
    price: 12.99,
    quantity: 42,
    brand: "Amoxil",
    form: "Capsules",
    expiry: "2024-09-15",
  },
  {
    id: "med004",
    name: "Loratadine",
    description: "Antihistamine for allergy relief",
    price: 8.25,
    quantity: 63,
    brand: "Claritin",
    form: "Tablets",
    expiry: "2025-03-20",
  },
  {
    id: "med005",
    name: "Omeprazole",
    description: "Acid reducer for heartburn",
    price: 14.5,
    quantity: 37,
    brand: "Prilosec",
    form: "Capsules",
    expiry: "2024-11-30",
  },
  {
    id: "med006",
    name: "Diphenhydramine",
    description: "Antihistamine for allergies and sleep aid",
    price: 6.75,
    quantity: 91,
    brand: "Benadryl",
    form: "Tablets",
    expiry: "2025-01-15",
  },
];



function renderMedicines() {
  const list = document.getElementById("medicinesList");
  const notFound = document.getElementById("notFoundMessage");
  list.innerHTML = "";

  if (medicines.length === 0) {
    list.style.display = "none";
    notFound.style.display = "block";
  } else {
    list.style.display = "grid";
    notFound.style.display = "none";

    medicines.forEach((med) => {
      const card = document.createElement("div");
      card.className = "pharmacy-card";
      card.innerHTML = `
        <div style="flex:1; min-width:0;">
          <div class="pharmacy-name" style="font-size:1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
            ${
              med.name
            } <span style="font-size:0.7rem; color:rgba(255,255,255,0.6)">(${
        med.brand
      })</span>
          </div>
          <div class="pharmacy-description" style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
            ${med.description}
          </div>
          <div class="pharmacy-details">
            <span>Price: $${med.price.toFixed(2)}</span>
            <span>Qty: ${med.quantity}</span>
            <span>Form: ${med.form}</span>
          </div>
        </div>
        <button class="update-btn" data-medicine-id="${med.id}">Update</button>
      `;
      list.appendChild(card);
    });

    // Add event listeners to all update buttons
    document.querySelectorAll(".update-btn").forEach((button) => {
      button.addEventListener("click", function () {
        const medId = this.getAttribute("data-medicine-id");
        openUpdateModal(medId);
      });
    });
  }
}

function openUpdateModal(medId) {
  const medicine = medicines.find((m) => m.id === medId);

  const modal = document.createElement("div");
  modal.className = "update-modal";
  modal.innerHTML = `
    <div class="modal-content">
      <div class="modal-header">
        <h3>Update ${medicine.name}</h3>
        <button class="close-modal">&times;</button>
      </div>
      <form class="modal-form" id="updateForm">
        <div>
          <label for="price">Price ($)</label>
          <input type="number" id="price" step="0.01" value="${medicine.price}" required>
        </div>
        <div>
          <label for="quantity">Quantity</label>
          <input type="number" id="quantity" value="${medicine.quantity}" required>
        </div>
        <div>
          <label for="expiry">Expiry Date</label>
          <input type="date" id="expiry" value="${medicine.expiry}" required>
        </div>
        <button type="submit" class="save-btn">Save Changes</button>
      </form>
    </div>
  `;

  document.body.appendChild(modal);

  // Show modal
  setTimeout(() => modal.classList.add("active"), 10);

  // Close modal when clicking X or outside
  modal.querySelector(".close-modal").addEventListener("click", closeModal);
  modal.addEventListener("click", (e) => {
    if (e.target === modal) closeModal();
  });

  // Handle form submission
  modal.querySelector("#updateForm").addEventListener("submit", (e) => {
    e.preventDefault();
    updateMedicine(medId);
  });

  function closeModal() {
    modal.classList.remove("active");
    setTimeout(() => modal.remove(), 300);
  }

  function updateMedicine(id) {
    const form = document.getElementById("updateForm");
    const medIndex = medicines.findIndex((m) => m.id === id);

    medicines[medIndex] = {
      ...medicines[medIndex],
      price: parseFloat(form.querySelector("#price").value),
      quantity: parseInt(form.querySelector("#quantity").value),
      expiry: form.querySelector("#expiry").value,
    };

    closeModal();
    renderMedicines(); // Refresh the list

    // In a real app, you would send data to your backend here:
    // fetch(`/api/medicines/${id}`, {
    //   method: 'PUT',
    //   body: JSON.stringify(updatedData),
    //   headers: { 'Content-Type': 'application/json' }
    // })
  }
}

// Initialize the page
document.addEventListener("DOMContentLoaded", renderMedicines);

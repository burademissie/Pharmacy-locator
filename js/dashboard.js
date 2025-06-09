// Global variables for modal management
let currentModal = null;

// Function to close modal
function closeModal() {
    if (currentModal) {
        currentModal.classList.remove("active");
        setTimeout(() => {
            currentModal.remove();
            currentModal = null;
        }, 300);
    }
}

// Function to show error message
function showError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    document.body.appendChild(errorDiv);
    setTimeout(() => errorDiv.remove(), 3000);
}

document.addEventListener("DOMContentLoaded", function() {
    // Add event listeners to all update buttons
    document.querySelectorAll(".update-btn").forEach((button) => {
        button.addEventListener("click", function(e) {
            e.preventDefault();
            const medId = this.getAttribute("data-medicine-id");
            if (medId) {
                openUpdateModal(medId);
            } else {
                showError("Invalid medicine ID");
            }
        });
    });

    // Close modal when clicking outside
    document.addEventListener("click", function(e) {
        if (currentModal && e.target === currentModal) {
            closeModal();
        }
    });

    // Close modal when pressing Escape key
    document.addEventListener("keydown", function(e) {
        if (e.key === "Escape" && currentModal) {
            closeModal();
        }
    });
});

function openUpdateModal(medId) {
    try {
        const card = document.querySelector(`[data-medicine-id="${medId}"]`).closest('.pharmacy-card');
        if (!card) {
            throw new Error("Medicine card not found");
        }

        const medicineName = card.querySelector('.pharmacy-name').textContent.trim();
        const priceText = card.querySelector('.pharmacy-details span:nth-child(1)').textContent;
        const quantityText = card.querySelector('.pharmacy-details span:nth-child(2)').textContent;

        const price = parseFloat(priceText.replace('Price: ₦', '').trim());
        const quantity = parseInt(quantityText.replace('Qty: ', '').trim());

        if (isNaN(price) || isNaN(quantity)) {
            throw new Error("Invalid price or quantity format");
        }

        // Remove any existing modal
        if (currentModal) {
            closeModal();
        }

        // Create new modal
        const modal = document.createElement("div");
        modal.className = "update-modal";
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Update ${medicineName}</h3>
                    <button class="close-modal" type="button">&times;</button>
                </div>
                <form class="modal-form" id="updateForm">
                    <div>
                        <label for="price">Price (₦)</label>
                        <input type="number" id="price" step="0.01" value="${price}" required min="0">
                    </div>
                    <div>
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" value="${quantity}" required min="0">
                    </div>
                    <div>
                        <label for="expire_date">Expiry Date</label>
                        <input type="date" id="expire_date" required>
                    </div>
                    <button type="submit" class="save-btn">Save Changes</button>
                </form>
            </div>
        `;

        document.body.appendChild(modal);
        currentModal = modal;

        // Show modal with animation
        requestAnimationFrame(() => {
            modal.classList.add("active");
        });

        // Add event listeners
        const closeBtn = modal.querySelector(".close-modal");
        if (closeBtn) {
            closeBtn.addEventListener("click", (e) => {
                e.preventDefault();
                closeModal();
            });
        }

        const form = modal.querySelector("#updateForm");
        if (form) {
            form.addEventListener("submit", async (e) => {
                e.preventDefault();
                await updateMedicine(medId, form);
            });
        }

    } catch (error) {
        console.error("Error opening update modal:", error);
        showError(error.message || "Error opening update form");
    }
}

async function updateMedicine(id, form) {
    try {
        const price = parseFloat(form.querySelector("#price").value);
        const quantity = parseInt(form.querySelector("#quantity").value);
        const expireDate = form.querySelector("#expire_date").value;

        if (isNaN(price) || isNaN(quantity) || price < 0 || quantity < 0) {
            throw new Error("Invalid price or quantity values");
        }

        if (!expireDate) {
            throw new Error("Expiry date is required");
        }

        const data = {
            medicine_id: id,
            price: price,
            quantity: quantity,
            expire_date: expireDate
        };

        const response = await fetch('../php/update_stock.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.error) {
            throw new Error(result.error);
        }

        // Close modal before reloading
        closeModal();
        
        // Show success message
        showError("Medicine updated successfully!");
        
        // Reload page after a short delay
        setTimeout(() => {
            window.location.reload();
        }, 1000);

    } catch (error) {
        console.error("Error updating medicine:", error);
        showError(error.message || "Error updating medicine");
    }
}

// Add CSS for the modal and error message
const style = document.createElement('style');
style.textContent = `
    .update-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .update-modal.active {
        display: flex;
        opacity: 1;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: var(--card-bg);
        padding: 2rem;
        border-radius: 1rem;
        width: 90%;
        max-width: 500px;
        position: relative;
        transform: translateY(-20px);
        transition: transform 0.3s ease;
    }

    .update-modal.active .modal-content {
        transform: translateY(0);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .modal-header h3 {
        color: var(--gradient-start);
        margin: 0;
    }

    .close-modal {
        background: none;
        border: none;
        color: #666;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0.5rem;
        transition: color 0.3s ease;
    }

    .close-modal:hover {
        color: var(--gradient-start);
    }

    .modal-form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .modal-form div {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .modal-form label {
        color: #666;
        font-size: 0.9rem;
    }

    .modal-form input {
        padding: 0.8rem;
        border: 1px solid rgba(98, 225, 241, 0.2);
        border-radius: 0.5rem;
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .modal-form input:focus {
        outline: none;
        border-color: var(--gradient-start);
    }

    .modal-form input:invalid {
        border-color: #ff4444;
    }

    .save-btn {
        margin-top: 1rem;
        padding: 0.8rem;
        background: linear-gradient(to right, var(--gradient-start), var(--gradient-end));
        color: white;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        font-weight: 600;
        transition: opacity 0.3s ease;
    }

    .save-btn:hover {
        opacity: 0.9;
    }

    .save-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .error-message {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 2rem;
        background: #ff4444;
        color: white;
        border-radius: 0.5rem;
        z-index: 1100;
        animation: slideIn 0.3s ease, fadeOut 0.3s ease 2.7s;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

function About() {
  window.scrollTo({
    top: document.body.scrollHeight * 0.3,
    behavior: 'smooth'
  });
}

function Contact() {
  window.scrollTo({
    top: document.body.scrollHeight * 0.9999,
    behavior: 'smooth' 
  });
}

console.log("take this ")

document.addEventListener('DOMContentLoaded', function() {
    // Fetch statistics from the server
    fetch('../php/get_stats.php')
        .then(response => response.json())
        .then(data => {
            // Update the statistics in the hero section
            const statsContainer = document.createElement('div');
            statsContainer.className = 'hero-stats';
            statsContainer.innerHTML = `
                <div class="stat-item">
                    <i class="fas fa-pills"></i>
                    <span class="stat-value">${data.total_medicines}+</span>
                    <span class="stat-label">Medicines</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-clinic-medical"></i>
                    <span class="stat-value">${data.total_pharmacies}+</span>
                    <span class="stat-label">Pharmacies</span>
                </div>
            `;
            
            // Insert stats after the hero description
            const heroDescription = document.querySelector('.hero-description');
            heroDescription.parentNode.insertBefore(statsContainer, heroDescription.nextSibling);
        })
        .catch(error => {
            console.error('Error fetching statistics:', error);
        });
});
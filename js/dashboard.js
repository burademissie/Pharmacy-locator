// Add active class to current navigation item
document.addEventListener('DOMContentLoaded', () => {
    const navItems = document.querySelectorAll('.sidebar-nav li');
    
    navItems.forEach(item => {
        item.addEventListener('click', () => {
            // Remove active class from all items
            navItems.forEach(nav => nav.classList.remove('active'));
            // Add active class to clicked item
            item.classList.add('active');
        });
    });

    // Animate stats numbers on page load
    const statsValues = document.querySelectorAll('.stats-value');
    statsValues.forEach(stat => {
        const finalValue = stat.textContent;
        let startValue = 0;
        const duration = 2000; // Animation duration in milliseconds
        const interval = 50; // Update interval in milliseconds
        const steps = duration / interval;
        const increment = parseInt(finalValue) / steps;

        const counter = setInterval(() => {
            startValue += increment;
            if (startValue >= parseInt(finalValue)) {
                stat.textContent = finalValue;
                clearInterval(counter);
            } else {
                stat.textContent = Math.floor(startValue) + '+';
            }
        }, interval);
    });
}); 
document.addEventListener('DOMContentLoaded', () => {
    const signinForm = document.getElementById('signin-form');
    const signupForm = document.getElementById('signup-form');

    if (signinForm) {
        signinForm.addEventListener('submit', handleSignIn);
    }

    if (signupForm) {
        signupForm.addEventListener('submit', handleSignUp);
    }
});

function handleSignIn(e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const remember = document.getElementById('remember').checked;

    // Basic validation
    if (!validateEmail(email)) {
        showError('Please enter a valid email address');
        return;
    }

    if (!password) {
        showError('Please enter your password');
        return;
    }

    // Here you would typically make an API call to your backend
    console.log('Sign in attempt:', { email, password, remember });
    
    // For demo purposes, redirect to landing page
    // Replace this with your actual authentication logic
    window.location.href = 'Landing.htm';
}

function handleSignUp(e) {
    e.preventDefault();
    
    const fullname = document.getElementById('fullname').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    const terms = document.getElementById('terms').checked;

    // Basic validation
    if (!fullname) {
        showError('Please enter your full name');
        return;
    }

    if (!validateEmail(email)) {
        showError('Please enter a valid email address');
        return;
    }

    if (password.length < 8) {
        showError('Password must be at least 8 characters long');
        return;
    }

    if (password !== confirmPassword) {
        showError('Passwords do not match');
        return;
    }

    if (!terms) {
        showError('Please accept the Terms & Conditions');
        return;
    }

    // Here you would typically make an API call to your backend
    console.log('Sign up attempt:', { fullname, email, password });
    
    // For demo purposes, redirect to signin page
    // Replace this with your actual registration logic
    window.location.href = 'signin.html';
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function showError(message) {
    // You can enhance this to show errors in a more user-friendly way
    alert(message);
} 
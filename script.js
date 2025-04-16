document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navLinks = document.querySelector('.nav-links');
    
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            navLinks.classList.toggle('active');
        });
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (navLinks && navLinks.classList.contains('active') && 
            !event.target.closest('.nav-links') && 
            !event.target.closest('.mobile-menu-btn')) {
            navLinks.classList.remove('active');
        }
    });
    
    // Header scroll effect
    const header = document.querySelector('header');
    
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                e.preventDefault();
                
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
                
                // Close mobile menu after clicking a link
                if (navLinks && navLinks.classList.contains('active')) {
                    navLinks.classList.remove('active');
                }
            }
        });
    });
    
    // Initialize any sliders or carousels if needed
    
    // Form validation for appointment booking
    const bookingForm = document.querySelector('.booking-form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic form validation
            let isValid = true;
            const requiredFields = bookingForm.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                }
            });
            
            if (isValid) {
                (function () {
                    emailjs.init("zw8QtbUKCbwQTjcLu"); // Replace with your actual Public Key
                })();
            
                document.getElementById("checkup-form").addEventListener("submit", function (e) {
                    e.preventDefault(); // Prevent the default form submission
            
                    // Collect form data
                    const fullName = document.getElementById("full-name").value;
                    const phone = document.getElementById("phone").value;
                    const email = document.getElementById("email").value;
                    const packageType = document.getElementById("package").value;
                    const appointmentDate = document.getElementById("appointment-date").value;
            
                    // Prepare the email parameters
                    const emailParams = {
                        full_name: fullName,
                        phone: phone,
                        email: email,
                        package: packageType,
                        appointment_date: appointmentDate,
                    };
            
                    // Send the email using EmailJS
                    emailjs
                        .send("service_0gly3ze", "template_rzk8y4d", emailParams) // Replace with your actual Service ID and Template ID
                        .then(
                            function (response) {
                                alert("Your appointment has been booked successfully. A confirmation email has been sent.");
                            },
                            function (error) {
                                alert("Failed to send confirmation email. Please try again.");
                                console.error("EmailJS Error:", error);
                            }
                        );
                });
                bookingForm.reset();
            } else {
                alert('Please fill in all required fields.');
            }
        });
    }

    
});
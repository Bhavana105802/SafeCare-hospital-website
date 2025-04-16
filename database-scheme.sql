
-- Create database if not exists
CREATE DATABASE IF NOT EXISTS safecare_hospital;

-- Use the database
USE safecare_hospital;

-- Health Packages Table
CREATE TABLE IF NOT EXISTS health_packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    package_code VARCHAR(20) NOT NULL,
    package_name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    original_price DECIMAL(10, 2),
    category VARCHAR(50) NOT NULL,
    tests_included TEXT,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Health Checkup Bookings Table
CREATE TABLE IF NOT EXISTS health_checkup_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_reference VARCHAR(20) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(10) NOT NULL,
    package_type VARCHAR(50) NOT NULL,
    branch VARCHAR(50) NOT NULL,
    appointment_date DATE NOT NULL,
    special_requests TEXT,
    booking_status VARCHAR(20) NOT NULL DEFAULT 'Pending',
    payment_status VARCHAR(20) DEFAULT 'Pending',
    payment_amount DECIMAL(10, 2),
    payment_date DATETIME,
    payment_reference VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- FAQs Table
CREATE TABLE IF NOT EXISTS faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    category VARCHAR(50) NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Help Articles Table
CREATE TABLE IF NOT EXISTS help_articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    category VARCHAR(50) NOT NULL,
    tags VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Support Queries Table
CREATE TABLE IF NOT EXISTS support_queries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_number VARCHAR(20) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    query_type VARCHAR(50) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    attachment_path VARCHAR(255),
    status VARCHAR(20) NOT NULL DEFAULT 'Open',
    assigned_to INT,
    resolution TEXT,
    resolved_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample health packages
INSERT INTO health_packages (package_code, package_name, description, price, original_price, category, tests_included, display_order) VALUES
('BASIC', 'Basic Health Checkup', 'A fundamental health assessment suitable for individuals aged 18-40 years who want to monitor their basic health parameters.', 1999.00, 2499.00, 'basic', 'Complete Blood Count, Blood Sugar (Fasting), Lipid Profile, Liver Function Test (Basic), Kidney Function Test (Basic), BMI Assessment, Blood Pressure, Pulse Rate, General Physical Examination, Urine Routine Examination, Chest X-Ray (if recommended), ECG, Doctor Consultation', 1),
('COMP', 'Comprehensive Health Checkup', 'A thorough health assessment suitable for individuals aged 30-50 years, covering all essential health parameters and additional screenings.', 3999.00, 4999.00, 'comprehensive', 'Complete Blood Count, Blood Sugar (Fasting & PP), HbA1c, Lipid Profile (Complete), Liver Function Test (Complete), Kidney Function Test (Complete), Thyroid Profile, Vitamin B12 & D3, BMI Assessment, Blood Pressure, Pulse Rate, Detailed Physical Examination, Pulmonary Function Test, Urine Routine Examination, Chest X-Ray, ECG, Ultrasound Abdomen, Cardiac Risk Assessment, Specialist Consultation', 2),
('EXEC', 'Executive Health Checkup', 'A premium health assessment designed for busy professionals and executives, covering comprehensive screenings and specialized tests.', 7999.00, 9999.00, 'executive', 'All tests in Comprehensive package plus: Stress Test (TMT), 2D Echo, Vitamin Panel (B12, D3, Folate), Tumor Markers, Heavy Metal Screening, Hormone Profile, Bone Density Scan, Pulmonary Function Test, Audiometry, Vision Assessment, Dental Check-up, Nutritional Consultation, Lifestyle Assessment, Executive Health Report', 3),
('WOMEN', 'Women\'s Health Checkup', 'A specialized health assessment for women of all ages, focusing on female-specific health concerns along with general health parameters.', 4999.00, 5999.00, 'women', 'All tests in Comprehensive package plus: Pap Smear, Mammography (for 40+ or as recommended), Pelvic Ultrasound, Hormone Profile, Bone Density Scan (DEXA), Thyroid Function Tests, Vitamin D & B12, Gynecologist Consultation', 4),
('SENIOR', 'Senior Citizen Health Checkup', 'A comprehensive health assessment designed specifically for individuals above 60 years, focusing on age-related health concerns.', 5999.00, 7499.00, 'senior', 'All tests in Comprehensive package plus: Bone Density Scan (DEXA), Audiometry, Vision Assessment, Prostate Specific Antigen (for men), Thyroid Function Tests, Vitamin D & B12, Pulmonary Function Test, Geriatric Assessment, Specialist Consultation', 5);

-- Insert sample FAQs
INSERT INTO faqs (question, answer, category, display_order) VALUES
('What are the visiting hours at SafeCare Hospital?', 'Our general visiting hours are from 10:00 AM to 8:00 PM daily. However, visiting hours may vary for different departments such as ICU, NICU, and post-operative care units. Please check with the specific department or call our helpline for detailed information.', 'general', 1),
('How do I find a doctor at SafeCare Hospital?', 'You can find a doctor at SafeCare Hospital in several ways: Use the "Find a Doctor" feature on our website to search by specialty, name, or location; Call our appointment helpline at +91 1234 567 890; Visit any of our branches and inquire at the information desk; Ask your primary care physician for a referral to a specialist at SafeCare Hospital.', 'general', 2),
('Is parking available at SafeCare Hospital?', 'Yes, all our branches have dedicated parking facilities for patients and visitors. Most branches offer free parking for the first 2 hours, with nominal charges thereafter. Valet parking services are also available at select locations for your convenience.', 'general', 3),
('How do I schedule an appointment?', 'You can schedule an appointment in several ways: Online: Use our appointment booking system on our website; Phone: Call our appointment desk at +91 1234 567 890; In-person: Visit any of our branches and schedule at the appointment desk; Mobile App: Download our mobile app and book appointments on the go.', 'appointments', 1),
('How do I reschedule or cancel my appointment?', 'To reschedule or cancel an appointment, please use one of the following methods: Log in to your account on our website and manage your appointments; Call our appointment desk at +91 1234 567 890; Use our mobile app to modify your appointment. We request that you notify us at least 24 hours in advance if you need to reschedule or cancel your appointment.', 'appointments', 2),
('What should I bring to my appointment?', 'Please bring the following items to your appointment: Photo ID (Aadhar Card, PAN Card, Passport, etc.); Insurance card (if applicable); List of current medications; Previous medical records, test results, or imaging studies related to your condition; Referral letter from your doctor (if applicable); Payment method for any co-pays or fees not covered by insurance.', 'appointments', 3);

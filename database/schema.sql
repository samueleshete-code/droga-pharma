-- Droga Pharma PLC - Database Schema
-- MySQL 8.0+

CREATE DATABASE IF NOT EXISTS droga_pharma CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE droga_pharma;

-- Admin Users
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('superadmin','admin','editor') DEFAULT 'editor',
    avatar VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Product Categories
CREATE TABLE IF NOT EXISTS product_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) UNIQUE NOT NULL,
    description TEXT,
    icon VARCHAR(100),
    image VARCHAR(255),
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(220) UNIQUE NOT NULL,
    short_description TEXT,
    description LONGTEXT,
    manufacturer VARCHAR(150),
    country_of_origin VARCHAR(100),
    registration_number VARCHAR(100),
    image VARCHAR(255),
    brochure VARCHAR(255),
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES product_categories(id) ON DELETE SET NULL
);

-- Partners
CREATE TABLE IF NOT EXISTS partners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    logo VARCHAR(255),
    website VARCHAR(255),
    country VARCHAR(100),
    partnership_type ENUM('manufacturer','distributor','hospital','research','government') DEFAULT 'manufacturer',
    description TEXT,
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- News / Blog
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(280) UNIQUE NOT NULL,
    excerpt TEXT,
    content LONGTEXT,
    image VARCHAR(255),
    category VARCHAR(100),
    tags VARCHAR(500),
    author_id INT,
    is_featured TINYINT(1) DEFAULT 0,
    is_published TINYINT(1) DEFAULT 0,
    views INT DEFAULT 0,
    published_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- News Comments
CREATE TABLE IF NOT EXISTS news_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    news_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    comment TEXT NOT NULL,
    is_approved TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE
);

-- Job Categories
CREATE TABLE IF NOT EXISTS job_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) UNIQUE NOT NULL,
    is_active TINYINT(1) DEFAULT 1
);

-- Jobs / Careers
CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(220) UNIQUE NOT NULL,
    department VARCHAR(100),
    location VARCHAR(150),
    type ENUM('full-time','part-time','contract','internship') DEFAULT 'full-time',
    experience VARCHAR(100),
    education VARCHAR(150),
    description LONGTEXT,
    requirements LONGTEXT,
    benefits TEXT,
    salary_range VARCHAR(100),
    deadline DATE,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES job_categories(id) ON DELETE SET NULL
);

-- Job Applications
CREATE TABLE IF NOT EXISTS job_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NULL,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30),
    cover_letter TEXT,
    cv_file VARCHAR(255),
    status ENUM('pending','reviewing','shortlisted','rejected','hired') DEFAULT 'pending',
    notes TEXT,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
);

-- Contact Messages
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30),
    subject VARCHAR(255),
    department VARCHAR(100),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    replied_at DATETIME,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Newsletter Subscribers
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) UNIQUE NOT NULL,
    name VARCHAR(100),
    is_active TINYINT(1) DEFAULT 1,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Research Projects
CREATE TABLE IF NOT EXISTS research_projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category ENUM('research','innovation','publication','grant') DEFAULT 'research',
    description TEXT,
    content LONGTEXT,
    image VARCHAR(255),
    year INT,
    collaborators VARCHAR(500),
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Site Settings
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value LONGTEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Page Views Analytics
CREATE TABLE IF NOT EXISTS page_views (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page VARCHAR(255),
    ip_address VARCHAR(45),
    user_agent TEXT,
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin
INSERT INTO admin_users (name, email, password, role) VALUES
('Super Admin', 'admin@drogapharma.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'superadmin');
-- Default password: password

-- Insert product categories
INSERT INTO product_categories (name, slug, description, icon, sort_order) VALUES
('Pharmaceuticals', 'pharmaceuticals', 'Wide range of pharmaceutical products', 'fa-pills', 1),
('Medical Devices', 'medical-devices', 'Advanced medical equipment and devices', 'fa-stethoscope', 2),
('Diagnostics', 'diagnostics', 'Diagnostic tools and reagents', 'fa-microscope', 3),
('Laboratory Equipment', 'laboratory-equipment', 'Professional laboratory solutions', 'fa-flask', 4),
('Surgical Products', 'surgical-products', 'Surgical instruments and supplies', 'fa-syringe', 5),
('Orthopedic Solutions', 'orthopedic-solutions', 'Orthopedic implants and instruments', 'fa-bone', 6);

-- Insert job categories
INSERT INTO job_categories (name, slug) VALUES
('Sales & Marketing', 'sales-marketing'),
('Medical Affairs', 'medical-affairs'),
('Supply Chain', 'supply-chain'),
('Finance', 'finance'),
('IT & Technology', 'it-technology'),
('Human Resources', 'human-resources'),
('Operations', 'operations');

-- Insert site settings
INSERT INTO site_settings (setting_key, setting_value) VALUES
('site_name', 'Droga Pharma PLC'),
('site_tagline', 'Transforming Healthcare Across Africa'),
('site_email', 'info@drogapharma.com'),
('site_phone', '+251 11 123 4567'),
('site_address', 'Bole Road, Addis Ababa, Ethiopia'),
('facebook_url', 'https://facebook.com/drogapharma'),
('twitter_url', 'https://twitter.com/drogapharma'),
('linkedin_url', 'https://linkedin.com/company/drogapharma'),
('youtube_url', 'https://youtube.com/drogapharma'),
('years_experience', '25+'),
('products_distributed', '5000+'),
('partner_hospitals', '500+'),
('regions_served', '11+');

-- ── Patch: add username column to admin_users (used by login form) ──────────
ALTER TABLE admin_users ADD COLUMN IF NOT EXISTS username VARCHAR(80) UNIQUE AFTER name;
UPDATE admin_users SET username = 'admin' WHERE username IS NULL AND email = 'admin@drogapharma.com';

-- ── Patch: add status column to admin_users for legacy compatibility ─────────
ALTER TABLE admin_users ADD COLUMN IF NOT EXISTS status ENUM('active','inactive') DEFAULT 'active' AFTER is_active;
UPDATE admin_users SET status = IF(is_active=1,'active','inactive');

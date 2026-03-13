  -- =====================================================
  -- Sakthi Clothes - MySQL Database Schema
  -- Database: clothes
  -- =====================================================

  CREATE DATABASE IF NOT EXISTS `clothes` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  USE `clothes`;

  -- -----------------------------------------------------
  -- Table: admins
  -- -----------------------------------------------------
  CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  ) ENGINE=InnoDB;

  -- Default admin: username=admin, password=admin123
  INSERT INTO `admins` (`username`, `password`) VALUES 
  ('admin', 'admin@123');

  -- ----------------------------------------------------- $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
  -- Table: categories
  -- -----------------------------------------------------
  CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `image` VARCHAR(255) DEFAULT NULL,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  ) ENGINE=InnoDB;



  -- -----------------------------------------------------
  -- Table: subcategories
  -- -----------------------------------------------------
  CREATE TABLE IF NOT EXISTS `subcategories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(150) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
  ) ENGINE=InnoDB;



  -- -----------------------------------------------------
  -- Table: products
  -- (Added: offer_percent, is_new — used by index.php)
  -- -----------------------------------------------------
  CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `subcategory_id` INT DEFAULT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `offer_percent` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `sizes` VARCHAR(255) DEFAULT 'S,M,L,XL,XXL',
    `image` VARCHAR(255) DEFAULT NULL,
    `stock` INT DEFAULT 1,
    `is_featured` TINYINT(1) DEFAULT 0,
    `is_new` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories`(`id`) ON DELETE SET NULL
  ) ENGINE=InnoDB;

  -- -----------------------------------------------------
  -- Table: posters
  -- (Used for homepage banners and popup offers)
  -- -----------------------------------------------------
  CREATE TABLE IF NOT EXISTS `posters` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `link` VARCHAR(500) DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `is_popup` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  ) ENGINE=InnoDB;

  -- -----------------------------------------------------
  -- Table: settings
  -- (Added: shop_ticker — used by index.php)
  -- -----------------------------------------------------
  CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT NOT NULL
  ) ENGINE=InnoDB;

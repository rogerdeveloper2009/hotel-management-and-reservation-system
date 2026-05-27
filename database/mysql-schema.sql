-- ============================================================================
-- Rubavu Hotel — Full MySQL Schema
-- Generated for XAMPP / phpMyAdmin import
-- Database: rubavu_hotel
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `rubavu_hotel`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `rubavu_hotel`;

-- -----------------------------------------------------------
-- roles
-- -----------------------------------------------------------
CREATE TABLE `roles` (
    `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`       VARCHAR(255) NOT NULL UNIQUE,
    `label`      VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- users
-- -----------------------------------------------------------
CREATE TABLE `users` (
    `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`           VARCHAR(255) NOT NULL,
    `username`       VARCHAR(255) NOT NULL UNIQUE,
    `email`          VARCHAR(255) NULL UNIQUE,
    `password`       VARCHAR(255) NOT NULL,
    `role_id`        BIGINT UNSIGNED NOT NULL,
    `remember_token` VARCHAR(100) NULL,
    `created_at`     TIMESTAMP NULL,
    `updated_at`     TIMESTAMP NULL,

    CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- password_reset_tokens
-- -----------------------------------------------------------
CREATE TABLE `password_reset_tokens` (
    `email`      VARCHAR(255) NOT NULL PRIMARY KEY,
    `token`      VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- sessions
-- -----------------------------------------------------------
CREATE TABLE `sessions` (
    `id`            VARCHAR(255) NOT NULL PRIMARY KEY,
    `user_id`       BIGINT UNSIGNED NULL,
    `ip_address`    VARCHAR(45) NULL,
    `user_agent`    TEXT NULL,
    `payload`       LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,

    INDEX `idx_sessions_user_id` (`user_id`),
    INDEX `idx_sessions_last_activity` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- cache
-- -----------------------------------------------------------
CREATE TABLE `cache` (
    `key`        VARCHAR(255) NOT NULL PRIMARY KEY,
    `value`      MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- cache_locks
-- -----------------------------------------------------------
CREATE TABLE `cache_locks` (
    `key`        VARCHAR(255) NOT NULL PRIMARY KEY,
    `owner`      VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- jobs
-- -----------------------------------------------------------
CREATE TABLE `jobs` (
    `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `queue`        VARCHAR(255) NOT NULL,
    `payload`      LONGTEXT NOT NULL,
    `attempts`     TINYINT UNSIGNED NOT NULL,
    `reserved_at`  INT UNSIGNED NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at`   INT UNSIGNED NOT NULL,

    INDEX `idx_jobs_queue` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- job_batches
-- -----------------------------------------------------------
CREATE TABLE `job_batches` (
    `id`             VARCHAR(255) NOT NULL PRIMARY KEY,
    `name`           VARCHAR(255) NOT NULL,
    `total_jobs`     INT NOT NULL,
    `pending_jobs`   INT NOT NULL,
    `failed_jobs`    INT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options`        MEDIUMTEXT NULL,
    `cancelled_at`   INT NULL,
    `created_at`     INT NOT NULL,
    `finished_at`    INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- failed_jobs
-- -----------------------------------------------------------
CREATE TABLE `failed_jobs` (
    `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `uuid`       VARCHAR(255) NOT NULL UNIQUE,
    `connection` TEXT NOT NULL,
    `queue`      TEXT NOT NULL,
    `payload`    LONGTEXT NOT NULL,
    `exception`  LONGTEXT NOT NULL,
    `failed_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- room_types
-- -----------------------------------------------------------
CREATE TABLE `room_types` (
    `id`               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`             VARCHAR(255) NOT NULL UNIQUE,
    `slug`             VARCHAR(255) NOT NULL UNIQUE,
    `description`      TEXT NULL,
    `default_rate`     DECIMAL(12,2) NOT NULL DEFAULT 0,
    `default_capacity` SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    `created_at`       TIMESTAMP NULL,
    `updated_at`       TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- rooms
-- -----------------------------------------------------------
CREATE TABLE `rooms` (
    `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `room_type_id`   BIGINT UNSIGNED NOT NULL,
    `room_number`    VARCHAR(255) NOT NULL UNIQUE,
    `floor`          SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    `capacity`       SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    `rate_per_night` DECIMAL(12,2) NOT NULL,
    `status`         VARCHAR(255) NOT NULL DEFAULT 'available',
    `notes`          TEXT NULL,
    `created_at`     TIMESTAMP NULL,
    `updated_at`     TIMESTAMP NULL,
    `deleted_at`     TIMESTAMP NULL,

    CONSTRAINT `fk_rooms_room_type` FOREIGN KEY (`room_type_id`) REFERENCES `room_types`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- room_images
-- -----------------------------------------------------------
CREATE TABLE `room_images` (
    `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `room_id`    BIGINT UNSIGNED NOT NULL,
    `path`       VARCHAR(255) NOT NULL,
    `caption`    VARCHAR(255) NULL,
    `sort_order` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,

    CONSTRAINT `fk_room_images_room` FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- amenities
-- -----------------------------------------------------------
CREATE TABLE `amenities` (
    `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`       VARCHAR(255) NOT NULL UNIQUE,
    `slug`       VARCHAR(255) NOT NULL UNIQUE,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- room_amenities
-- -----------------------------------------------------------
CREATE TABLE `room_amenities` (
    `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `room_id`    BIGINT UNSIGNED NOT NULL,
    `amenity_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,

    UNIQUE KEY `uq_room_amenity` (`room_id`, `amenity_id`),

    CONSTRAINT `fk_room_amenities_room`    FOREIGN KEY (`room_id`)    REFERENCES `rooms`(`id`)    ON DELETE CASCADE,
    CONSTRAINT `fk_room_amenities_amenity` FOREIGN KEY (`amenity_id`) REFERENCES `amenities`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- customers
-- -----------------------------------------------------------
CREATE TABLE `customers` (
    `id`                        BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `full_name`                 VARCHAR(255) NOT NULL,
    `phone_number`              VARCHAR(255) NULL,
    `nationality`               VARCHAR(255) NULL,
    `passport_or_id`            VARCHAR(255) NULL,
    `address`                   VARCHAR(255) NULL,
    `emergency_contact_name`    VARCHAR(255) NULL,
    `emergency_contact_phone`   VARCHAR(255) NULL,
    `notes`                     TEXT NULL,
    `created_at`                TIMESTAMP NULL,
    `updated_at`                TIMESTAMP NULL,
    `deleted_at`                TIMESTAMP NULL,

    INDEX `idx_customers_name_phone` (`full_name`, `phone_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- bookings
-- -----------------------------------------------------------
CREATE TABLE `bookings` (
    `id`                   BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `booking_number`       VARCHAR(255) NOT NULL UNIQUE,
    `customer_id`          BIGINT UNSIGNED NOT NULL,
    `room_type_id`         BIGINT UNSIGNED NOT NULL,
    `room_id`              BIGINT UNSIGNED NULL,
    `check_in_date`        DATE NOT NULL,
    `check_out_date`       DATE NOT NULL,
    `nights`               SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    `adults`               SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    `children`             SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `status`               VARCHAR(255) NOT NULL DEFAULT 'pending',
    `rate_per_night`       DECIMAL(12,2) NOT NULL,
    `subtotal`             DECIMAL(12,2) NOT NULL DEFAULT 0,
    `discount_amount`      DECIMAL(12,2) NOT NULL DEFAULT 0,
    `extra_services_amount` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `tax_rate`             DECIMAL(5,2) NOT NULL DEFAULT 0,
    `tax_amount`           DECIMAL(12,2) NOT NULL DEFAULT 0,
    `total_amount`         DECIMAL(12,2) NOT NULL DEFAULT 0,
    `paid_amount`          DECIMAL(12,2) NOT NULL DEFAULT 0,
    `balance_amount`       DECIMAL(12,2) NOT NULL DEFAULT 0,
    `notes`                TEXT NULL,
    `created_by`           BIGINT UNSIGNED NULL,
    `created_at`           TIMESTAMP NULL,
    `updated_at`           TIMESTAMP NULL,
    `deleted_at`           TIMESTAMP NULL,

    CONSTRAINT `fk_bookings_customer`  FOREIGN KEY (`customer_id`)  REFERENCES `customers`(`id`),
    CONSTRAINT `fk_bookings_room_type` FOREIGN KEY (`room_type_id`) REFERENCES `room_types`(`id`),
    CONSTRAINT `fk_bookings_room`      FOREIGN KEY (`room_id`)      REFERENCES `rooms`(`id`),
    CONSTRAINT `fk_bookings_created_by` FOREIGN KEY (`created_by`)  REFERENCES `users`(`id`),

    INDEX `idx_bookings_status_dates`    (`status`, `check_in_date`, `check_out_date`),
    INDEX `idx_bookings_room_dates`      (`room_id`, `check_in_date`, `check_out_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- invoices
-- -----------------------------------------------------------
CREATE TABLE `invoices` (
    `id`                   BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `invoice_number`       VARCHAR(255) NOT NULL UNIQUE,
    `booking_id`           BIGINT UNSIGNED NOT NULL,
    `subtotal`             DECIMAL(12,2) NOT NULL DEFAULT 0,
    `discount_amount`      DECIMAL(12,2) NOT NULL DEFAULT 0,
    `extra_services_amount` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `tax_amount`           DECIMAL(12,2) NOT NULL DEFAULT 0,
    `total_amount`         DECIMAL(12,2) NOT NULL DEFAULT 0,
    `paid_amount`          DECIMAL(12,2) NOT NULL DEFAULT 0,
    `balance_amount`       DECIMAL(12,2) NOT NULL DEFAULT 0,
    `status`               VARCHAR(255) NOT NULL DEFAULT 'pending',
    `issued_at`            TIMESTAMP NULL,
    `created_at`           TIMESTAMP NULL,
    `updated_at`           TIMESTAMP NULL,

    CONSTRAINT `fk_invoices_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- payments
-- -----------------------------------------------------------
CREATE TABLE `payments` (
    `id`                 BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `payment_reference`  VARCHAR(255) NOT NULL UNIQUE,
    `booking_id`         BIGINT UNSIGNED NOT NULL,
    `invoice_id`         BIGINT UNSIGNED NULL,
    `amount`             DECIMAL(12,2) NOT NULL,
    `method`             VARCHAR(255) NOT NULL,
    `status`             VARCHAR(255) NOT NULL DEFAULT 'paid',
    `paid_at`            TIMESTAMP NULL,
    `notes`              TEXT NULL,
    `received_by`        BIGINT UNSIGNED NULL,
    `created_at`         TIMESTAMP NULL,
    `updated_at`         TIMESTAMP NULL,

    CONSTRAINT `fk_payments_booking`   FOREIGN KEY (`booking_id`)  REFERENCES `bookings`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_payments_invoice`   FOREIGN KEY (`invoice_id`)  REFERENCES `invoices`(`id`)  ON DELETE SET NULL,
    CONSTRAINT `fk_payments_received`  FOREIGN KEY (`received_by`) REFERENCES `users`(`id`),

    INDEX `idx_payments_method_status` (`method`, `status`, `paid_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- checkins
-- -----------------------------------------------------------
CREATE TABLE `checkins` (
    `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `booking_id`      BIGINT UNSIGNED NOT NULL,
    `checked_in_at`   TIMESTAMP NOT NULL,
    `guest_verified`  TINYINT(1) NOT NULL DEFAULT 1,
    `notes`           TEXT NULL,
    `created_by`      BIGINT UNSIGNED NULL,
    `created_at`      TIMESTAMP NULL,
    `updated_at`      TIMESTAMP NULL,

    CONSTRAINT `fk_checkins_booking`   FOREIGN KEY (`booking_id`)  REFERENCES `bookings`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_checkins_created`   FOREIGN KEY (`created_by`)  REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- checkouts
-- -----------------------------------------------------------
CREATE TABLE `checkouts` (
    `id`                 BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `booking_id`         BIGINT UNSIGNED NOT NULL,
    `checked_out_at`     TIMESTAMP NOT NULL,
    `late_checkout_fee`  DECIMAL(12,2) NOT NULL DEFAULT 0,
    `notes`              TEXT NULL,
    `created_by`         BIGINT UNSIGNED NULL,
    `created_at`         TIMESTAMP NULL,
    `updated_at`         TIMESTAMP NULL,

    CONSTRAINT `fk_checkouts_booking`  FOREIGN KEY (`booking_id`)  REFERENCES `bookings`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_checkouts_created`  FOREIGN KEY (`created_by`)  REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- activity_logs
-- -----------------------------------------------------------
CREATE TABLE `activity_logs` (
    `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id`       BIGINT UNSIGNED NULL,
    `action`        VARCHAR(255) NOT NULL,
    `subject_type`  VARCHAR(255) NULL,
    `subject_id`    BIGINT UNSIGNED NULL,
    `description`   TEXT NULL,
    `ip_address`    VARCHAR(45) NULL,
    `user_agent`    TEXT NULL,
    `created_at`    TIMESTAMP NULL,
    `updated_at`    TIMESTAMP NULL,

    CONSTRAINT `fk_activity_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,

    INDEX `idx_activity_subject`  (`subject_type`, `subject_id`),
    INDEX `idx_activity_action`   (`action`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- settings
-- -----------------------------------------------------------
CREATE TABLE `settings` (
    `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key`        VARCHAR(255) NOT NULL UNIQUE,
    `value`      TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Seed Data — Roles
-- ============================================================================
INSERT INTO `roles` (`name`, `label`, `created_at`, `updated_at`) VALUES
('super_admin',  'Super Admin',   NOW(), NOW()),
('admin',        'Admin',         NOW(), NOW()),
('receptionist', 'Receptionist',  NOW(), NOW()),
('manager',      'Manager',       NOW(), NOW());

-- ============================================================================
-- Seed Data — Users (password: ChangeMe123!)
-- ============================================================================
INSERT INTO `users` (`name`, `username`, `email`, `password`, `role_id`, `created_at`, `updated_at`) VALUES
('Super Admin',  'superadmin', NULL, '$2y$12$1iyduHyisl96xTC7VT5mgu..M81M8Ih1D7ZUJOwagxxBpHEFNteqe', 1, NOW(), NOW()),
('Admin',        'admin',      NULL, '$2y$12$1iyduHyisl96xTC7VT5mgu..M81M8Ih1D7ZUJOwagxxBpHEFNteqe', 2, NOW(), NOW()),
('Receptionist', 'reception',  NULL, '$2y$12$1iyduHyisl96xTC7VT5mgu..M81M8Ih1D7ZUJOwagxxBpHEFNteqe', 3, NOW(), NOW()),
('Manager',      'manager',    NULL, '$2y$12$1iyduHyisl96xTC7VT5mgu..M81M8Ih1D7ZUJOwagxxBpHEFNteqe', 4, NOW(), NOW());

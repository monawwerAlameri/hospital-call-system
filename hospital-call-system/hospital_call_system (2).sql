-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 29 أغسطس 2026 الساعة 14:57
-- إصدار الخادم: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital_call_system`
--

-- --------------------------------------------------------

--
-- بنية الجدول `call_logs`
--

CREATE TABLE `call_logs` (
  `id` int(11) NOT NULL,
  `call_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specialty_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_role_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doctor_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extension` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `announced_text` text COLLATE utf8mb4_unicode_ci,
  `voice_gender` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `initiated_by` int(11) DEFAULT NULL,
  `operator_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT 'sent',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'medical',
  `floor` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `extension` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `head_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `head_name_ar` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `head_title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `head_title_ar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `is_active` tinyint(4) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `departments`
--

INSERT INTO `departments` (`id`, `code`, `name`, `name_ar`, `category`, `floor`, `extension`, `head_name`, `head_name_ar`, `head_title`, `head_title_ar`, `is_active`, `created_at`) VALUES
(1, 'ER', 'Emergency Room', 'قسم الطوارئ', 'medical', 'Ground Floor', '', '', '', '', '', 1, '2026-07-01 03:26:01'),
(2, 'DLY', 'Dialysis Unit', 'وحدة الغسيل الكلوي', 'medical', '1st Floor', '', '', '', '', '', 1, '2026-07-01 03:26:01'),
(3, 'CCU', 'Coronary Care Unit', 'وحدة عناية القلب', 'medical', '2nd Floor', '', '', '', '', '', 1, '2026-07-01 03:26:01'),
(4, 'ADM', 'Administration', 'الإدارة', 'admin', '5th Floor', '', '', '', '', '', 1, '2026-07-01 03:26:01'),
(5, 'LOB', 'Main Lobby', 'البهو الرئيسي', 'general', 'Ground Floor', '', '', '', '', '', 1, '2026-07-01 03:26:01'),
(6, 'LAB', 'Laboratory', 'المختبر', 'medical', 'Ground Floor', '', '', '', '', '', 1, '2026-07-01 03:26:01'),
(7, 'ICU', 'Intensive Care Unit', 'وحدة العناية المركزة', 'medical', '2nd Floor', '', '', '', '', '', 1, '2026-07-01 03:26:01'),
(8, 'FMW', 'Female Medical Ward', 'الجناح الطبي النسائي', 'medical', '4th Floor', '', '', '', '', '', 1, '2026-07-01 03:26:01'),
(9, 'OPC', 'Outpatient Clinics', 'العيادات الخارجية', 'medical', '1st Floor', '', '', '', '', '', 1, '2026-07-01 03:26:01'),
(10, 'OR', 'Operating Room', 'غرفة العمليات', 'medical', '2nd Floor', '', '', '', '', '', 1, '2026-07-01 03:26:01'),
(11, 'NICU', 'Neonatal ICU', 'وحدة عناية حديثي الولادة', 'medical', '3rd Floor', '', '', '', '', '', 1, '2026-07-01 03:26:01'),
(12, 'MMW', 'Male Medical Ward', 'الجناح الطبي الرجالي', 'medical', '3rd Floor', '', '', '', '', '', 1, '2026-07-01 03:26:01'),
(13, 'RAD', 'Radiology Department', 'قسم الأشعة', 'medical', 'Ground Floor', '', '', '', '', '', 1, '2026-07-01 03:26:01');

-- --------------------------------------------------------

--
-- بنية الجدول `department_employees`
--

CREATE TABLE `department_employees` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Staff',
  `role_ar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extension` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'male',
  `is_active` tinyint(4) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `department_schedules`
--

CREATE TABLE `department_schedules` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `schedule_month` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule_year` int(11) NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_ar` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shift_definitions` text COLLATE utf8mb4_unicode_ci,
  `schedule_data` longtext COLLATE utf8mb4_unicode_ci,
  `approved_by` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_by_ar` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approver_title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approver_title_ar` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_ar` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specialty_id` int(11) DEFAULT NULL,
  `level` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'doctor',
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extension` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `custom_message` text COLLATE utf8mb4_unicode_ci,
  `last_paged` datetime DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `name_ar`, `specialty_id`, `level`, `gender`, `staff_type`, `phone`, `extension`, `department_id`, `custom_message`, `last_paged`, `is_active`) VALUES
(1, 'Dr. Ahmed Al-Ghamdi', 'د. أحمد الغامدي', 1, 'Consultant', 'male', 'doctor', NULL, NULL, 1, NULL, NULL, 1),
(2, 'Dr. Fatima Al-Zahrani', 'د. فاطمة الزهراني', 2, 'Specialist', 'female', 'doctor', NULL, NULL, 2, NULL, NULL, 1),
(3, 'Dr. Mohammed Al-Otaibi', 'د. محمد العتيبي', 4, 'Consultant', 'male', 'doctor', NULL, NULL, 1, NULL, NULL, 1),
(4, 'Sara Al-Qahtani', 'سارة القحطاني', 3, 'Specialist', 'female', 'nurse', NULL, NULL, 2, NULL, NULL, 1),
(5, 'Khaled Al-Harbi', 'خالد الحربي', 2, 'Resident', 'male', 'technician', NULL, NULL, 3, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- بنية الجدول `emergency_codes`
--

CREATE TABLE `emergency_codes` (
  `id` int(11) NOT NULL,
  `code_key` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_ar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '#e03131',
  `text_color` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '#ffffff',
  `icon` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT 'fa-exclamation-triangle',
  `priority` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'high',
  `msg_en` text COLLATE utf8mb4_unicode_ci,
  `msg_ar` text COLLATE utf8mb4_unicode_ci,
  `action_note` text COLLATE utf8mb4_unicode_ci,
  `is_builtin` tinyint(4) DEFAULT '0',
  `sort_order` int(11) DEFAULT '99',
  `is_active` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `emergency_codes`
--

INSERT INTO `emergency_codes` (`id`, `code_key`, `name`, `name_ar`, `description`, `color`, `text_color`, `icon`, `priority`, `msg_en`, `msg_ar`, `action_note`, `is_builtin`, `sort_order`, `is_active`) VALUES
(1, 'CODE_BLUE', 'Code Blue', 'كود أزرق', 'Cardiac/Respiratory Arrest', '#2563eb', '#ffffff', 'fa-heartbeat', 'critical', 'Code Blue activated', 'تم تفعيل الكود الأزرق', NULL, 1, 1, 1),
(2, 'CODE_RED', 'Code Red', 'كود أحمر', 'Fire Emergency', '#dc2626', '#ffffff', 'fa-fire', 'critical', 'Code Red activated', 'تم تفعيل الكود الأحمر', NULL, 1, 2, 1),
(3, 'CODE_BLACK', 'Code Black', 'كود أسود', 'Bomb Threat', '#1e1b4b', '#ffffff', 'fa-skull-crossbones', 'critical', 'Code Black activated', 'تم تفعيل الكود الأسود', NULL, 1, 3, 1),
(4, 'CODE_PINK', 'Code Pink', 'كود وردي', 'Infant/Child Abduction', '#ec4899', '#ffffff', 'fa-baby', 'critical', 'Code Pink activated', 'تم تفعيل الكود الوردي', NULL, 1, 4, 1),
(5, 'CODE_WHITE', 'Code White', 'كود أبيض', 'Violent/Aggressive Patient', '#f8fafc', '#1e293b', 'fa-hand-fist', 'high', 'Code White activated', 'تم تفعيل الكود الأبيض', NULL, 1, 5, 1),
(6, 'CODE_YELLOW', 'Code Yellow', 'كود أصفر', 'Missing Patient', '#eab308', '#1e293b', 'fa-person-walking', 'high', 'Code Yellow activated', 'تم تفعيل الكود الأصفر', NULL, 1, 6, 1),
(7, 'CODE_RRT', 'Rapid Response', 'فريق الاستجابة السريعة', 'Rapid Response Team', '#7c3aed', '#ffffff', 'fa-bolt', 'high', 'Rapid Response Team activated', 'تم تفعيل فريق الاستجابة السريعة', NULL, 1, 7, 1);

-- --------------------------------------------------------

--
-- بنية الجدول `handover_records`
--

CREATE TABLE `handover_records` (
  `id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `department_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shift_from` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shift_to` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `outgoing_staff` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `incoming_staff` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `priority` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'routine',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `handover_records`
--

INSERT INTO `handover_records` (`id`, `department_id`, `department_name`, `shift_from`, `shift_to`, `outgoing_staff`, `incoming_staff`, `notes`, `priority`, `status`, `created_by`, `created_at`) VALUES
(1, 4, 'Administration', 'morning', 'evening', 'Administrator', '', 'اهخمناعل', 'routine', 'pending', NULL, '2026-07-14 17:11:58');

-- --------------------------------------------------------

--
-- بنية الجدول `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_ar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'medical',
  `floor` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `extension` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `is_active` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `locations`
--

INSERT INTO `locations` (`id`, `code`, `name`, `name_ar`, `category`, `floor`, `extension`, `is_active`) VALUES
(1, 'ER', 'Emergency Room', 'الطوارئ', 'medical', '', '', 1),
(2, 'ICU', 'Intensive Care', 'العناية المركزة', 'medical', '', '', 1),
(3, 'SUR', 'Surgery', 'الجراحة', 'medical', '', '', 1);

-- --------------------------------------------------------

--
-- بنية الجدول `quiet_hours_config`
--

CREATE TABLE `quiet_hours_config` (
  `id` int(11) NOT NULL,
  `is_enabled` tinyint(4) DEFAULT '0',
  `start_time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '22:00',
  `end_time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '06:00',
  `repeat_days` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Sun,Mon,Tue,Wed,Thu',
  `allowed_codes` text COLLATE utf8mb4_unicode_ci,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `scheduled_announcements`
--

CREATE TABLE `scheduled_announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message_text` text COLLATE utf8mb4_unicode_ci,
  `target_role` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_doctor_id` int(11) DEFAULT NULL,
  `target_location_id` int(11) DEFAULT NULL,
  `voice_gender` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'female',
  `scheduled_time` datetime DEFAULT NULL,
  `repeat_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'once',
  `is_active` tinyint(4) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `shift_timers`
--

CREATE TABLE `shift_timers` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `employee_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_name_ar` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shift_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `auto_announce` tinyint(4) DEFAULT '1',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `operation_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `specialties`
--

CREATE TABLE `specialties` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_ar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `is_active` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `specialties`
--

INSERT INTO `specialties` (`id`, `name`, `name_ar`, `code`, `is_active`) VALUES
(1, 'Cardiology', 'القلبية', '', 1),
(2, 'Neurology', 'الأعصاب', '', 1),
(3, 'Pediatrics', 'الأطفال', '', 1),
(4, 'Internal Medicine', 'الباطنية', '', 1),
(5, 'Orthopedics', 'العظام', '', 1),
(6, 'General Surgery', 'الجراحة العامة', '', 1);

-- --------------------------------------------------------

--
-- بنية الجدول `staff_roles`
--

CREATE TABLE `staff_roles` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_ar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'medical',
  `default_gender` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'any',
  `is_active` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `staff_roles`
--

INSERT INTO `staff_roles` (`id`, `name`, `name_ar`, `code`, `category`, `default_gender`, `is_active`) VALUES
(1, 'Security', 'الأمن', 'SEC', 'admin', 'male', 1),
(2, 'Housekeeping', 'النظافة', 'HSK', 'support', 'any', 1),
(3, 'Maintenance', 'الصيانة', 'MNT', 'support', 'male', 1),
(4, 'Pharmacist', 'الصيدلي', 'PHR', 'medical', 'any', 1),
(5, 'Lab Technician', 'فني مختبر', 'LAB', 'medical', 'any', 1),
(6, 'Radiology Tech', 'فني أشعة', 'RAD', 'medical', 'any', 1),
(7, 'Social Worker', 'الأخصائي الاجتماعي', 'SOC', 'admin', 'any', 1),
(8, 'Dietitian', 'أخصائي تغذية', 'DIT', 'medical', 'any', 1);

-- --------------------------------------------------------

--
-- بنية الجدول `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `description`, `updated_at`) VALUES
(1, 'ui_language', 'ar', NULL, '2026-07-01 03:26:25');

-- --------------------------------------------------------

--
-- بنية الجدول `tv_board_messages`
--

CREATE TABLE `tv_board_messages` (
  `id` int(11) NOT NULL,
  `message_en` text COLLATE utf8mb4_unicode_ci,
  `message_ar` text COLLATE utf8mb4_unicode_ci,
  `priority` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'normal',
  `duration` int(11) DEFAULT '60',
  `is_active` tinyint(4) DEFAULT '1',
  `expires_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'operator',
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'male',
  `department` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `employee_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `is_active` tinyint(4) DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `gender`, `department`, `employee_id`, `phone`, `is_active`, `last_login`, `created_at`) VALUES
(1, 'Administrator', 'admin@hospital.sa', '$2y$10$w5SXKJiLGfQcMNdYXit7lu443mXI66AirwKIhAkQ6E7yVDn.qrvTK', 'admin', 'male', '', '', '', 1, '2026-07-14 19:43:44', '2026-07-01 03:26:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `call_logs`
--
ALTER TABLE `call_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `department_employees`
--
ALTER TABLE `department_employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dept` (`department_id`);

--
-- Indexes for table `department_schedules`
--
ALTER TABLE `department_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dept_month` (`department_id`,`schedule_month`,`schedule_year`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emergency_codes`
--
ALTER TABLE `emergency_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_key` (`code_key`);

--
-- Indexes for table `handover_records`
--
ALTER TABLE `handover_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiet_hours_config`
--
ALTER TABLE `quiet_hours_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scheduled_announcements`
--
ALTER TABLE `scheduled_announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shift_timers`
--
ALTER TABLE `shift_timers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dept_status` (`department_id`,`status`);

--
-- Indexes for table `specialties`
--
ALTER TABLE `specialties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_roles`
--
ALTER TABLE `staff_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `tv_board_messages`
--
ALTER TABLE `tv_board_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `call_logs`
--
ALTER TABLE `call_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `department_employees`
--
ALTER TABLE `department_employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department_schedules`
--
ALTER TABLE `department_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `emergency_codes`
--
ALTER TABLE `emergency_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `handover_records`
--
ALTER TABLE `handover_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `quiet_hours_config`
--
ALTER TABLE `quiet_hours_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scheduled_announcements`
--
ALTER TABLE `scheduled_announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shift_timers`
--
ALTER TABLE `shift_timers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `specialties`
--
ALTER TABLE `specialties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `staff_roles`
--
ALTER TABLE `staff_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tv_board_messages`
--
ALTER TABLE `tv_board_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

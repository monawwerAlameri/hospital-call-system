-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 14, 2026 at 12:32 AM
-- Server version: 5.7.24
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
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `details` text,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `call_logs`
--

CREATE TABLE `call_logs` (
  `id` int(11) NOT NULL,
  `call_type` enum('emergency_code','call_doctor','call_staff','custom','scheduled') NOT NULL,
  `code` varchar(100) DEFAULT NULL,
  `location_name` varchar(150) DEFAULT NULL,
  `specialty_name` varchar(150) DEFAULT NULL,
  `staff_role_name` varchar(150) DEFAULT NULL,
  `doctor_name` varchar(150) DEFAULT NULL,
  `extension` varchar(20) DEFAULT NULL,
  `custom_message` text,
  `announced_text` text NOT NULL,
  `voice_gender` enum('male','female') DEFAULT 'female',
  `initiated_by` int(11) DEFAULT NULL,
  `operator_name` varchar(150) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `status` enum('sent','failed','pending') DEFAULT 'sent',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `codes`
--

CREATE TABLE `codes` (
  `id` int(11) NOT NULL,
  `code_key` varchar(60) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `name_ar` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `color` varchar(20) DEFAULT '#e03131',
  `text_color` varchar(20) DEFAULT '#ffffff',
  `icon` varchar(60) DEFAULT 'fa-exclamation-triangle',
  `priority` varchar(20) DEFAULT 'high',
  `msg_en` text,
  `msg_ar` text,
  `action_note` text,
  `is_builtin` tinyint(4) DEFAULT '0',
  `sort_order` int(11) DEFAULT '99',
  `is_active` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `name_ar` varchar(150) DEFAULT NULL,
  `category` varchar(50) DEFAULT 'medical',
  `floor` varchar(50) DEFAULT '',
  `extension` varchar(20) DEFAULT '',
  `head_name` varchar(150) DEFAULT '',
  `head_name_ar` varchar(150) DEFAULT '',
  `head_title` varchar(100) DEFAULT '',
  `head_title_ar` varchar(100) DEFAULT '',
  `is_active` tinyint(4) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `code`, `name`, `name_ar`, `category`, `floor`, `extension`, `head_name`, `head_name_ar`, `head_title`, `head_title_ar`, `is_active`, `created_at`) VALUES
(1, 'ER', 'Emergency Room', 'قسم الطوارئ', 'medical', 'Ground Floor', '', '', '', '', '', 1, '2026-03-13 23:30:58'),
(2, 'DLY', 'Dialysis Unit', 'وحدة الغسيل الكلوي', 'medical', '1st Floor', '', '', '', '', '', 1, '2026-03-13 23:30:58'),
(3, 'CCU', 'Coronary Care Unit', 'وحدة عناية القلب', 'medical', '2nd Floor', '', '', '', '', '', 1, '2026-03-13 23:30:58'),
(4, 'ADM', 'Administration', 'الإدارة', 'admin', '5th Floor', '', '', '', '', '', 1, '2026-03-13 23:30:58'),
(5, 'LOB', 'Main Lobby', 'البهو الرئيسي', 'general', 'Ground Floor', '', '', '', '', '', 1, '2026-03-13 23:30:58'),
(6, 'LAB', 'Laboratory', 'المختبر', 'medical', 'Ground Floor', '', '', '', '', '', 1, '2026-03-13 23:30:58'),
(7, 'ICU', 'Intensive Care Unit', 'وحدة العناية المركزة', 'medical', '2nd Floor', '', '', '', '', '', 1, '2026-03-13 23:30:58'),
(8, 'FMW', 'Female Medical Ward', 'الجناح الطبي النسائي', 'medical', '4th Floor', '', '', '', '', '', 1, '2026-03-13 23:30:58'),
(9, 'OPC', 'Outpatient Clinics', 'العيادات الخارجية', 'medical', '1st Floor', '', '', '', '', '', 1, '2026-03-13 23:30:58'),
(10, 'OR', 'Operating Room', 'غرفة العمليات', 'medical', '2nd Floor', '', '', '', '', '', 1, '2026-03-13 23:30:58'),
(11, 'NICU', 'Neonatal ICU', 'وحدة عناية حديثي الولادة', 'medical', '3rd Floor', '', '', '', '', '', 1, '2026-03-13 23:30:58'),
(12, 'MMW', 'Male Medical Ward', 'الجناح الطبي الرجالي', 'medical', '3rd Floor', '', '', '', '', '', 1, '2026-03-13 23:30:58'),
(13, 'RAD', 'Radiology Department', 'قسم الأشعة', 'medical', 'Ground Floor', '', '', '', '', '', 1, '2026-03-13 23:30:58');

-- --------------------------------------------------------

--
-- Table structure for table `department_employees`
--

CREATE TABLE `department_employees` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `name_ar` varchar(150) DEFAULT NULL,
  `employee_id` varchar(50) DEFAULT NULL,
  `role` varchar(100) DEFAULT 'Staff',
  `role_ar` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `extension` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `gender` varchar(20) DEFAULT 'male',
  `is_active` tinyint(4) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `department_employees`
--

INSERT INTO `department_employees` (`id`, `department_id`, `name`, `name_ar`, `employee_id`, `role`, `role_ar`, `phone`, `extension`, `email`, `gender`, `is_active`, `created_at`) VALUES
(1, 4, 'monawwer', 'منور ', '126454', 'موظف', '', '0559270104', 'تس', '', 'male', 1, '2026-03-13 21:17:11'),
(2, 1, 'Dr. Mohammed Al-Rashidi', '', 'ER-001', 'Emergency Physician', '', '0551234001', '2101', 'm.er@hosp.sa', 'male', 1, '2026-03-14 00:57:46'),
(3, 1, 'Fatima Al-Ghamdi', '', 'ER-002', 'Head Nurse', '', '0551234002', '2102', 'f.er@hosp.sa', 'female', 1, '2026-03-14 00:57:46'),
(4, 1, 'Dr. Khalid Al-Otaibi', '', 'ER-003', 'Resident Physician', '', '0551234003', '2103', 'k.er@hosp.sa', 'male', 1, '2026-03-14 00:57:46'),
(5, 1, 'Sara Al-Zahrani', '', 'ER-004', 'Staff Nurse', '', '0551234004', '2104', 's.er@hosp.sa', 'female', 1, '2026-03-14 00:57:46'),
(6, 1, 'Hassan Al-Qahtani', '', 'ER-005', 'Paramedic', '', '0551234005', '2105', 'h.er@hosp.sa', 'male', 1, '2026-03-14 00:57:46'),
(7, 1, 'Nora Al-Shehri', '', 'ER-006', 'Registration Clerk', '', '0551234006', '2106', 'n.er@hosp.sa', 'female', 1, '2026-03-14 00:57:46'),
(8, 2, 'Dr. Ali Al-Maliki', '', 'DLY-001', 'Nephrologist', '', '0552234001', '2201', 'a.dly@hosp.sa', 'male', 1, '2026-03-14 00:58:42'),
(9, 2, 'Amal Al-Harthi', '', 'DLY-002', 'Head Nurse', '', '0552234002', '2202', 'a2.dly@hosp.sa', 'female', 1, '2026-03-14 00:58:42'),
(10, 2, 'Ibrahim Al-Dawsari', '', 'DLY-003', 'Dialysis Technician', '', '0552234003', '2203', 'i.dly@hosp.sa', 'male', 1, '2026-03-14 00:58:42'),
(11, 2, 'Mona Al-Subaie', '', 'DLY-004', 'Staff Nurse', '', '0552234004', '2204', 'm.dly@hosp.sa', 'female', 1, '2026-03-14 00:58:42'),
(12, 2, 'Omar Al-Bishi', '', 'DLY-005', 'Patient Care Tech', '', '0552234005', '2205', 'o.dly@hosp.sa', 'male', 1, '2026-03-14 00:58:42'),
(13, 2, 'Haya Al-Shamri', '', 'DLY-006', 'Coordinator', '', '0552234006', '2206', 'h.dly@hosp.sa', 'female', 1, '2026-03-14 00:58:42'),
(14, 3, 'Dr. Turki Al-Ghamdi', '', 'CCU-001', 'Cardiologist', '', '0553234001', '2301', 't.ccu@hosp.sa', 'male', 1, '2026-03-14 00:58:58'),
(15, 3, 'Reem Al-Otaibi', '', 'CCU-002', 'ICU Nurse', '', '0553234002', '2302', 'r.ccu@hosp.sa', 'female', 1, '2026-03-14 00:58:58'),
(16, 3, 'Dr. Bandar Al-Shahrani', '', 'CCU-003', 'Senior Resident', '', '0553234003', '2303', 'b.ccu@hosp.sa', 'male', 1, '2026-03-14 00:58:58'),
(17, 3, 'Lama Al-Johani', '', 'CCU-004', 'Staff Nurse', '', '0553234004', '2304', 'l.ccu@hosp.sa', 'female', 1, '2026-03-14 00:58:58'),
(18, 3, 'Faris Al-Yami', '', 'CCU-005', 'Cardiac Technician', '', '0553234005', '2305', 'f.ccu@hosp.sa', 'male', 1, '2026-03-14 00:58:58'),
(19, 3, 'Arwa Al-Zahrani', '', 'CCU-006', 'Monitoring Nurse', '', '0553234006', '2306', 'a.ccu@hosp.sa', 'female', 1, '2026-03-14 00:58:58'),
(20, 4, 'Abdullah Al-Sayed', '', 'ADM-002', 'HR Manager', '', '0554234002', '4402', 'a.adm@hosp.sa', 'male', 1, '2026-03-14 00:58:58'),
(21, 4, 'Noura Al-Aqeel', '', 'ADM-003', 'Secretary', '', '0554234003', '4403', 'n.adm@hosp.sa', 'female', 1, '2026-03-14 00:58:58'),
(22, 4, 'Saud Al-Mutairi', '', 'ADM-004', 'Finance Officer', '', '0554234004', '4404', 's.adm@hosp.sa', 'male', 1, '2026-03-14 00:58:58'),
(23, 4, 'Hessa Al-Harbi', '', 'ADM-005', 'Records Manager', '', '0554234005', '4405', 'h.adm@hosp.sa', 'female', 1, '2026-03-14 00:58:58'),
(24, 4, 'Yazid Al-Qahtani', '', 'ADM-006', 'IT Coordinator', '', '0554234006', '4406', 'y.adm@hosp.sa', 'male', 1, '2026-03-14 00:58:58'),
(25, 5, 'Ahmad Al-Dosari', '', 'LOB-001', 'Security Supervisor', '', '0555234001', '5501', 'a.lob@hosp.sa', 'male', 1, '2026-03-14 00:59:55'),
(26, 5, 'Maryam Al-Subhi', '', 'LOB-002', 'Receptionist', '', '0555234002', '5502', 'm.lob@hosp.sa', 'female', 1, '2026-03-14 00:59:55'),
(27, 5, 'Khaled Al-Harbi', '', 'LOB-003', 'Security Guard', '', '0555234003', '5503', 'k.lob@hosp.sa', 'male', 1, '2026-03-14 00:59:55'),
(28, 5, 'Dana Al-Maliki', '', 'LOB-004', 'Information Desk', '', '0555234004', '5504', 'd.lob@hosp.sa', 'female', 1, '2026-03-14 00:59:55'),
(29, 5, 'Meshal Al-Enezi', '', 'LOB-005', 'Porter', '', '0555234005', '5505', 'm2.lob@hosp.sa', 'male', 1, '2026-03-14 00:59:55'),
(30, 5, 'Rawan Al-Osaimi', '', 'LOB-006', 'Guest Relations', '', '0555234006', '5506', 'r.lob@hosp.sa', 'female', 1, '2026-03-14 00:59:55'),
(31, 6, 'Dr. Majid Al-Zahrani', '', 'LAB-001', 'Lab Director', '', '0556234001', '6601', 'm.lab@hosp.sa', 'male', 1, '2026-03-14 00:59:55'),
(32, 6, 'Reema Al-Shehri', '', 'LAB-002', 'Lab Technician', '', '0556234002', '6602', 'r.lab@hosp.sa', 'female', 1, '2026-03-14 00:59:55'),
(33, 6, 'Nawaf Al-Atiqi', '', 'LAB-003', 'Hematology Tech', '', '0556234003', '6603', 'n.lab@hosp.sa', 'male', 1, '2026-03-14 00:59:55'),
(34, 6, 'Ghada Al-Asmari', '', 'LAB-004', 'Microbiology Tech', '', '0556234004', '6604', 'g.lab@hosp.sa', 'female', 1, '2026-03-14 00:59:55'),
(35, 6, 'Rakan Al-Balawi', '', 'LAB-005', 'Phlebotomist', '', '0556234005', '6605', 'r2.lab@hosp.sa', 'male', 1, '2026-03-14 00:59:55'),
(36, 6, 'Shatha Al-Ruwaili', '', 'LAB-006', 'Quality Coordinator', '', '0556234006', '6606', 's.lab@hosp.sa', 'female', 1, '2026-03-14 00:59:55'),
(37, 7, 'Dr. Sami Al-Asmari', '', 'ICU-001', 'Intensivist', '', '0557234001', '7701', 's.icu@hosp.sa', 'male', 1, '2026-03-14 01:00:21'),
(38, 7, 'Wafa Al-Harbi', '', 'ICU-002', 'ICU Head Nurse', '', '0557234002', '7702', 'w.icu@hosp.sa', 'female', 1, '2026-03-14 01:00:21'),
(39, 7, 'Dr. Adel Al-Barrak', '', 'ICU-003', 'Senior Resident', '', '0557234003', '7703', 'a.icu@hosp.sa', 'male', 1, '2026-03-14 01:00:21'),
(40, 7, 'Hind Al-Mutairi', '', 'ICU-004', 'Staff Nurse', '', '0557234004', '7704', 'h.icu@hosp.sa', 'female', 1, '2026-03-14 01:00:21'),
(41, 7, 'Yasser Al-Omari', '', 'ICU-005', 'Respiratory Therapist', '', '0557234005', '7705', 'y.icu@hosp.sa', 'male', 1, '2026-03-14 01:00:21'),
(42, 7, 'Bushra Al-Thaqfi', '', 'ICU-006', 'ICU Nurse', '', '0557234006', '7706', 'b.icu@hosp.sa', 'female', 1, '2026-03-14 01:00:21'),
(43, 8, 'Dr. Hanan Al-Shehri', '', 'FMW-001', 'Internal Medicine Physician', '', '0558234001', '8801', 'h.fmw@hosp.sa', 'female', 1, '2026-03-14 01:00:21'),
(44, 8, 'Manar Al-Ghamdi', '', 'FMW-002', 'Head Nurse', '', '0558234002', '8802', 'm.fmw@hosp.sa', 'female', 1, '2026-03-14 01:00:21'),
(45, 8, 'Dr. Maha Al-Rashidi', '', 'FMW-003', 'Resident Physician', '', '0558234003', '8803', 'm2.fmw@hosp.sa', 'female', 1, '2026-03-14 01:00:21'),
(46, 8, 'Lina Al-Qahtani', '', 'FMW-004', 'Staff Nurse', '', '0558234004', '8804', 'l.fmw@hosp.sa', 'female', 1, '2026-03-14 01:00:21'),
(47, 8, 'Taghreed Al-Enezi', '', 'FMW-005', 'Staff Nurse', '', '0558234005', '8805', 't.fmw@hosp.sa', 'female', 1, '2026-03-14 01:00:21'),
(48, 8, 'Wajd Al-Maliki', '', 'FMW-006', 'Ward Coordinator', '', '0558234006', '8806', 'w.fmw@hosp.sa', 'female', 1, '2026-03-14 01:00:21'),
(49, 9, 'Dr. Faisal Al-Dosari', '', 'OPC-001', 'General Practitioner', '', '0559234001', '9901', 'f.opc@hosp.sa', 'male', 1, '2026-03-14 01:00:37'),
(50, 9, 'Nada Al-Maliki', '', 'OPC-002', 'Clinic Nurse', '', '0559234002', '9902', 'n.opc@hosp.sa', 'female', 1, '2026-03-14 01:00:37'),
(51, 9, 'Dr. Hessa Al-Zahrani', '', 'OPC-003', 'Pediatrician', '', '0559234003', '9903', 'h.opc@hosp.sa', 'female', 1, '2026-03-14 01:00:37'),
(52, 9, 'Rashed Al-Otaibi', '', 'OPC-004', 'Appointment Coordinator', '', '0559234004', '9904', 'r.opc@hosp.sa', 'male', 1, '2026-03-14 01:00:37'),
(53, 9, 'Afra Al-Thaqfi', '', 'OPC-005', 'Triage Nurse', '', '0559234005', '9905', 'a.opc@hosp.sa', 'female', 1, '2026-03-14 01:00:37'),
(54, 9, 'Sultan Al-Balawi', '', 'OPC-006', 'Medical Assistant', '', '0559234006', '9906', 's.opc@hosp.sa', 'male', 1, '2026-03-14 01:00:37'),
(55, 10, 'Dr. Walid Al-Asmari', '', 'OR-001', 'Surgeon', '', '0550134001', '3001', 'w.or@hosp.sa', 'male', 1, '2026-03-14 01:00:37'),
(56, 10, 'Dr. Najla Al-Harbi', '', 'OR-002', 'Anesthesiologist', '', '0550134002', '3002', 'n.or@hosp.sa', 'female', 1, '2026-03-14 01:00:37'),
(57, 10, 'Badr Al-Qahtani', '', 'OR-003', 'Scrub Technician', '', '0550134003', '3003', 'b.or@hosp.sa', 'male', 1, '2026-03-14 01:00:37'),
(58, 10, 'Asma Al-Ghamdi', '', 'OR-004', 'Circulating Nurse', '', '0550134004', '3004', 'a.or@hosp.sa', 'female', 1, '2026-03-14 01:00:37'),
(59, 10, 'Moath Al-Mutairi', '', 'OR-005', 'OR Technician', '', '0550134005', '3005', 'm.or@hosp.sa', 'male', 1, '2026-03-14 01:00:37'),
(60, 10, 'Sumaya Al-Ruwaili', '', 'OR-006', 'Recovery Nurse', '', '0550134006', '3006', 's.or@hosp.sa', 'female', 1, '2026-03-14 01:00:37'),
(61, 11, 'Dr. Ghalia Al-Otaibi', '', 'NICU-001', 'Neonatologist', '', '0551134001', '1101', 'g.nicu@hosp.sa', 'female', 1, '2026-03-14 01:01:00'),
(62, 11, 'Rasha Al-Bishi', '', 'NICU-002', 'NICU Head Nurse', '', '0551134002', '1102', 'r.nicu@hosp.sa', 'female', 1, '2026-03-14 01:01:00'),
(63, 11, 'Dr. Tariq Al-Zahrani', '', 'NICU-003', 'Pediatric Resident', '', '0551134003', '1103', 't.nicu@hosp.sa', 'male', 1, '2026-03-14 01:01:00'),
(64, 11, 'Eman Al-Shamri', '', 'NICU-004', 'NICU Nurse', '', '0551134004', '1104', 'e.nicu@hosp.sa', 'female', 1, '2026-03-14 01:01:00'),
(65, 11, 'Ranya Al-Maliki', '', 'NICU-005', 'NICU Nurse', '', '0551134005', '1105', 'r2.nicu@hosp.sa', 'female', 1, '2026-03-14 01:01:00'),
(66, 11, 'Naif Al-Enezi', '', 'NICU-006', 'Respiratory Tech', '', '0551134006', '1106', 'n.nicu@hosp.sa', 'male', 1, '2026-03-14 01:01:00'),
(67, 12, 'Dr. Saad Al-Ghamdi', '', 'MMW-001', 'Internal Medicine Physician', '', '0551234021', '1201', 's.mmw@hosp.sa', 'male', 1, '2026-03-14 01:01:00'),
(68, 12, 'Hamad Al-Otaibi', '', 'MMW-002', 'Head Nurse', '', '0551234022', '1202', 'h.mmw@hosp.sa', 'male', 1, '2026-03-14 01:01:00'),
(69, 12, 'Dr. Rashid Al-Maliki', '', 'MMW-003', 'Resident Physician', '', '0551234023', '1203', 'r.mmw@hosp.sa', 'male', 1, '2026-03-14 01:01:00'),
(70, 12, 'Osama Al-Harbi', '', 'MMW-004', 'Staff Nurse', '', '0551234024', '1204', 'o.mmw@hosp.sa', 'male', 1, '2026-03-14 01:01:00'),
(71, 12, 'Mishaal Al-Shehri', '', 'MMW-005', 'Staff Nurse', '', '0551234025', '1205', 'm.mmw@hosp.sa', 'male', 1, '2026-03-14 01:01:00'),
(72, 12, 'Raed Al-Asmari', '', 'MMW-006', 'Ward Coordinator', '', '0551234026', '1206', 'r2.mmw@hosp.sa', 'male', 1, '2026-03-14 01:01:00'),
(73, 13, 'Dr. Abdulaziz Al-Dosari', '', 'RAD-001', 'Radiologist', '', '0551234031', '1301', 'a.rad@hosp.sa', 'male', 1, '2026-03-14 01:01:00'),
(74, 13, 'Nour Al-Qahtani', '', 'RAD-002', 'CT Technician', '', '0551234032', '1302', 'n.rad@hosp.sa', 'female', 1, '2026-03-14 01:01:00'),
(75, 13, 'Fahad Al-Balawi', '', 'RAD-003', 'X-Ray Technician', '', '0551234033', '1303', 'f.rad@hosp.sa', 'male', 1, '2026-03-14 01:01:00'),
(76, 13, 'Duaa Al-Ghamdi', '', 'RAD-004', 'MRI Technician', '', '0551234034', '1304', 'd.rad@hosp.sa', 'female', 1, '2026-03-14 01:01:00'),
(77, 13, 'Waleed Al-Maliki', '', 'RAD-005', 'Ultrasound Tech', '', '0551234035', '1305', 'w.rad@hosp.sa', 'male', 1, '2026-03-14 01:01:00'),
(78, 13, 'Lama Al-Harbi', '', 'RAD-006', 'Radiology Coordinator', '', '0551234036', '1306', 'l.rad@hosp.sa', 'female', 1, '2026-03-14 01:01:00');

-- --------------------------------------------------------

--
-- Table structure for table `department_schedules`
--

CREATE TABLE `department_schedules` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `schedule_month` varchar(20) NOT NULL,
  `schedule_year` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `title_ar` varchar(200) DEFAULT NULL,
  `shift_definitions` text,
  `schedule_data` longtext,
  `approved_by` varchar(150) DEFAULT NULL,
  `approved_by_ar` varchar(150) DEFAULT NULL,
  `approver_title` varchar(150) DEFAULT NULL,
  `approver_title_ar` varchar(150) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `name_ar` varchar(150) DEFAULT NULL,
  `specialty_id` int(11) DEFAULT NULL,
  `level` varchar(100) DEFAULT 'Consultant',
  `gender` enum('male','female') DEFAULT 'male',
  `staff_type` varchar(30) DEFAULT 'doctor',
  `phone` varchar(20) DEFAULT NULL,
  `extension` varchar(20) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `name_ar`, `specialty_id`, `level`, `gender`, `staff_type`, `phone`, `extension`, `department_id`, `is_active`, `created_at`) VALUES
(1, 'Dr. Ahmed Al-Ghamdi', 'د. أحمد الغامدي', 1, 'Consultant', 'male', 'doctor', NULL, NULL, 1, 1, '2026-03-05 03:09:55'),
(2, 'Sara Nurse', 'الممرضة سارة', 3, 'Specialist', 'female', 'nurse', NULL, NULL, 2, 1, '2026-03-05 03:09:55'),
(3, 'Khaled Tech', 'الفني خالد', 2, 'Resident', 'male', 'technician', NULL, NULL, 3, 1, '2026-03-05 03:09:55');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_codes`
--

CREATE TABLE `emergency_codes` (
  `id` int(11) NOT NULL,
  `code_key` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `name_ar` varchar(150) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `color` varchar(20) DEFAULT '#1549c0',
  `text_color` varchar(20) DEFAULT '#ffffff',
  `icon` varchar(80) DEFAULT 'fa-exclamation-triangle',
  `priority` enum('critical','high','normal') DEFAULT 'high',
  `msg_en` text,
  `msg_ar` text,
  `action_note` text,
  `is_builtin` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `emergency_codes`
--

INSERT INTO `emergency_codes` (`id`, `code_key`, `name`, `name_ar`, `description`, `color`, `text_color`, `icon`, `priority`, `msg_en`, `msg_ar`, `action_note`, `is_builtin`, `is_active`, `sort_order`, `created_at`) VALUES
(1, 'CODE_BLUE', 'Code Blue', 'كود أزرق', 'Cardiac Arrest', '#1549c0', '#ffffff', 'fa-heart-pulse', 'critical', 'Code Blue... Code Blue... {loc}. Medical emergency team, respond immediately.', 'كود أزرق... كود أزرق... {loc_ar}. فريق الطوارئ الطبية، الاستجابة فورًا.', 'Crash team respond immediately, bring crash cart and defibrillator', 1, 1, 1, '2026-03-05 02:23:23'),
(2, 'CODE_RED', 'Code Red', 'كود أحمر', 'Fire Emergency', '#b91c1c', '#ffffff', 'fa-fire', 'critical', 'Code Red... Code Red... {loc}. All staff, follow fire emergency protocol immediately.', 'كود أحمر... كود أحمر... {loc_ar}. جميع الكوادر، اتبعوا بروتوكول الحريق فورًا.', 'Evacuate area, call fire department 998, use extinguishers', 1, 1, 2, '2026-03-05 02:23:23'),
(3, 'CODE_WHITE', 'Code White', 'كود أبيض', 'Violent Person', '#f1f5f9', '#111111', 'fa-shield-halved', 'high', 'Code White... Code White... {loc}. Security team, respond immediately.', 'كود أبيض... كود أبيض... {loc_ar}. فريق الأمن، الاستجابة فورًا.', 'Security contain situation, do not approach alone', 1, 1, 3, '2026-03-05 02:23:23'),
(4, 'CODE_PINK', 'Code Pink', 'كود وردي', 'Infant Abduction', '#be185d', '#ffffff', 'fa-baby', 'critical', 'Code Pink... Code Pink. Infant abduction alert. All exits are secured. Security, respond immediately.', 'كود وردي... تنبيه اختطاف رضيع. جميع المخارج مغلقة. فريق الأمن، الاستجابة فورًا.', 'Lock all exits, check all persons leaving, call security immediately', 1, 1, 4, '2026-03-05 02:23:23'),
(5, 'CODE_BLACK', 'Code Black', 'كود أسود', 'Bomb Threat', '#18181b', '#ffffff', 'fa-skull-crossbones', 'critical', 'Code Black... Code Black. Bomb threat received. Follow evacuation protocol immediately.', 'كود أسود... تم استلام تهديد بقنبلة. اتبعوا بروتوكول الإخلاء فورًا.', 'Do not touch, evacuate area, notify police 999 immediately', 1, 1, 5, '2026-03-05 02:23:23'),
(6, 'CODE_YELLOW', 'Code Yellow', 'كود أصفر', 'Missing Patient', '#d97706', '#111111', 'fa-magnifying-glass', 'high', 'Code Yellow... Code Yellow. Missing patient alert at {loc}. All staff, be on alert.', 'كود أصفر... تنبيه مريض مفقود في {loc_ar}. جميع الكوادر، كونوا في حالة تأهب.', 'Search all areas, check CCTV, notify all security personnel', 1, 1, 6, '2026-03-05 02:23:23'),
(7, 'RRT_TEAM', 'RRT Team', 'فريق الاستجابة السريعة', 'Rapid Response', '#7c3aed', '#ffffff', 'fa-truck-medical', 'high', 'Rapid Response Team required at {loc}. R R T team, respond immediately.', 'مطلوب فريق الاستجابة السريعة في {loc_ar}. فريق الاستجابة السريعة، الاستجابة فورًا.', 'RRT team respond with equipment including crash cart', 1, 1, 7, '2026-03-05 02:23:23'),
(8, 'CODE_PURPLE', 'Code Purple', 'كود بنفسجي', 'Hostage Situation', '#4f46e5', '#ffffff', 'fa-user-lock', 'critical', 'Code Purple... Code Purple. Hostage situation reported. Security and authorities notified.', 'كود بنفسجي... تم الإبلاغ عن حالة احتجاز رهينة. تم إخطار الأمن والسلطات.', 'Do not confront, notify police, follow hostage protocol', 1, 1, 8, '2026-03-05 02:23:23'),
(9, 'CODE_ORANGE', 'Code Orange', '', 'Mass Casualty Incident', '#ea580c', '#ffffff', 'fa-person-falling-burst', 'critical', 'Code Orange... Code Orange... All trauma teams respond immediately.', '', 'Activate mass casualty protocol, prepare all available beds', 0, 1, 9, '2026-03-14 01:02:30'),
(10, 'CODE_SILVER', 'Code Silver', '', 'Armed Person', '#64748b', '#ffffff', 'fa-gun', 'critical', 'Code Silver... Code Silver... Armed person reported. Security respond immediately.', '', 'Do not confront, secure patients, notify police 999', 0, 1, 10, '2026-03-14 01:02:30'),
(11, 'RAPID_RESPONSE', 'Rapid Response', '', 'Patient Deterioration', '#0891b2', '#ffffff', 'fa-truck-medical', 'high', 'Rapid Response required at {loc}. Patient deterioration. Rapid response team respond immediately.', '', 'RRT team respond with equipment, contact physician, prepare crash cart', 0, 1, 11, '2026-03-14 01:02:30'),
(12, 'CODE_AMBER', 'Code Amber', '', 'Child Abduction', '#f59e0b', '#111111', 'fa-child', 'critical', 'Code Amber... Child abduction alert. All exits secured. Security respond immediately.', '', 'Lock all exits, activate CCTV review, notify police immediately', 0, 1, 12, '2026-03-14 01:02:30'),
(13, 'INTERNAL_EMERGENCY', 'Internal Emergency', '', 'Infrastructure Failure', '#7c3aed', '#ffffff', 'fa-triangle-exclamation', 'high', 'Internal Emergency at {loc}. Facilities team respond immediately.', '', 'Facilities team respond, check utilities, notify maintenance chief', 0, 1, 13, '2026-03-14 01:02:30'),
(14, 'CODE_GREEN', 'Code Green', '', 'External Disaster', '#16a34a', '#ffffff', 'fa-house-damage', 'high', 'Code Green... External disaster declared. Department heads report to command center.', '', 'Activate hospital disaster plan, contact all department heads', 0, 1, 14, '2026-03-14 01:02:30');

-- --------------------------------------------------------

--
-- Table structure for table `handover_records`
--

CREATE TABLE `handover_records` (
  `id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `department_name` varchar(150) DEFAULT NULL,
  `shift_from` varchar(50) DEFAULT NULL,
  `shift_to` varchar(50) DEFAULT NULL,
  `outgoing_staff` varchar(150) DEFAULT NULL,
  `incoming_staff` varchar(150) DEFAULT NULL,
  `notes` text,
  `priority` varchar(20) DEFAULT 'routine',
  `status` varchar(20) DEFAULT 'pending',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `name_ar` varchar(150) DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `category` enum('medical','admin','technical','general') DEFAULT 'medical',
  `floor` varchar(50) DEFAULT NULL,
  `extension` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `name_ar`, `code`, `category`, `floor`, `extension`, `is_active`, `created_at`) VALUES
(1, 'Emergency Room', 'قسم الطوارئ', 'ER', 'medical', 'Ground Floor', NULL, 1, '2026-03-05 02:23:23'),
(2, 'Intensive Care Unit', 'وحدة العناية المركزة', 'ICU', 'medical', '2nd Floor', '', 1, '2026-03-05 02:23:23'),
(3, 'Coronary Care Unit', 'وحدة عناية القلب', 'CCU', 'medical', '2nd Floor', NULL, 1, '2026-03-05 02:23:23'),
(4, 'Neonatal ICU', 'وحدة عناية حديثي الولادة', 'NICU', 'medical', '3rd Floor', NULL, 1, '2026-03-05 02:23:23'),
(5, 'Male Medical Ward', 'الجناح الطبي الرجالي', 'MMW', 'medical', '3rd Floor', '', 1, '2026-03-05 02:23:23'),
(6, 'Female Medical Ward', 'الجناح الطبي النسائي', 'FMW', 'medical', '4th Floor', NULL, 1, '2026-03-05 02:23:23'),
(7, 'Operating Room', 'غرفة العمليات', 'OR', 'medical', '2nd Floor', NULL, 1, '2026-03-05 02:23:23'),
(8, 'Radiology Department', 'قسم الأشعة', 'RAD', 'medical', 'Ground Floor', NULL, 1, '2026-03-05 02:23:23'),
(9, 'Laboratory', 'المختبر', 'LAB', 'medical', 'Ground Floor', NULL, 1, '2026-03-05 02:23:23'),
(10, 'Dialysis Unit', 'وحدة الغسيل الكلوي', 'DLY', 'medical', '1st Floor', NULL, 1, '2026-03-05 02:23:23'),
(11, 'Outpatient Clinics', 'العيادات الخارجية', 'OPC', 'medical', '1st Floor', NULL, 1, '2026-03-05 02:23:23'),
(12, 'Administration', 'الإدارة', 'ADM', 'admin', '5th Floor', NULL, 1, '2026-03-05 02:23:23'),
(13, 'Main Lobby', 'البهو الرئيسي', 'LOB', 'general', 'Ground Floor', NULL, 1, '2026-03-05 02:23:23');

-- --------------------------------------------------------

--
-- Table structure for table `quiet_hours_config`
--

CREATE TABLE `quiet_hours_config` (
  `id` int(11) NOT NULL,
  `is_enabled` tinyint(4) DEFAULT '0',
  `start_time` varchar(10) DEFAULT '22:00',
  `end_time` varchar(10) DEFAULT '06:00',
  `repeat_days` varchar(100) DEFAULT 'Sun,Mon,Tue,Wed,Thu',
  `allowed_codes` text,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quiet_hours_config`
--

INSERT INTO `quiet_hours_config` (`id`, `is_enabled`, `start_time`, `end_time`, `repeat_days`, `allowed_codes`, `updated_at`) VALUES
(5, 0, '22:00', '06:00', 'Sun,Mon,Tue,Wed,Thu,Fri,Sat', 'blue,red,pink,black', '2026-03-14 03:11:16');

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_announcements`
--

CREATE TABLE `scheduled_announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `message_text` text NOT NULL,
  `target_role` varchar(150) DEFAULT NULL,
  `target_doctor_id` int(11) DEFAULT NULL,
  `target_location_id` int(11) DEFAULT NULL,
  `voice_gender` enum('male','female') DEFAULT 'female',
  `scheduled_time` datetime DEFAULT NULL,
  `repeat_type` enum('once','daily','weekly','none') DEFAULT 'once',
  `is_active` tinyint(1) DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `shift_timers`
--

CREATE TABLE `shift_timers` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `employee_name` varchar(150) DEFAULT NULL,
  `employee_name_ar` varchar(150) DEFAULT NULL,
  `shift_type` varchar(50) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `auto_announce` tinyint(4) DEFAULT '1',
  `status` varchar(20) DEFAULT 'active',
  `operation_number` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `specialties`
--

CREATE TABLE `specialties` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `name_ar` varchar(150) DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `specialties`
--

INSERT INTO `specialties` (`id`, `name`, `name_ar`, `code`, `is_active`, `created_at`) VALUES
(1, 'Internal Medicine', 'الطب الباطني', 'IM', 1, '2026-03-05 02:23:23'),
(2, 'Cardiology', 'أمراض القلب', 'CARD', 1, '2026-03-05 02:23:23'),
(3, 'Neurology', 'طب الأعصاب', 'NEUR', 1, '2026-03-05 02:23:23'),
(4, 'Neurosurgery', 'جراحة الأعصاب', 'NSURG', 1, '2026-03-05 02:23:23'),
(5, 'Gastroenterology', 'طب الجهاز الهضمي', 'GI', 1, '2026-03-05 02:23:23'),
(6, 'Endocrinology', 'الغدد الصماء', 'ENDO', 1, '2026-03-05 02:23:23'),
(7, 'General Surgery', 'الجراحة العامة', 'GS', 1, '2026-03-05 02:23:23'),
(8, 'Orthopedic Surgery', 'جراحة العظام', 'ORTHO', 1, '2026-03-05 02:23:23'),
(9, 'Urology', 'طب المسالك البولية', 'URO', 1, '2026-03-05 02:23:23'),
(10, 'Pediatrics', 'طب الأطفال', 'PED', 1, '2026-03-05 02:23:23'),
(11, 'Obstetrics and Gynecology', 'النساء والولادة', 'OBG', 1, '2026-03-05 02:23:23'),
(12, 'Anesthesia', 'التخدير', 'ANES', 1, '2026-03-05 02:23:23'),
(13, 'Psychiatry', 'الطب النفسي', 'PSY', 1, '2026-03-05 02:23:23'),
(14, 'Dermatology', 'الجلدية', 'DERM', 1, '2026-03-05 02:23:23'),
(15, 'Ophthalmology', 'طب العيون', 'OPH', 1, '2026-03-05 02:23:23'),
(16, 'ENT', 'الأنف والأذن والحنجرة', 'ENT', 1, '2026-03-05 02:23:23'),
(17, 'Oncology', 'الأورام', 'ONC', 1, '2026-03-05 02:23:23'),
(18, 'Pulmonology', 'أمراض الصدر', 'PULM', 1, '2026-03-05 02:23:23'),
(19, 'Nephrology', 'أمراض الكلى', 'NEPH', 1, '2026-03-05 02:23:23'),
(20, 'Hematology', 'أمراض الدم', 'HEMA', 1, '2026-03-05 02:23:23');

-- --------------------------------------------------------

--
-- Table structure for table `staff_roles`
--

CREATE TABLE `staff_roles` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `name_ar` varchar(150) DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `category` enum('medical','nursing','technical','administrative') DEFAULT 'medical',
  `default_gender` enum('male','female','any') DEFAULT 'any',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff_roles`
--

INSERT INTO `staff_roles` (`id`, `name`, `name_ar`, `code`, `category`, `default_gender`, `is_active`, `created_at`) VALUES
(1, 'Hospital Director On Call', 'مدير المستشفى المناوب', 'DIR', 'administrative', 'male', 1, '2026-03-05 02:23:23'),
(2, 'Administrative Supervisor', 'المشرف الإداري', 'ADMINS', 'administrative', 'any', 1, '2026-03-05 02:23:23'),
(3, 'Security Supervisor', 'مشرف الأمن', 'SEC', 'administrative', 'male', 1, '2026-03-05 02:23:23'),
(4, 'Maintenance Supervisor', 'مشرف الصيانة', 'MAINT', 'administrative', 'male', 1, '2026-03-05 02:23:23'),
(5, 'IT Support', 'دعم تقنية المعلومات', 'IT', 'administrative', 'any', 1, '2026-03-05 02:23:23'),
(6, 'Nursing Supervisor', 'مشرفة التمريض', 'NS', 'nursing', 'any', 1, '2026-03-05 02:23:23'),
(7, 'Head Nurse', 'رئيسة التمريض', 'HN', 'nursing', 'any', 1, '2026-03-05 02:23:23'),
(8, 'Laboratory Technician', 'فني المختبر', 'LABTECH', 'technical', 'any', 1, '2026-03-05 02:23:23'),
(9, 'Radiology Technician', 'فني الأشعة', 'RADTECH', 'technical', 'any', 1, '2026-03-05 02:23:23'),
(10, 'Respiratory Therapist', 'أخصائي العلاج التنفسي', 'RT', 'technical', 'any', 1, '2026-03-05 02:23:23'),
(11, 'OR Technician', 'فني غرفة العمليات', 'ORTECH', 'technical', 'any', 1, '2026-03-05 02:23:23'),
(12, 'Dialysis Technician', 'فني الغسيل الكلوي', 'DLYTECH', 'technical', 'any', 1, '2026-03-05 02:23:23'),
(13, 'Pharmacist On Call', 'الصيدلاني المناوب', 'PHARM', 'administrative', 'any', 1, '2026-03-05 02:23:23'),
(14, 'Social Worker', 'الأخصائي الاجتماعي', 'SW', 'administrative', 'any', 1, '2026-03-05 02:23:23');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `description`) VALUES
(1, 'hospital_name', 'King Khalid Hospital - Hail', 'Hospital display name'),
(2, 'hospital_name_ar', 'مستشفى الملك خالد - حائل', 'Arabic hospital name'),
(3, 'announcement_repeat', '2', 'Number of announcement repeats'),
(4, 'tts_rate', '0.72', 'Text-to-speech rate (airport slow)'),
(5, 'tts_pitch_male', '0.78', 'Male voice pitch'),
(6, 'tts_pitch_female', '1.10', 'Female voice pitch'),
(7, 'tts_pause_ms', '600', 'Pause between phrases in ms'),
(8, 'attention_prefix', '1', 'Add \"Attention please\" before all announcements'),
(9, 'system_version', '2.1.0', 'System version'),
(10, 'ui_language', 'en', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tv_board_messages`
--

CREATE TABLE `tv_board_messages` (
  `id` int(11) NOT NULL,
  `message_en` text,
  `message_ar` text,
  `priority` varchar(20) DEFAULT 'normal',
  `duration` int(11) DEFAULT '60',
  `is_active` tinyint(4) DEFAULT '1',
  `expires_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','operator','viewer') DEFAULT 'operator',
  `gender` enum('male','female') DEFAULT 'male',
  `department` varchar(100) DEFAULT NULL,
  `employee_id` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `gender`, `department`, `employee_id`, `phone`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'System Administrator', 'admin@hospital.sa', '$2y$10$TKh8H1.PfBfOJSc9qGHcou3GPKsqRkrwDUFEXPFhbElbXqFBIoNyq', 'admin', 'male', 'IT Department', 'EMP001', NULL, 1, NULL, '2026-03-05 02:23:23', '2026-03-05 02:23:23'),
(2, 'Dr. Sara Al-Rashidi', 'sara@hospital.sa', '$2y$10$TKh8H1.PfBfOJSc9qGHcou3GPKsqRkrwDUFEXPFhbElbXqFBIoNyq', 'operator', 'female', 'Emergency Room', 'EMP002', NULL, 1, NULL, '2026-03-05 02:23:23', '2026-03-05 02:23:23'),
(3, 'Mohammed Al-Qahtani', 'mohammed@hospital.sa', '$2y$10$TKh8H1.PfBfOJSc9qGHcou3GPKsqRkrwDUFEXPFhbElbXqFBIoNyq', 'operator', 'male', 'ICU', 'EMP003', NULL, 1, NULL, '2026-03-05 02:23:23', '2026-03-05 02:23:23');

-- --------------------------------------------------------

--
-- Table structure for table `voice_profiles`
--

CREATE TABLE `voice_profiles` (
  `id` int(11) NOT NULL,
  `profile_name` varchar(100) NOT NULL,
  `announcement_type` varchar(50) NOT NULL,
  `voice_gender` enum('male','female') DEFAULT 'female',
  `tts_rate` decimal(3,2) DEFAULT '0.72',
  `tts_pitch` decimal(3,2) DEFAULT '1.00',
  `pause_ms` int(11) DEFAULT '600',
  `repeat_count` int(11) DEFAULT '2',
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `voice_profiles`
--

INSERT INTO `voice_profiles` (`id`, `profile_name`, `announcement_type`, `voice_gender`, `tts_rate`, `tts_pitch`, `pause_ms`, `repeat_count`, `is_default`, `created_at`) VALUES
(1, 'Emergency Code Voice', 'emergency_code', 'female', '0.70', '0.95', 700, 2, 1, '2026-03-05 02:23:23'),
(2, 'Doctor Page Voice', 'call_doctor', 'female', '0.72', '1.10', 600, 2, 1, '2026-03-05 02:23:23'),
(3, 'Staff Page Voice', 'call_staff', 'male', '0.72', '0.78', 600, 2, 1, '2026-03-05 02:23:23'),
(4, 'Custom Broadcast Voice', 'custom', 'female', '0.72', '1.10', 500, 1, 1, '2026-03-05 02:23:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `call_logs`
--
ALTER TABLE `call_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `initiated_by` (`initiated_by`);

--
-- Indexes for table `codes`
--
ALTER TABLE `codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_key` (`code_key`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `specialty_id` (`specialty_id`),
  ADD KEY `department_id` (`department_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `quiet_hours_config`
--
ALTER TABLE `quiet_hours_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scheduled_announcements`
--
ALTER TABLE `scheduled_announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `target_doctor_id` (`target_doctor_id`),
  ADD KEY `target_location_id` (`target_location_id`),
  ADD KEY `created_by` (`created_by`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `staff_roles`
--
ALTER TABLE `staff_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

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
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `employee_id` (`employee_id`);

--
-- Indexes for table `voice_profiles`
--
ALTER TABLE `voice_profiles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `call_logs`
--
ALTER TABLE `call_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `codes`
--
ALTER TABLE `codes`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `department_schedules`
--
ALTER TABLE `department_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `emergency_codes`
--
ALTER TABLE `emergency_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `handover_records`
--
ALTER TABLE `handover_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `quiet_hours_config`
--
ALTER TABLE `quiet_hours_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `staff_roles`
--
ALTER TABLE `staff_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tv_board_messages`
--
ALTER TABLE `tv_board_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `voice_profiles`
--
ALTER TABLE `voice_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `call_logs`
--
ALTER TABLE `call_logs`
  ADD CONSTRAINT `call_logs_ibfk_1` FOREIGN KEY (`initiated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`specialty_id`) REFERENCES `specialties` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `doctors_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `scheduled_announcements`
--
ALTER TABLE `scheduled_announcements`
  ADD CONSTRAINT `scheduled_announcements_ibfk_1` FOREIGN KEY (`target_doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `scheduled_announcements_ibfk_2` FOREIGN KEY (`target_location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `scheduled_announcements_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

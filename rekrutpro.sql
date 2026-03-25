-- --------------------------------------------------------
-- Host:                         195.88.211.210
-- Server version:               10.6.24-MariaDB - MariaDB Server
-- Server OS:                    Linux
-- HeidiSQL Version:             12.13.0.7147
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for yesbisac_rekrutpro
CREATE DATABASE IF NOT EXISTS `yesbisac_rekrutpro` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci */;
USE `yesbisac_rekrutpro`;

-- Dumping structure for table yesbisac_rekrutpro.applications
CREATE TABLE IF NOT EXISTS `applications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `application_code` varchar(255) DEFAULT NULL,
  `job_posting_id` bigint(20) unsigned NOT NULL,
  `candidate_id` bigint(20) unsigned NOT NULL,
  `candidate_snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Snapshot data kandidat saat melamar' CHECK (json_valid(`candidate_snapshot`)),
  `cv_file` varchar(255) DEFAULT NULL,
  `cover_letter` varchar(255) DEFAULT NULL,
  `portfolio_file` varchar(255) DEFAULT NULL,
  `other_documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`other_documents`)),
  `status` enum('submitted','screening_passed','rejected_admin','interview_scheduled','interview_passed','rejected_interview','offered','hired','rejected_offer','archived') NOT NULL DEFAULT 'submitted',
  `rejection_reason` text DEFAULT NULL,
  `reviewed_by` bigint(20) unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `screening_passed_at` timestamp NULL DEFAULT NULL,
  `interview_scheduled_at` timestamp NULL DEFAULT NULL,
  `interview_passed_at` timestamp NULL DEFAULT NULL,
  `offered_at` timestamp NULL DEFAULT NULL,
  `hired_at` timestamp NULL DEFAULT NULL,
  `status_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `applications_code_unique` (`code`),
  KEY `applications_job_posting_id_foreign` (`job_posting_id`),
  KEY `applications_candidate_id_foreign` (`candidate_id`),
  KEY `applications_reviewed_by_foreign` (`reviewed_by`),
  CONSTRAINT `applications_candidate_id_foreign` FOREIGN KEY (`candidate_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `applications_job_posting_id_foreign` FOREIGN KEY (`job_posting_id`) REFERENCES `job_postings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `applications_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.applications: ~6 rows (approximately)
INSERT INTO `applications` (`id`, `code`, `application_code`, `job_posting_id`, `candidate_id`, `candidate_snapshot`, `cv_file`, `cover_letter`, `portfolio_file`, `other_documents`, `status`, `rejection_reason`, `reviewed_by`, `reviewed_at`, `screening_passed_at`, `interview_scheduled_at`, `interview_passed_at`, `offered_at`, `hired_at`, `status_notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'AP-2025-001', NULL, 1, 4, '"{\\"full_name\\":\\"John Developer\\",\\"email\\":\\"candidate1@example.com\\",\\"phone\\":\\"081234567893\\",\\"address\\":\\"Jl. Sudirman No. 123, Jakarta Pusat\\",\\"birth_date\\":\\"1995-05-15\\",\\"gender\\":\\"male\\",\\"education\\":[{\\"degree\\":\\"S1\\",\\"institution\\":\\"Universitas Indonesia\\",\\"major\\":\\"Teknik Informatika\\",\\"year\\":\\"2017\\"}],\\"experience\\":[{\\"position\\":\\"Software Engineer\\",\\"company\\":\\"PT. Tech Solutions\\",\\"duration\\":\\"2020-2023\\",\\"description\\":\\"Develop web applications using Laravel and Vue.js\\"}]}"', 'cvs/candidate1_software_engineer.pdf', 'Kepada Yth. Tim Rekrutmen,\n\nSaya sangat tertarik dengan posisi Software Engineer. Dengan pengalaman 3 tahun di Laravel dan Vue.js.\n\nHormat saya,\nJohn Developer', NULL, NULL, 'interview_scheduled', NULL, 2, '2025-12-26 09:29:59', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-18 09:29:59', '2026-01-18 09:29:59', NULL),
	(2, 'AP-2025-002', NULL, 2, 5, '"{\\"full_name\\":\\"Sarah Designer\\",\\"email\\":\\"candidate2@example.com\\",\\"phone\\":\\"081234567894\\",\\"address\\":\\"Jl. Gatot Subroto No. 456, Jakarta Selatan\\",\\"birth_date\\":\\"1997-08-22\\",\\"gender\\":\\"female\\",\\"education\\":[{\\"degree\\":\\"S1\\",\\"institution\\":\\"Institut Teknologi Bandung\\",\\"major\\":\\"Desain Komunikasi Visual\\",\\"year\\":\\"2019\\"}],\\"experience\\":[{\\"position\\":\\"UI\\\\/UX Designer\\",\\"company\\":\\"Digital Creative Agency\\",\\"duration\\":\\"2019-2021\\",\\"description\\":\\"Design UI and create wireframes using Figma\\"}]}"', 'cvs/candidate2_uiux_designer.pdf', 'Dear Hiring Team,\n\nI am excited to apply for the UI/UX Designer position.\n\nBest regards,\nSarah Designer', 'portfolios/candidate2_portfolio.pdf', NULL, 'offered', NULL, 2, '2026-01-02 09:29:59', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-18 09:29:59', '2026-01-18 09:29:59', NULL),
	(3, 'AP-2025-003', NULL, 3, 6, '"{\\"full_name\\":\\"Michael Marketing\\",\\"email\\":\\"candidate3@example.com\\",\\"phone\\":\\"081234567895\\",\\"address\\":\\"Jl. HR Rasuna Said No. 789, Jakarta Selatan\\",\\"birth_date\\":\\"1988-03-10\\",\\"gender\\":\\"male\\",\\"education\\":[{\\"degree\\":\\"S1\\",\\"institution\\":\\"Universitas Gajah Mada\\",\\"major\\":\\"Marketing Management\\",\\"year\\":\\"2010\\"}],\\"experience\\":[{\\"position\\":\\"Marketing Manager\\",\\"company\\":\\"PT. Global Brands\\",\\"duration\\":\\"2015-2023\\",\\"description\\":\\"Lead marketing team and develop strategies\\"}]}"', 'cvs/candidate3_marketing_manager.pdf', 'Kepada Yth. HRD Manager,\n\nSaya tertarik bergabung sebagai Marketing Manager.\n\nSalam hormat', NULL, NULL, 'screening_passed', NULL, 2, '2026-01-08 09:29:59', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-18 09:29:59', '2026-01-18 09:29:59', NULL),
	(4, 'AP-2025-004', NULL, 4, 7, '"{\\"full_name\\":\\"Emma Analyst\\",\\"email\\":\\"candidate4@example.com\\",\\"phone\\":\\"081234567896\\",\\"address\\":\\"Jl. Thamrin No. 234, Jakarta Pusat\\",\\"birth_date\\":\\"1994-11-05\\",\\"gender\\":\\"female\\",\\"education\\":[{\\"degree\\":\\"S1\\",\\"institution\\":\\"Universitas Brawijaya\\",\\"major\\":\\"Statistika\\",\\"year\\":\\"2016\\"}],\\"experience\\":[{\\"position\\":\\"Data Analyst\\",\\"company\\":\\"E-commerce Startup\\",\\"duration\\":\\"2018-2023\\",\\"description\\":\\"Analyze data and create dashboards\\"}]}"', 'cvs/candidate4_data_analyst.pdf', 'Dear Recruitment Team,\n\nI am interested in the Data Analyst position.\n\nBest regards', NULL, NULL, 'submitted', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-18 09:29:59', '2026-01-18 09:29:59', NULL),
	(5, 'AP-2025-005', NULL, 5, 8, '"{\\"full_name\\":\\"David HR\\",\\"email\\":\\"candidate5@example.com\\",\\"phone\\":\\"081234567897\\",\\"address\\":\\"Jl. Kuningan No. 567, Jakarta Selatan\\",\\"birth_date\\":\\"1992-07-18\\",\\"gender\\":\\"male\\",\\"education\\":[{\\"degree\\":\\"S1\\",\\"institution\\":\\"Universitas Airlangga\\",\\"major\\":\\"Psikologi\\",\\"year\\":\\"2014\\"}],\\"experience\\":[{\\"position\\":\\"HR Generalist\\",\\"company\\":\\"PT. Manufacturing Indonesia\\",\\"duration\\":\\"2016-2023\\",\\"description\\":\\"Recruitment and employee relations\\"}]}"', 'cvs/candidate5_hr_specialist.pdf', 'Kepada Yth. Tim HRD,\n\nSaya tertarik dengan posisi HR Specialist.\n\nTerima kasih', NULL, NULL, 'rejected_admin', 'Pengalaman kurang sesuai dengan kebutuhan saat ini.', 2, '2026-01-15 09:29:59', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-18 09:29:59', '2026-01-18 09:29:59', NULL),
	(6, 'AP-2025-006', NULL, 4, 4, '"{\\"full_name\\":\\"John Developer\\",\\"email\\":\\"candidate1@example.com\\",\\"phone\\":\\"081234567893\\",\\"address\\":\\"Jl. Sudirman No. 123, Jakarta Pusat\\",\\"birth_date\\":\\"1995-05-15\\",\\"gender\\":\\"male\\",\\"education\\":[{\\"degree\\":\\"S1\\",\\"institution\\":\\"Universitas Indonesia\\",\\"major\\":\\"Teknik Informatika\\",\\"year\\":\\"2017\\"}],\\"experience\\":[{\\"position\\":\\"Software Engineer\\",\\"company\\":\\"PT. Tech Solutions\\",\\"duration\\":\\"2020-2023\\",\\"description\\":\\"Web development and data analysis\\"}]}"', 'cvs/candidate1_data_analyst.pdf', 'Dear Hiring Manager,\n\nI also have analytical skills.\n\nRegards', NULL, NULL, 'submitted', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-18 09:29:59', '2026-01-18 09:29:59', NULL),
	(7, 'APP-2026-01-00001', 'APP-ZMBQRSE1', 5, 4, '{"full_name":"John Developer","email":"candidate1@example.com","phone":"081234567893","address":"-","birth_date":null,"gender":"-","education":[],"experience":[],"profile_photo":null,"snapshot_at":"2026-01-24 10:40:20"}', 'cvs/john-developer_CV_20260124104020_ykXfin.pdf', NULL, 'portfolios/john-developer_Portfolio_20260124104020_Sq1ijM.pdf', NULL, 'submitted', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-24 03:40:20', '2026-01-24 03:40:20', NULL);

-- Dumping structure for table yesbisac_rekrutpro.assessments
CREATE TABLE IF NOT EXISTS `assessments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `interview_id` bigint(20) unsigned NOT NULL,
  `interviewer_id` bigint(20) unsigned NOT NULL,
  `technical_score` int(11) DEFAULT NULL,
  `technical_notes` text DEFAULT NULL,
  `communication_skill` enum('sangat_baik','baik','cukup','kurang') DEFAULT NULL,
  `problem_solving_score` int(11) DEFAULT NULL,
  `problem_solving_notes` text DEFAULT NULL,
  `teamwork_potential` enum('tinggi','sedang','rendah') DEFAULT NULL,
  `overall_score` decimal(5,2) DEFAULT NULL,
  `strengths` text DEFAULT NULL,
  `weaknesses` text DEFAULT NULL,
  `additional_notes` text DEFAULT NULL,
  `recommendation` enum('sangat_direkomendasikan','direkomendasikan','tidak_direkomendasikan') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assessments_interview_id_foreign` (`interview_id`),
  KEY `assessments_interviewer_id_foreign` (`interviewer_id`),
  CONSTRAINT `assessments_interview_id_foreign` FOREIGN KEY (`interview_id`) REFERENCES `interviews` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assessments_interviewer_id_foreign` FOREIGN KEY (`interviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.assessments: ~2 rows (approximately)
INSERT INTO `assessments` (`id`, `interview_id`, `interviewer_id`, `technical_score`, `technical_notes`, `communication_skill`, `problem_solving_score`, `problem_solving_notes`, `teamwork_potential`, `overall_score`, `strengths`, `weaknesses`, `additional_notes`, `recommendation`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 85, 'Candidate shows strong technical fundamentals and good problem solving.', 'baik', 80, 'Problem solving is solid and well explained.', 'tinggi', 82.00, 'Relevant experience and good communication.', 'Needs minor improvements.', 'Auto-seeded assessment for demo.', 'direkomendasikan', '2026-01-18 09:29:59', '2026-01-18 09:29:59'),
	(2, 2, 3, 85, 'Candidate shows strong technical fundamentals and good problem solving.', 'baik', 80, 'Problem solving is solid and well explained.', 'tinggi', 82.00, 'Relevant experience and good communication.', 'Needs minor improvements.', 'Auto-seeded assessment for demo.', 'direkomendasikan', '2026-01-18 09:29:59', '2026-01-18 09:29:59');

-- Dumping structure for table yesbisac_rekrutpro.audit_logs
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` bigint(20) unsigned DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  KEY `audit_logs_model_type_model_id_index` (`model_type`,`model_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.audit_logs: ~4 rows (approximately)
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `model_type`, `model_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
	(1, 1, 'user_login', 'App\\Models\\User', 1, NULL, '{"name": "Super Admin", "email": "admin@rekrutpro.com"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '2026-01-18 10:35:58', '2026-01-18 10:35:58'),
	(2, 1, 'application_status_updated', 'App\\Models\\Application', 1, '{"status": "submitted", "job_title": "Software Engineer - Laravel Specialist", "candidate_name": "John Developer"}', '{"status": "screening_passed", "job_title": "Software Engineer - Laravel Specialist", "candidate_name": "John Developer"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '2026-01-18 10:35:58', '2026-01-18 10:35:58'),
	(3, 1, 'user_created', 'App\\Models\\User', 1, NULL, '{"name": "Alice Smith", "role": "Kandidat", "email": "alice.smith@example.com"}', '192.168.1.100', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)', '2026-01-18 10:35:58', '2026-01-18 10:35:58'),
	(4, 1, 'application_submitted', 'App\\Models\\Application', 1, NULL, '{"status": "submitted", "job_title": "Software Engineer - Laravel Specialist", "candidate_name": "John Developer"}', '10.0.0.50', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X)', '2026-01-18 10:35:58', '2026-01-18 10:35:58'),
	(5, 4, 'application_submitted', 'Application', 7, NULL, NULL, '119.235.221.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-24 03:40:20', '2026-01-24 03:40:20');

-- Dumping structure for table yesbisac_rekrutpro.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.cache: ~0 rows (approximately)

-- Dumping structure for table yesbisac_rekrutpro.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.cache_locks: ~0 rows (approximately)

-- Dumping structure for table yesbisac_rekrutpro.divisions
CREATE TABLE IF NOT EXISTS `divisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `divisions_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.divisions: ~6 rows (approximately)
INSERT INTO `divisions` (`id`, `name`, `code`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Teknologi Informasi', 'IT', 'Divisi pengembangan dan infrastruktur IT', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(2, 'Sumber Daya Manusia', 'HR', 'Divisi pengelolaan SDM dan rekrutmen', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(3, 'Pemasaran', 'MKT', 'Divisi pemasaran dan promosi', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(4, 'Keuangan', 'FIN', 'Divisi keuangan dan akuntansi', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(5, 'Operasional', 'OPS', 'Divisi operasional dan produksi', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(6, 'Penjualan', 'SALES', 'Divisi penjualan dan distribusi', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57');

-- Dumping structure for table yesbisac_rekrutpro.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table yesbisac_rekrutpro.interviews
CREATE TABLE IF NOT EXISTS `interviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `application_id` bigint(20) unsigned NOT NULL,
  `interviewer_id` bigint(20) unsigned NOT NULL,
  `scheduled_by` bigint(20) unsigned NOT NULL,
  `scheduled_at` datetime NOT NULL,
  `duration` int(11) NOT NULL DEFAULT 60,
  `interview_type` enum('phone','video','onsite') NOT NULL DEFAULT 'video',
  `location` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('scheduled','completed','cancelled','rescheduled') NOT NULL DEFAULT 'scheduled',
  `cancellation_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `interviews_application_id_foreign` (`application_id`),
  KEY `interviews_interviewer_id_foreign` (`interviewer_id`),
  KEY `interviews_scheduled_by_foreign` (`scheduled_by`),
  CONSTRAINT `interviews_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `interviews_interviewer_id_foreign` FOREIGN KEY (`interviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `interviews_scheduled_by_foreign` FOREIGN KEY (`scheduled_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.interviews: ~5 rows (approximately)
INSERT INTO `interviews` (`id`, `application_id`, `interviewer_id`, `scheduled_by`, `scheduled_at`, `duration`, `interview_type`, `location`, `notes`, `status`, `cancellation_reason`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 2, '2026-01-01 10:00:00', 30, 'onsite', 'Meeting Room A - HR Interview untuk Software Engineer position', 'HR Interview: Kandidat sangat komunikatif dan antusias. Rekomedasi lanjut ke technical interview.', 'completed', NULL, '2026-01-18 09:29:59', '2026-01-18 09:29:59'),
	(2, 1, 3, 2, '2026-01-05 14:00:00', 60, 'video', 'Google Meet - Technical Interview', 'Technical Interview: Covering backend and system design. Score: 90/100. PASS.', 'completed', NULL, '2026-01-18 09:29:59', '2026-01-18 09:29:59'),
	(3, 1, 9, 2, '2026-01-25 15:00:00', 45, 'onsite', 'CEO Office', 'Final interview with Director of Engineering to discuss team fit and roadmap.', 'scheduled', NULL, '2026-01-18 09:29:59', '2026-01-18 09:29:59'),
	(4, 2, 3, 2, '2026-01-07 13:00:00', 90, 'video', 'Google Meet - Design Challenge Session', 'Design Challenge: outstanding portfolio and UX thinking.', 'completed', NULL, '2026-01-18 09:29:59', '2026-01-18 09:29:59'),
	(5, 2, 9, 2, '2026-01-10 10:00:00', 30, 'onsite', 'Meeting Room A - Final Interview', 'Final Interview: discussion about team collaboration and design system implementation.', 'completed', NULL, '2026-01-18 09:29:59', '2026-01-18 09:29:59');

-- Dumping structure for table yesbisac_rekrutpro.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.job_batches: ~0 rows (approximately)

-- Dumping structure for table yesbisac_rekrutpro.job_postings
CREATE TABLE IF NOT EXISTS `job_postings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `position_id` bigint(20) unsigned NOT NULL,
  `division_id` bigint(20) unsigned NOT NULL,
  `location_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `responsibilities` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `quota` int(11) NOT NULL DEFAULT 1,
  `employment_type` varchar(255) NOT NULL DEFAULT 'full_time',
  `experience_level` varchar(255) NOT NULL DEFAULT 'mid',
  `salary_min` decimal(12,2) DEFAULT NULL,
  `salary_max` decimal(12,2) DEFAULT NULL,
  `salary_currency` varchar(255) NOT NULL DEFAULT 'IDR',
  `published_at` date DEFAULT NULL,
  `closed_at` date DEFAULT NULL,
  `status` enum('draft','active','closed','archived') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `job_postings_code_unique` (`code`),
  KEY `job_postings_position_id_foreign` (`position_id`),
  KEY `job_postings_division_id_foreign` (`division_id`),
  KEY `job_postings_location_id_foreign` (`location_id`),
  KEY `job_postings_created_by_foreign` (`created_by`),
  CONSTRAINT `job_postings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `job_postings_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `job_postings_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `job_postings_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.job_postings: ~6 rows (approximately)
INSERT INTO `job_postings` (`id`, `code`, `position_id`, `division_id`, `location_id`, `created_by`, `title`, `description`, `requirements`, `responsibilities`, `benefits`, `quota`, `employment_type`, `experience_level`, `salary_min`, `salary_max`, `salary_currency`, `published_at`, `closed_at`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'SE001', 1, 1, 1, 2, 'Software Engineer - Laravel Specialist', 'Kami mencari Software Engineer berpengalaman untuk bergabung dengan tim pengembangan kami.\n\nTanggung jawab:\n- Mengembangkan dan memelihara aplikasi web\n- Berkolaborasi dengan tim untuk merancang solusi teknis\n- Menulis kode yang bersih dan terdokumentasi\n- Melakukan code review\n\nKualifikasi:\n- Minimal 2 tahun pengalaman dalam pengembangan web\n- Menguasai Laravel, PHP, MySQL\n- Familiar dengan Git dan agile methodology\n- Kemampuan komunikasi yang baik', '- S1 Teknik Informatika/Sistem Informasi\n- Pengalaman minimal 2 tahun\n- Menguasai Laravel, Vue.js/React\n- Memahami RESTful API\n- Pengalaman dengan testing (PHPUnit, Jest)', '- Develop dan maintain web applications\n- Write clean, maintainable code\n- Collaborate dengan tim\n- Code review\n- Dokumentasi teknis', '- Gaji kompetitif\n- BPJS Kesehatan & Ketenagakerjaan\n- Flexible working hours\n- Learning budget\n- Team building activities', 2, 'full_time', 'mid', 8000000.00, 15000000.00, 'IDR', '2025-12-19', NULL, 'active', '2026-01-18 09:29:59', '2026-01-18 09:29:59', NULL),
	(2, 'UIUX001', 2, 1, 1, 2, 'UI/UX Designer - Product Design', 'Bergabunglah dengan tim kreatif kami sebagai UI/UX Designer!\n\nTanggung jawab:\n- Merancang antarmuka pengguna yang intuitif dan menarik\n- Melakukan riset pengguna dan analisis kompetitor\n- Membuat wireframe, mockup, dan prototype\n- Berkolaborasi dengan developer untuk implementasi\n\nKualifikasi:\n- Minimal 1 tahun pengalaman sebagai UI/UX Designer\n- Menguasai Figma/Adobe XD\n- Memahami prinsip design thinking\n- Portfolio yang kuat', '- S1 Desain Grafis/Multimedia atau setara\n- Pengalaman minimal 1 tahun\n- Menguasai Figma, Adobe XD, atau Sketch\n- Memahami design system\n- Portfolio wajib dilampirkan', '- Design user interfaces\n- Conduct user research\n- Create wireframes and prototypes\n- Collaborate dengan developer\n- Maintain design system', '- Gaji kompetitif\n- BPJS\n- Peralatan kerja (Laptop, Monitor)\n- Flexible hours\n- Creative freedom', 1, 'full_time', 'junior', 6000000.00, 12000000.00, 'IDR', '2025-12-29', NULL, 'active', '2026-01-18 09:29:59', '2026-01-18 09:29:59', NULL),
	(3, 'MM001', 3, 2, 2, 2, 'Marketing Manager - Digital Marketing Lead', 'Kami mencari Marketing Manager yang berpengalaman untuk memimpin tim marketing kami.\n\nTanggung jawab:\n- Mengembangkan strategi marketing yang efektif\n- Mengelola campaign marketing digital dan konvensional\n- Menganalisis performa marketing dan ROI\n- Memimpin dan mengembangkan tim marketing\n- Mengelola budget marketing\n\nKualifikasi:\n- Minimal 5 tahun pengalaman di bidang marketing\n- Pengalaman memimpin tim\n- Menguasai digital marketing tools\n- Analytical thinking yang kuat', '- S1 Marketing/Komunikasi/Management\n- Pengalaman minimal 5 tahun di marketing\n- Pengalaman leadership minimal 2 tahun\n- Menguasai Google Analytics, Facebook Ads, Google Ads\n- Kemampuan presentasi dan negosiasi yang baik', '- Develop marketing strategy\n- Lead marketing team\n- Manage campaigns\n- Analyze marketing performance\n- Budget management', '- Gaji menarik + bonus performa\n- BPJS + Asuransi swasta\n- Flexible hours\n- Annual leave 15 hari\n- Career development', 1, 'full_time', 'senior', 15000000.00, 25000000.00, 'IDR', '2026-01-03', NULL, 'active', '2026-01-18 09:29:59', '2026-01-18 09:29:59', NULL),
	(4, 'DA001', 4, 1, 1, 2, 'Data Analyst - Business Intelligence', 'Posisi contract 12 bulan sebagai Data Analyst.\n\nTanggung jawab:\n- Menganalisis data bisnis dan memberikan insights\n- Membuat dashboard dan laporan visualisasi data\n- Berkolaborasi dengan tim untuk kebutuhan data\n- Melakukan data cleaning dan preprocessing\n\nKualifikasi:\n- Minimal 2 tahun pengalaman sebagai Data Analyst\n- Menguasai SQL, Python/R\n- Familiar dengan tools visualisasi data (Tableau, Power BI)\n- Analytical dan detail-oriented', '- S1 Statistika/Matematika/Teknik Informatika\n- Pengalaman minimal 2 tahun\n- Menguasai SQL (MySQL/PostgreSQL)\n- Menguasai Python (pandas, numpy) atau R\n- Pengalaman dengan Tableau/Power BI', '- Analyze business data\n- Create dashboards and reports\n- Data cleaning and preprocessing\n- Provide actionable insights\n- Collaborate with stakeholders', '- Gaji kompetitif\n- BPJS\n- Laptop provided\n- Learning resources\n- Extension opportunity', 1, 'contract', 'mid', 7000000.00, 13000000.00, 'IDR', '2026-01-08', NULL, 'active', '2026-01-18 09:29:59', '2026-01-18 09:29:59', NULL),
	(5, 'HR001', 5, 3, 1, 2, 'HR Specialist - Recruitment & Employee Relations', 'Bergabunglah dengan tim HR kami!\n\nTanggung jawab:\n- Mengelola proses rekrutmen end-to-end\n- Mengembangkan program employee engagement\n- Mengelola administrasi kepegawaian\n- Menangani employee relations\n\nKualifikasi:\n- Minimal 2 tahun pengalaman di HR\n- Memahami employment law\n- Kemampuan komunikasi dan interpersonal yang baik\n- Detail-oriented dan organized', '- S1 Psikologi/Management/Hukum\n- Pengalaman minimal 2 tahun di HR\n- Memahami UU Ketenagakerjaan\n- Menguasai MS Office\n- Bersertifikat HR (diutamakan)', '- Manage recruitment process\n- Employee engagement programs\n- HR administration\n- Employee relations\n- Performance management support', '- Gaji kompetitif\n- BPJS\n- Training & development\n- Work-life balance\n- Career progression', 1, 'full_time', 'mid', 6000000.00, 10000000.00, 'IDR', '2026-01-11', NULL, 'active', '2026-01-18 09:29:59', '2026-01-18 09:29:59', NULL),
	(6, 'INT001', 1, 1, 1, 2, 'IT Internship Program - Web Development', 'Program internship 6 bulan untuk fresh graduate.\n\nTanggung jawab:\n- Membantu tim dalam project development\n- Belajar best practices software development\n- Dokumentasi dan testing\n\nKualifikasi:\n- Fresh graduate atau mahasiswa semester akhir\n- Antusias belajar teknologi baru\n- Basic programming skills', '- Mahasiswa S1 Teknik Informatika semester akhir atau fresh graduate\n- IPK minimal 3.0\n- Memiliki basic programming (PHP/Python/Java)\n- Dapat bekerja full time selama 6 bulan', '- Assist development team\n- Learn software development best practices\n- Documentation\n- Testing\n- Code review participation', '- Uang saku\n- Sertifikat\n- Mentoring\n- Pengalaman kerja\n- Potential full-time offer', 3, 'internship', 'junior', 3000000.00, 4000000.00, 'IDR', '2025-11-19', '2026-01-13', 'closed', '2026-01-18 09:29:59', '2026-01-18 09:29:59', NULL);

-- Dumping structure for table yesbisac_rekrutpro.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.jobs: ~0 rows (approximately)

-- Dumping structure for table yesbisac_rekrutpro.locations
CREATE TABLE IF NOT EXISTS `locations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `locations_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.locations: ~7 rows (approximately)
INSERT INTO `locations` (`id`, `name`, `code`, `city`, `province`, `address`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Jakarta Pusat', 'JKP', 'Jakarta', 'DKI Jakarta', 'Jl. Sudirman No. 123, Jakarta Pusat', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(2, 'Jakarta Selatan', 'JKS', 'Jakarta', 'DKI Jakarta', 'Jl. TB Simatupang Kav. 88, Jakarta Selatan', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(3, 'Bandung', 'BDG', 'Bandung', 'Jawa Barat', 'Jl. Soekarno Hatta No. 456, Bandung', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(4, 'Surabaya', 'SBY', 'Surabaya', 'Jawa Timur', 'Jl. HR Muhammad No. 789, Surabaya', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(5, 'Yogyakarta', 'YGY', 'Yogyakarta', 'DI Yogyakarta', 'Jl. Gejayan No. 321, Yogyakarta', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(6, 'Semarang', 'SMG', 'Semarang', 'Jawa Tengah', 'Jl. Pemuda No. 654, Semarang', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(7, 'Remote', 'RMT', 'Remote', 'Indonesia', 'Work from Anywhere', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57');

-- Dumping structure for table yesbisac_rekrutpro.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.migrations: ~24 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_11_27_102934_create_roles_table', 1),
	(5, '2025_11_27_102937_create_divisions_table', 1),
	(6, '2025_11_27_102939_create_positions_table', 1),
	(7, '2025_11_27_102942_create_locations_table', 1),
	(8, '2025_11_27_102945_create_job_postings_table', 1),
	(9, '2025_11_27_103024_create_applications_table', 1),
	(10, '2025_11_27_103027_create_interviews_table', 1),
	(11, '2025_11_27_103030_create_assessments_table', 1),
	(12, '2025_11_27_103033_create_notification_templates_table', 1),
	(13, '2025_11_27_103036_create_audit_logs_table', 1),
	(14, '2025_11_27_103047_add_role_fields_to_users_table', 1),
	(15, '2025_11_27_103050_create_offers_table', 1),
	(16, '2025_11_27_200317_add_duration_and_type_to_interviews_table', 1),
	(17, '2025_11_27_200433_add_status_timestamps_to_applications_table', 1),
	(18, '2025_11_28_034702_add_social_media_fields_to_users_table', 1),
	(19, '2025_11_28_041522_add_registration_step_to_users_table', 1),
	(20, '2025_11_28_041534_add_registration_step_to_users_table', 1),
	(21, '2025_11_28_045511_add_cv_path_to_users_table', 1),
	(22, '2025_11_29_204344_refactor_applications_table_use_snapshot', 1),
	(23, '2025_11_30_103218_create_offer_negotiations_table', 1),
	(24, '2026_01_18_040300_create_system_configs_table', 1);

-- Dumping structure for table yesbisac_rekrutpro.notification_templates
CREATE TABLE IF NOT EXISTS `notification_templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `event` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text NOT NULL,
  `available_placeholders` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`available_placeholders`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `notification_templates_name_unique` (`name`),
  UNIQUE KEY `notification_templates_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.notification_templates: ~11 rows (approximately)
INSERT INTO `notification_templates` (`id`, `name`, `slug`, `type`, `event`, `subject`, `body`, `available_placeholders`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Application Submitted - Email', 'application-submitted-email', 'email', 'application_submitted', 'Aplikasi Anda Telah Diterima - {{job_title}}', 'Halo {{candidate_name}},\\n\\nTerima kasih telah melamar posisi {{job_title}} di {{company_name}}.\\n\\nAplikasi Anda telah kami terima dan sedang dalam proses review oleh tim kami. Kami akan menghubungi Anda dalam 3-5 hari kerja untuk informasi selanjutnya.\\n\\nNomor Aplikasi: {{application_number}}\\n\\nSalam,\\nTim Rekrutmen {{company_name}}', '"[\\"candidate_name\\",\\"job_title\\",\\"company_name\\",\\"application_number\\"]"', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(2, 'Application Submitted - WhatsApp', 'application-submitted-whatsapp', 'whatsapp', 'application_submitted', NULL, 'Halo {{candidate_name}}, aplikasi Anda untuk posisi {{job_title}} telah diterima. Nomor aplikasi: {{application_number}}. Tim kami akan menghubungi Anda segera.', '"[\\"candidate_name\\",\\"job_title\\",\\"application_number\\"]"', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(3, 'Screening Passed - Email', 'screening-passed-email', 'email', 'screening_passed', 'Selamat! Anda Lolos Tahap Screening - {{job_title}}', 'Halo {{candidate_name}},\\n\\nSelamat! Kami dengan senang hati memberitahukan bahwa Anda telah lolos tahap screening untuk posisi {{job_title}}.\\n\\nTahap selanjutnya adalah wawancara dengan tim kami. Kami akan menghubungi Anda segera untuk penjadwalan wawancara.\\n\\nSalam,\\nTim Rekrutmen {{company_name}}', '"[\\"candidate_name\\",\\"job_title\\",\\"company_name\\"]"', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(4, 'Screening Rejected - Email', 'screening-rejected-email', 'email', 'screening_rejected', 'Update Aplikasi Anda - {{job_title}}', 'Halo {{candidate_name}},\\n\\nTerima kasih atas minat Anda untuk bergabung dengan {{company_name}} sebagai {{job_title}}.\\n\\nSetelah melakukan review yang cermat, kami memutuskan untuk melanjutkan dengan kandidat lain yang lebih sesuai dengan kebutuhan saat ini.\\n\\nKami menghargai waktu dan usaha Anda dalam proses aplikasi ini. Kami mendorong Anda untuk terus memantau lowongan kami di masa depan.\\n\\nSalam,\\nTim Rekrutmen {{company_name}}', '"[\\"candidate_name\\",\\"job_title\\",\\"company_name\\"]"', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(5, 'Interview Scheduled - Email', 'interview-scheduled-email', 'email', 'interview_scheduled', 'Undangan Wawancara - {{job_title}}', 'Halo {{candidate_name}},\\n\\nKami mengundang Anda untuk wawancara posisi {{job_title}}.\\n\\nDetail Wawancara:\\n- Tanggal: {{interview_date}}\\n- Waktu: {{interview_time}}\\n- Lokasi: {{interview_location}}\\n- Pewawancara: {{interviewer_name}}\\n- Tipe: {{interview_type}}\\n\\nMohon konfirmasi kehadiran Anda paling lambat 1 hari sebelum jadwal wawancara.\\n\\nSalam,\\nTim Rekrutmen {{company_name}}', '"[\\"candidate_name\\",\\"job_title\\",\\"interview_date\\",\\"interview_time\\",\\"interview_location\\",\\"interviewer_name\\",\\"interview_type\\",\\"company_name\\"]"', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(6, 'Interview Scheduled - WhatsApp', 'interview-scheduled-whatsapp', 'whatsapp', 'interview_scheduled', NULL, 'Halo {{candidate_name}}, Anda dijadwalkan wawancara untuk posisi {{job_title}} pada {{interview_date}} pukul {{interview_time}}. Lokasi: {{interview_location}}. Mohon konfirmasi kehadiran.', '"[\\"candidate_name\\",\\"job_title\\",\\"interview_date\\",\\"interview_time\\",\\"interview_location\\"]"', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(7, 'Interview Reminder - Email', 'interview-reminder-email', 'email', 'interview_reminder', 'Pengingat Wawancara Besok - {{job_title}}', 'Halo {{candidate_name}},\\n\\nIni adalah pengingat untuk wawancara Anda besok:\\n\\n- Posisi: {{job_title}}\\n- Tanggal: {{interview_date}}\\n- Waktu: {{interview_time}}\\n- Lokasi: {{interview_location}}\\n- Pewawancara: {{interviewer_name}}\\n\\nPastikan Anda datang 10 menit lebih awal.\\n\\nSampai jumpa!\\nTim Rekrutmen {{company_name}}', '"[\\"candidate_name\\",\\"job_title\\",\\"interview_date\\",\\"interview_time\\",\\"interview_location\\",\\"interviewer_name\\",\\"company_name\\"]"', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(8, 'Interview Passed - Email', 'interview-passed-email', 'email', 'interview_passed', 'Selamat! Anda Lolos Wawancara - {{job_title}}', 'Halo {{candidate_name}},\\n\\nSelamat! Anda telah berhasil melewati tahap wawancara untuk posisi {{job_title}}.\\n\\n{{next_steps}}\\n\\nTerima kasih atas partisipasi Anda.\\n\\nSalam,\\nTim Rekrutmen {{company_name}}', '"[\\"candidate_name\\",\\"job_title\\",\\"next_steps\\",\\"company_name\\"]"', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(9, 'Job Offer Sent - Email', 'offer-sent-email', 'email', 'offer_sent', 'Penawaran Kerja - {{job_title}} di {{company_name}}', 'Halo {{candidate_name}},\\n\\nSelamat! Kami dengan senang hati menawarkan Anda posisi {{job_title}} di {{company_name}}.\\n\\nDetail Penawaran:\\n- Posisi: {{job_title}}\\n- Gaji: {{salary_range}}\\n- Tanggal Mulai: {{start_date}}\\n- Lokasi: {{location}}\\n\\nSurat penawaran lengkap terlampir. Mohon berikan konfirmasi penerimaan dalam 7 hari kerja.\\n\\nKami sangat antusias menyambut Anda di tim kami!\\n\\nSalam,\\n{{company_name}}', '"[\\"candidate_name\\",\\"job_title\\",\\"company_name\\",\\"salary_range\\",\\"start_date\\",\\"location\\"]"', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(10, 'Offer Accepted - Email', 'offer-accepted-email', 'email', 'offer_accepted', 'Terima Kasih - Selamat Datang di {{company_name}}!', 'Halo {{candidate_name}},\\n\\nTerima kasih telah menerima penawaran kami!\\n\\nKami sangat senang Anda akan bergabung sebagai {{job_title}} mulai {{start_date}}.\\n\\nTim HR kami akan menghubungi Anda segera untuk proses onboarding dan persiapan hari pertama Anda.\\n\\nSelamat datang di keluarga {{company_name}}!\\n\\nSalam,\\nTim HR {{company_name}}', '"[\\"candidate_name\\",\\"job_title\\",\\"start_date\\",\\"company_name\\"]"', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(11, 'Offer Rejected - Email', 'offer-rejected-email', 'email', 'offer_rejected', 'Terima Kasih atas Waktu Anda', 'Halo {{candidate_name}},\\n\\nKami telah menerima pemberitahuan bahwa Anda memutuskan untuk menolak penawaran kami untuk posisi {{job_title}}.\\n\\nKami menghargai waktu dan usaha Anda selama proses rekrutmen ini. Kami berharap jalan terbaik untuk karir Anda ke depan.\\n\\nJika di masa depan Anda tertarik untuk bergabung dengan {{company_name}}, kami akan dengan senang hati mempertimbangkan aplikasi Anda.\\n\\nSalam,\\nTim Rekrutmen {{company_name}}', '"[\\"candidate_name\\",\\"job_title\\",\\"company_name\\"]"', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57');

-- Dumping structure for table yesbisac_rekrutpro.offer_negotiations
CREATE TABLE IF NOT EXISTS `offer_negotiations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `offer_id` bigint(20) unsigned NOT NULL,
  `candidate_id` bigint(20) unsigned NOT NULL,
  `proposed_salary` decimal(15,2) NOT NULL,
  `candidate_notes` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `hr_notes` text DEFAULT NULL,
  `reviewed_by` bigint(20) unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `offer_negotiations_offer_id_foreign` (`offer_id`),
  KEY `offer_negotiations_candidate_id_foreign` (`candidate_id`),
  KEY `offer_negotiations_reviewed_by_foreign` (`reviewed_by`),
  CONSTRAINT `offer_negotiations_candidate_id_foreign` FOREIGN KEY (`candidate_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `offer_negotiations_offer_id_foreign` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `offer_negotiations_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.offer_negotiations: ~1 rows (approximately)
INSERT INTO `offer_negotiations` (`id`, `offer_id`, `candidate_id`, `proposed_salary`, `candidate_notes`, `status`, `hr_notes`, `reviewed_by`, `reviewed_at`, `created_at`, `updated_at`) VALUES
	(1, 1, 5, 12000000.00, 'Terima kasih atas penawaran yang diberikan. Setelah saya pertimbangkan, saya ingin mengajukan negosiasi gaji menjadi Rp 12.000.000 berdasarkan:\n\n1. Pengalaman saya 2+ tahun sebagai UI/UX Designer dengan portfolio yang proven\n2. Skills tambahan saya di motion design dan user research\n3. Market rate untuk posisi serupa di Jakarta berkisar Rp 11-14 juta\n4. Saya akan membawa value tambahan dengan kemampuan mentoring untuk junior designers\n\nSaya sangat excited untuk bergabung dengan tim dan berkontribusi maksimal. Terima kasih atas pertimbangannya.', 'pending', NULL, NULL, NULL, '2026-01-18 03:29:59', '2026-01-18 09:29:59');

-- Dumping structure for table yesbisac_rekrutpro.offers
CREATE TABLE IF NOT EXISTS `offers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `application_id` bigint(20) unsigned NOT NULL,
  `offered_by` bigint(20) unsigned NOT NULL,
  `position_title` varchar(255) NOT NULL,
  `salary` decimal(12,2) NOT NULL,
  `salary_currency` varchar(255) NOT NULL DEFAULT 'IDR',
  `salary_period` varchar(255) NOT NULL DEFAULT 'monthly',
  `benefits` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`benefits`)),
  `contract_type` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `terms_and_conditions` text DEFAULT NULL,
  `internal_notes` text DEFAULT NULL,
  `status` enum('pending','accepted','rejected','expired') NOT NULL DEFAULT 'pending',
  `valid_until` date DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `responded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `offers_application_id_foreign` (`application_id`),
  KEY `offers_offered_by_foreign` (`offered_by`),
  CONSTRAINT `offers_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `offers_offered_by_foreign` FOREIGN KEY (`offered_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.offers: ~1 rows (approximately)
INSERT INTO `offers` (`id`, `application_id`, `offered_by`, `position_title`, `salary`, `salary_currency`, `salary_period`, `benefits`, `contract_type`, `start_date`, `end_date`, `terms_and_conditions`, `internal_notes`, `status`, `valid_until`, `rejection_reason`, `responded_at`, `created_at`, `updated_at`) VALUES
	(1, 2, 2, 'UI/UX Designer', 10000000.00, 'IDR', 'monthly', '"[\\"Tunjangan kesehatan (BPJS Kesehatan & Ketenagakerjaan)\\",\\"Asuransi swasta untuk karyawan dan keluarga\\",\\"Laptop dan peralatan kerja\\",\\"Flexible working hours\\",\\"Work from home 2 hari per minggu\\",\\"Annual leave 12 hari + cuti bersama\\",\\"Learning & development budget Rp 5.000.000\\\\/tahun\\",\\"Quarterly team building activities\\",\\"Free snacks and beverages\\"]"', 'Permanent', '2026-02-17', NULL, 'SYARAT DAN KETENTUAN PENAWARAN KERJA\n\n1. Masa Percobaan: 3 bulan dengan evaluasi di akhir periode\n2. Jam Kerja: Senin-Jumat, 09:00-18:00 (Flexible hours)\n3. Kenaikan Gaji: Review tahunan berdasarkan performa\n4. Bonus: Performance bonus tahunan (tergantung company performance)\n5. Penawaran ini berlaku selama 14 hari sejak tanggal penerbitan\n6. Kandidat diminta memberikan konfirmasi paling lambat sebelum tanggal expired\n7. Start date dapat dinegosiasikan sesuai ketersediaan kandidat\n8. Penawaran ini dapat dibatalkan jika kandidat tidak memberikan konfirmasi dalam waktu yang ditentukan', 'Kandidat sangat qualified dengan portfolio yang impressive. Salary Rp 10jt sesuai dengan range yang diminta dan competitive dengan market rate untuk 4 tahun pengalaman di UI/UX. Hasil interview: HR (90/100), Technical (95/100), Final (92/100). Strongly recommended.', 'pending', '2026-02-01', NULL, NULL, '2026-01-18 09:29:59', '2026-01-18 09:29:59');

-- Dumping structure for table yesbisac_rekrutpro.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table yesbisac_rekrutpro.positions
CREATE TABLE IF NOT EXISTS `positions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `division_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `positions_code_unique` (`code`),
  KEY `positions_division_id_foreign` (`division_id`),
  CONSTRAINT `positions_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.positions: ~34 rows (approximately)
INSERT INTO `positions` (`id`, `division_id`, `name`, `code`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Software Engineer', 'SE', 'Position for Software Engineer in Teknologi Informasi division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(2, 1, 'Frontend Developer', 'FE', 'Position for Frontend Developer in Teknologi Informasi division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(3, 1, 'Backend Developer', 'BE', 'Position for Backend Developer in Teknologi Informasi division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(4, 1, 'Full Stack Developer', 'FS', 'Position for Full Stack Developer in Teknologi Informasi division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(5, 1, 'DevOps Engineer', 'DO', 'Position for DevOps Engineer in Teknologi Informasi division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(6, 1, 'QA Engineer', 'QA', 'Position for QA Engineer in Teknologi Informasi division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(7, 1, 'UI/UX Designer', 'UX', 'Position for UI/UX Designer in Teknologi Informasi division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(8, 1, 'Data Analyst', 'DA', 'Position for Data Analyst in Teknologi Informasi division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(9, 2, 'HR Manager', 'HRM', 'Position for HR Manager in Sumber Daya Manusia division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(10, 2, 'HR Specialist', 'HRS', 'Position for HR Specialist in Sumber Daya Manusia division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(11, 2, 'Recruitment Specialist', 'REC', 'Position for Recruitment Specialist in Sumber Daya Manusia division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(12, 2, 'Training & Development', 'TND', 'Position for Training & Development in Sumber Daya Manusia division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(13, 2, 'Talent Acquisition', 'TA', 'Position for Talent Acquisition in Sumber Daya Manusia division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(14, 3, 'Marketing Manager', 'MM', 'Position for Marketing Manager in Pemasaran division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(15, 3, 'Digital Marketing Specialist', 'DM', 'Position for Digital Marketing Specialist in Pemasaran division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(16, 3, 'Content Writer', 'CW', 'Position for Content Writer in Pemasaran division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(17, 3, 'Social Media Specialist', 'SM', 'Position for Social Media Specialist in Pemasaran division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(18, 3, 'SEO Specialist', 'SEO', 'Position for SEO Specialist in Pemasaran division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(19, 3, 'Brand Manager', 'BM', 'Position for Brand Manager in Pemasaran division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(20, 4, 'Finance Manager', 'FM', 'Position for Finance Manager in Keuangan division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(21, 4, 'Accountant', 'ACC', 'Position for Accountant in Keuangan division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(22, 4, 'Financial Analyst', 'FA', 'Position for Financial Analyst in Keuangan division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(23, 4, 'Tax Specialist', 'TX', 'Position for Tax Specialist in Keuangan division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(24, 4, 'Budget Analyst', 'BA', 'Position for Budget Analyst in Keuangan division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(25, 5, 'Operations Manager', 'OM', 'Position for Operations Manager in Operasional division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(26, 5, 'Business Analyst', 'BSA', 'Position for Business Analyst in Operasional division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(27, 5, 'Project Manager', 'PM', 'Position for Project Manager in Operasional division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(28, 5, 'Product Manager', 'PDM', 'Position for Product Manager in Operasional division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(29, 5, 'Operations Specialist', 'OPS', 'Position for Operations Specialist in Operasional division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(30, 6, 'Sales Manager', 'SAM', 'Position for Sales Manager in Penjualan division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(31, 6, 'Sales Executive', 'SAE', 'Position for Sales Executive in Penjualan division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(32, 6, 'Account Manager', 'AM', 'Position for Account Manager in Penjualan division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(33, 6, 'Business Development', 'BD', 'Position for Business Development in Penjualan division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(34, 6, 'Customer Success', 'CSS', 'Position for Customer Success in Penjualan division', 1, '2026-01-18 09:29:57', '2026-01-18 09:29:57');

-- Dumping structure for table yesbisac_rekrutpro.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.roles: ~4 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'super_admin', 'Super Admin', 'Full system access - kelola user, master data, konfigurasi, dan laporan', '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(2, 'hr', 'HR / Recruiter', 'Kelola lowongan, proses kandidat dari awal sampai hired', '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(3, 'interviewer', 'Interviewer', 'Melakukan interview dan memberi penilaian kandidat', '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(4, 'candidate', 'Kandidat', 'Daftar, apply lowongan, dan cek status lamaran', '2026-01-18 09:29:57', '2026-01-18 09:29:57');

-- Dumping structure for table yesbisac_rekrutpro.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.sessions: ~38 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('0XRaP3ZLFTbi6cPrraXQJM2tr8jTW2UtUPYyuKov', NULL, '184.73.5.24', 'Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid2tyTnEyRnByUVdpeE51dVNXamZjQlBMV1hldzBoMDJFYXlQV2doUSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cHM6Ly9yZWtydXRwcm8ucmVsYWJpZnkuY29tL2hyL29mZmVycyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM2OiJodHRwczovL3Jla3J1dHByby5yZWxhYmlmeS5jb20vbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1769600928),
	('2K5p6i59Da9FvhWeo8gN2ZDA2BO8k5oLq1MqV53K', NULL, '18.232.129.59', 'Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoia0ZSdW5xMEx4eXVIajh5SlViQnZaeUMxYnM1V0VqNXZRaVdzT2xTQSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1MToiaHR0cHM6Ly9yZWtydXRwcm8ucmVsYWJpZnkuY29tL3N1cGVyYWRtaW4vZGFzaGJvYXJkIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHBzOi8vcmVrcnV0cHJvLnJlbGFiaWZ5LmNvbS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1769597715),
	('78Hl2i6ytGCqm6DaTYExVZFjS08NOv8imVncqu1w', NULL, '52.91.139.206', 'Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibzg4SHozeFRVbTdEWlV2MEMySDd0YU0zejNnbkd0endmamdYR3lKUyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MzoiaHR0cHM6Ly9yZWtydXRwcm8ucmVsYWJpZnkuY29tL2hyL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM2OiJodHRwczovL3Jla3J1dHByby5yZWxhYmlmeS5jb20vbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1769599668),
	('bNmh2ftDHAQ5IgRCs3pPy9WAIWS40filCO8bMcM1', NULL, '44.196.29.182', 'Mozilla/5.0 (iPad; CPU OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/138.0.7204.156 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOEtVT2dDa2hvRUYzVHlhWE8zVE1qRXBQSnlWbzVRdnR3a0xCSUpTRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vd3d3LnJla3J1dHByby5yZWxhYmlmeS5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1769597167),
	('EARRq8lmny591slpDi417US50AQ5NrLEhPWpwhy1', NULL, '3.90.89.162', 'Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibFNreXQyOFZMZlRwUm1XSks2NXZYWTBjZnp5WEd5SzJCTVZEa3pqQiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NzoiaHR0cHM6Ly9yZWtydXRwcm8ucmVsYWJpZnkuY29tL3N1cGVyYWRtaW4vYXVkaXQiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozNjoiaHR0cHM6Ly9yZWtydXRwcm8ucmVsYWJpZnkuY29tL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1769599648),
	('EyGMR76T3JMyYE9JNQyn6EcaF1ZWwasKBhKZPJaF', NULL, '34.238.127.71', 'Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidTNmODVaQVd5MWVmQWZ6U0p3c05JdDZUaE9jZVJEd0pqaGJSdk01ZiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MzoiaHR0cHM6Ly9yZWtydXRwcm8ucmVsYWJpZnkuY29tL2hyL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM2OiJodHRwczovL3Jla3J1dHByby5yZWxhYmlmeS5jb20vbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1769599540),
	('IS6o4OwIOzIfIUSn9lmqZ1NufYNoXCMqPoV5bZMe', NULL, '34.227.99.123', 'Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoic1FKVUFzdmxIenU3R3RsZ0R5NzRkOVNmZ0k2cnlOT1BpaE5xdjJtdiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1MzoiaHR0cHM6Ly9yZWtydXRwcm8ucmVsYWJpZnkuY29tL2ludGVydmlld2VyL2ludGVydmlld3MiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozNjoiaHR0cHM6Ly9yZWtydXRwcm8ucmVsYWJpZnkuY29tL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1769600866),
	('j5hEI1A75WFNRijGzKj6Fgi75qjg6fV4JO0bgKa8', NULL, '98.81.193.160', 'Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM2lKemJnOWZwRTZISEhucFFRWkJhNDN4bmtSbVVEblNtN2hYdjNQNyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NzoiaHR0cHM6Ly9yZWtydXRwcm8ucmVsYWJpZnkuY29tL3N1cGVyYWRtaW4vdXNlcnMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozNjoiaHR0cHM6Ly9yZWtydXRwcm8ucmVsYWJpZnkuY29tL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1769598666),
	('LawojlIU9dhlN2DrRsIJvNMeE4NgLbbu0o0H0low', 3, '180.254.75.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidnZkd3B1aEZtZ3FFQmlzUTdBOE1ndEtHM1ZQZW5QcmtMODJpa0Q1OSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHBzOi8vcmVrcnV0cHJvLnJlbGFiaWZ5LmNvbS9pbnRlcnZpZXdlci9pbnRlcnZpZXdzIjtzOjU6InJvdXRlIjtzOjI4OiJpbnRlcnZpZXdlci5pbnRlcnZpZXdzLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1769601087),
	('nhSuJ9ms7AnATpzYjKAwF3YxKS5OPEr9cPM8NR73', NULL, '18.208.176.191', 'Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVm1ZVkl6ck44d3dTZ3FwbUQwenlNRHhIUVdaT2M4NFhTNlIwQkNqdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHBzOi8vcmVrcnV0cHJvLnJlbGFiaWZ5LmNvbSI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1769597643),
	('nXXYk0AB3OXjcQUUlXSqlsfO4ogqoIcNwtIW6L10', NULL, '34.229.23.249', 'Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWEp0ekZmRk1MbW5ic3BtWHp1UXFzbHZwRDBlNU5kbTFpYUMxNmtGbSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cHM6Ly9yZWtydXRwcm8ucmVsYWJpZnkuY29tL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM2OiJodHRwczovL3Jla3J1dHByby5yZWxhYmlmeS5jb20vbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1769598579),
	('qemaU4zdxSp1ufsdJw6W48eeZquw5fYZMGIu5kWt', NULL, '36.92.231.72', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS0tKRTJMVkt2NFcxaG1ab1UzQWJob3VHaUFpQk83NnJJOE8yWTlsSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHBzOi8vcmVrcnV0cHJvLnJlbGFiaWZ5LmNvbS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1769598240),
	('Sl0hVrzYAjyWTaNebvdk7w4sA2gjIL5Oa2S6S4VL', NULL, '18.232.130.185', 'Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoia25oeUxzRVlrbWdqS0d2V2w5WXZQQ0lqNXY2cjAzQ0V1MUc5Y29jMCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1MjoiaHR0cHM6Ly9yZWtydXRwcm8ucmVsYWJpZnkuY29tL2ludGVydmlld2VyL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM2OiJodHRwczovL3Jla3J1dHByby5yZWxhYmlmeS5jb20vbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1769600231),
	('tQpUGPBIZ4kthVLlkeuDNQegCqEPoFAWlFDKS3EM', NULL, '182.253.59.244', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT0MzNjE2Z2MyREIyV3VZQzd5aUxsYWxTTVA5dHlsWjdWTDFWWjcwWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHBzOi8vcmVrcnV0cHJvLnJlbGFiaWZ5LmNvbS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1769598102),
	('uZdC1hcxE7Rfd1KIfCZuRAUhrkZTYuDtMBvMHtrb', 3, '182.253.59.244', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQURpc1lXcndqZ28yWnBQdkVTQ2dYWXZ2OEN3SWdlcTNPc0p0WGpmeiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHBzOi8vcmVrcnV0cHJvLnJlbGFiaWZ5LmNvbS9pbnRlcnZpZXdlci9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MjE6ImludGVydmlld2VyLmRhc2hib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==', 1769598411),
	('ZdD5DtInBjVgjW9w2205rO06kp7koBkCkegb9BkP', NULL, '54.162.133.45', 'Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWHc0UmdnVDVuWXFIdGJGR0pTa2NFRnJQc2VBNXIwMzg4TDQ5UllrZCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0ODoiaHR0cHM6Ly9yZWtydXRwcm8ucmVsYWJpZnkuY29tL3N1cGVyYWRtaW4vY29uZmlnIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHBzOi8vcmVrcnV0cHJvLnJlbGFiaWZ5LmNvbS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1769598859);

-- Dumping structure for table yesbisac_rekrutpro.system_configs
CREATE TABLE IF NOT EXISTS `system_configs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'string',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_configs_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.system_configs: ~10 rows (approximately)
INSERT INTO `system_configs` (`id`, `key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'whatsapp_phone', '628123456789', 'string', 'Nomor WhatsApp untuk Fonnte API (format: 628xxx)', '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(2, 'whatsapp_api_key', '', 'string', 'API Key dari Fonnte.com', '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(3, 'email_driver', 'smtp', 'string', 'Mail driver (smtp, sendmail, mailgun, ses)', '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(4, 'email_host', 'smtp.gmail.com', 'string', 'SMTP Host', '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(5, 'email_port', '587', 'string', 'SMTP Port', '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(6, 'email_username', '', 'string', 'SMTP Username/Email', '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(7, 'email_password', '', 'string', 'SMTP Password', '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(8, 'email_encryption', 'tls', 'string', 'Email Encryption (tls, ssl)', '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(9, 'email_from_address', 'noreply@rekrutpro.com', 'string', 'From Email Address', '2026-01-18 09:29:57', '2026-01-18 09:29:57'),
	(10, 'email_from_name', 'RekrutPro', 'string', 'From Name', '2026-01-18 09:29:57', '2026-01-18 09:29:57');

-- Dumping structure for table yesbisac_rekrutpro.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned DEFAULT NULL,
  `division_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `education` text DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `github_url` varchar(255) DEFAULT NULL,
  `portfolio_url` varchar(255) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `cv_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `otp_code` varchar(255) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `registration_step` tinyint(4) NOT NULL DEFAULT 1,
  `registration_completed` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  KEY `users_division_id_foreign` (`division_id`),
  CONSTRAINT `users_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table yesbisac_rekrutpro.users: ~9 rows (approximately)
INSERT INTO `users` (`id`, `role_id`, `division_id`, `name`, `email`, `phone`, `date_of_birth`, `address`, `education`, `experience`, `skills`, `linkedin_url`, `github_url`, `portfolio_url`, `profile_photo`, `cv_path`, `is_active`, `last_login_at`, `otp_code`, `otp_expires_at`, `is_verified`, `registration_step`, `registration_completed`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 1, NULL, 'Super Admin', 'admin@rekrutpro.com', '081234567890', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 1, 0, NULL, '$2y$12$w2HRgHA.hVFV2PnD8MGSEeiPCfNvnzAmDPeh.kG2/Kqg0.uhM1ATu', NULL, '2026-01-18 09:29:59', '2026-01-18 09:29:59'),
	(2, 2, 2, 'Alice Smith', 'hr@rekrutpro.com', '081234567891', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 1, 0, NULL, '$2y$12$hvgyBwTp5YRU9e8U5tAIMOlcQq6gqMNVz/kneTmuP3pMEMwCImj72', NULL, '2026-01-18 09:29:59', '2026-01-18 09:29:59'),
	(3, 3, 1, 'Bob Johnson', 'interviewer@rekrutpro.com', '081234567892', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 1, 0, NULL, '$2y$12$lwo9ijhXxbQ86fVqjdbDGuqJZ2A/c7tO4c3N8on0OZJa8Xm0c8NVO', NULL, '2026-01-18 09:29:59', '2026-01-18 09:29:59'),
	(4, 4, NULL, 'John Developer', 'candidate1@example.com', '081234567893', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 6, 1, NULL, '$2y$12$0XQhghosKtAGLGY1OAhFY.jLCfkOB8t1KS/XzfamDu2Q9.qgAlFVO', NULL, '2026-01-18 09:29:59', '2026-01-24 03:06:29'),
	(5, 4, NULL, 'Sarah Designer', 'candidate2@example.com', '081234567894', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 6, 1, NULL, '$2y$12$nt4XTfScuRfwpjQklTtBIuuQ9saz3T0vw3HrsZjNyaujr4Hw764j2', NULL, '2026-01-18 09:29:59', '2026-01-24 03:06:29'),
	(6, 4, NULL, 'Michael Marketing', 'candidate3@example.com', '081234567895', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 6, 1, NULL, '$2y$12$78TxnPJ1o4zngR3dWoEAIOlywJQ5MiE8rtm1BLy6pFuhc46fVmoYS', NULL, '2026-01-18 09:29:59', '2026-01-24 03:06:29'),
	(7, 4, NULL, 'Emma Analyst', 'candidate4@example.com', '081234567896', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 6, 1, NULL, '$2y$12$B5jaDAbE9HgF908Lt08f2uWxapsOuwNZ81YRNDO2nOr2bAF9vkMYS', NULL, '2026-01-18 09:29:59', '2026-01-24 03:06:29'),
	(8, 4, NULL, 'David HR', 'candidate5@example.com', '081234567897', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 6, 1, NULL, '$2y$12$CLm4p6XxyLltgKGdLfcWvOulVTHBoiAPEpQhjjECqMbVr2G09QyGC', NULL, '2026-01-18 09:29:59', '2026-01-24 03:06:29'),
	(9, 3, 1, 'Lisa Chen', 'interviewer2@rekrutpro.com', '081234567898', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 1, 0, NULL, '$2y$12$J0N.4i9zOzvMIGHQacUX1Op7JLCs8weAKdljjAqS5fTecjLZelrAy', NULL, '2026-01-18 09:29:59', '2026-01-18 09:29:59');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

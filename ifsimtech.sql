/*
 Navicat MySQL Data Transfer

 Source Server         : [ TCC ] - IFSIMTECH
 Source Server Type    : MySQL
 Source Server Version : 80044 (8.0.44)
 Source Host           : 127.0.0.1:3306
 Source Schema         : tcc

 Target Server Type    : MySQL
 Target Server Version : 80044 (8.0.44)
 File Encoding         : 65001

 Date: 26/11/2025 16:45:58
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for attempt_answers
-- ----------------------------
DROP TABLE IF EXISTS `attempt_answers`;
CREATE TABLE `attempt_answers`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `attempt_id` bigint UNSIGNED NOT NULL,
  `question_id` bigint UNSIGNED NOT NULL,
  `selected_label` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `attempt_answers_attempt_id_question_id_unique`(`attempt_id` ASC, `question_id` ASC) USING BTREE,
  INDEX `attempt_answers_question_id_foreign`(`question_id` ASC) USING BTREE,
  CONSTRAINT `attempt_answers_attempt_id_foreign` FOREIGN KEY (`attempt_id`) REFERENCES `attempts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `attempt_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Records of attempt_answers
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for attempt_question_stats
-- ----------------------------
DROP TABLE IF EXISTS `attempt_question_stats`;
CREATE TABLE `attempt_question_stats`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `topic_id` bigint UNSIGNED NOT NULL,
  `topic_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_attempts` int NOT NULL DEFAULT 0,
  `correct_attempts` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `attempt_question_stats_user_id_topic_id_unique`(`user_id` ASC, `topic_id` ASC) USING BTREE,
  INDEX `attempt_question_stats_topic_id_foreign`(`topic_id` ASC) USING BTREE,
  CONSTRAINT `attempt_question_stats_topic_id_foreign` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `attempt_question_stats_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Records of attempt_question_stats
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for attempts
-- ----------------------------
DROP TABLE IF EXISTS `attempts`;
CREATE TABLE `attempts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `exam_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` enum('in_progress','submitted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress',
  `score` int UNSIGNED NOT NULL DEFAULT 0,
  `time_seconds` int UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `attempts_exam_id_foreign`(`exam_id` ASC) USING BTREE,
  INDEX `attempts_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `attempts_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 44 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Records of attempts
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for exam_question
-- ----------------------------
DROP TABLE IF EXISTS `exam_question`;
CREATE TABLE `exam_question`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `exam_id` bigint UNSIGNED NOT NULL,
  `question_id` bigint UNSIGNED NOT NULL,
  `order` int UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `exam_question_exam_id_question_id_unique`(`exam_id` ASC, `question_id` ASC) USING BTREE,
  INDEX `exam_question_question_id_foreign`(`question_id` ASC) USING BTREE,
  CONSTRAINT `exam_question_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `exam_question_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 214 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Records of exam_question
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for exams
-- ----------------------------
DROP TABLE IF EXISTS `exams`;
CREATE TABLE `exams`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Simulado',
  `questions_count` int UNSIGNED NOT NULL,
  `filters` json NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `exams_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `exams_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 43 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Records of exams
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
BEGIN;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1, '0001_01_01_000000_create_users_table', 1), (2, '0001_01_01_000001_create_cache_table', 1), (3, '0001_01_01_000002_create_jobs_table', 1), (4, '2025_11_05_184945_create_subjects_table', 1), (5, '2025_11_05_184946_create_topics_table', 1), (6, '2025_11_05_184947_create_questions_table', 1), (7, '2025_11_05_184948_create_question_options_table', 1), (8, '2025_11_05_184949_create_exams_table', 1), (9, '2025_11_05_184950_create_exam_question_table', 1), (10, '2025_11_05_184951_create_attempts_table', 1), (11, '2025_11_05_184952_create_attempt_answers_table', 1), (12, '2025_11_05_204528_add_image_path_to_questions_table', 1), (13, '2025_11_05_210507_make_statement_nullable_on_questions_table', 1), (14, '2025_11_06_235728_drop_difficulty_from_questions_and_exams', 1), (15, '2025_11_13_025908_add_is_admin_to_users_table', 2), (18, '2025_11_13_232728_create_question_topic_table', 3), (19, '2025_11_14_071152_remove_topic_id_from_questions_table', 4), (20, '2025_11_24_201827_create_user_topic_stats_table', 5), (21, '2025_11_25_032359_create_attempt_question_stats_table', 6), (22, '2025_11_25_201736_create_study_goals_table', 7);
COMMIT;

-- ----------------------------
-- Table structure for question_options
-- ----------------------------
DROP TABLE IF EXISTS `question_options`;
CREATE TABLE `question_options`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` bigint UNSIGNED NOT NULL,
  `label` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `question_options_question_id_label_unique`(`question_id` ASC, `label` ASC) USING BTREE,
  CONSTRAINT `question_options_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 156 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Records of question_options
-- ----------------------------
BEGIN;
INSERT INTO `question_options` (`id`, `question_id`, `label`, `text`, `is_correct`, `created_at`, `updated_at`) VALUES (136, 1, 'A', 'presença de uma massa de ar frio proveniente do sul (Argentina), que se estabeleceu sobre o Estado devido à influência de uma massa de ar seco e quente que se instalou no Centro do Brasil.', 0, '2025-11-26 18:19:36', '2025-11-26 18:19:36'), (137, 1, 'B', 'formação da chuva ácida que se formou sob as cabeceiras dos rios Taquari, Caí, Pardo, Jacuí, Sinos e Gravataí, além do Guaíba, em Porto Alegre.', 1, '2025-11-26 18:19:37', '2025-11-26 18:19:37'), (138, 1, 'C', 'presença de uma massa de ar quente vinda do Uruguai, a qual provocou as chuvas que tomaram conta do Rio Grande do Sul, inclusive com enchente em Pelotas.', 0, '2025-11-26 18:19:37', '2025-11-26 18:19:37'), (139, 1, 'D', 'presença de uma massa de ar seco que chegou ao Rio Grande do Sul devido ao seu relevo de baixa altitude, o qual deixa entrar todos os tipos de massas de ar no nosso estado, sofrendo todo tipo de desastre climático.', 0, '2025-11-26 18:19:37', '2025-11-26 18:19:37'), (140, 1, 'E', '-----', 0, '2025-11-26 18:19:37', '2025-11-26 18:19:37'), (141, 2, 'A', 'desvio completo do curso do Rio Paraná, secando-o permanentemente.', 0, '2025-11-26 18:20:47', '2025-11-26 18:20:47'), (142, 2, 'B', 'alagamento de vastas áreas, resultando na extinção de várias espécies locais.', 0, '2025-11-26 18:20:47', '2025-11-26 18:20:47'), (143, 2, 'C', 'produção de energia insuficiente na região, diante do tamanho da construção.', 1, '2025-11-26 18:20:47', '2025-11-26 18:20:47'), (144, 2, 'D', 'criação de novas reservas de biodiversidade, para compensar os danos ambientais.', 0, '2025-11-26 18:20:48', '2025-11-26 18:20:48'), (145, 2, 'E', '-----', 0, '2025-11-26 18:20:48', '2025-11-26 18:20:48'), (146, 3, 'A', '-5', 0, '2025-11-26 18:22:26', '2025-11-26 18:22:26'), (147, 3, 'B', '5', 1, '2025-11-26 18:22:26', '2025-11-26 18:22:26'), (148, 3, 'C', '13', 0, '2025-11-26 18:22:26', '2025-11-26 18:22:26'), (149, 3, 'D', '15', 0, '2025-11-26 18:22:26', '2025-11-26 18:22:26'), (150, 3, 'E', '---', 0, '2025-11-26 18:22:26', '2025-11-26 18:22:26'), (151, 4, 'A', 'hipérbole.', 0, '2025-11-26 18:23:42', '2025-11-26 18:23:42'), (152, 4, 'B', 'aliteração.', 1, '2025-11-26 18:23:42', '2025-11-26 18:23:42'), (153, 4, 'C', 'eufemismo.', 0, '2025-11-26 18:23:42', '2025-11-26 18:23:42'), (154, 4, 'D', 'prosopopeia.', 0, '2025-11-26 18:23:42', '2025-11-26 18:23:42'), (155, 4, 'E', '---', 0, '2025-11-26 18:23:42', '2025-11-26 18:23:42');
COMMIT;

-- ----------------------------
-- Table structure for question_topic
-- ----------------------------
DROP TABLE IF EXISTS `question_topic`;
CREATE TABLE `question_topic`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` bigint UNSIGNED NOT NULL,
  `topic_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `question_topic_question_id_topic_id_unique`(`question_id` ASC, `topic_id` ASC) USING BTREE,
  INDEX `question_topic_topic_id_foreign`(`topic_id` ASC) USING BTREE,
  CONSTRAINT `question_topic_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `question_topic_topic_id_foreign` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Records of question_topic
-- ----------------------------
BEGIN;
INSERT INTO `question_topic` (`id`, `question_id`, `topic_id`, `created_at`, `updated_at`) VALUES (26, 1, 11, NULL, NULL), (27, 2, 9, NULL, NULL), (28, 3, 1, NULL, NULL), (29, 4, 4, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for questions
-- ----------------------------
DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` bigint UNSIGNED NOT NULL,
  `statement` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `year` year NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `questions_subject_id_foreign`(`subject_id` ASC) USING BTREE,
  CONSTRAINT `questions_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Records of questions
-- ----------------------------
BEGIN;
INSERT INTO `questions` (`id`, `subject_id`, `statement`, `image_path`, `source`, `year`, `created_at`, `updated_at`) VALUES (1, 4, NULL, 'questions/2024/NvWOFUYgVDpziDOxKmF1sE98W3axh9rZ8P0ttQF1.png', 'Ifsul', 2024, '2025-11-26 18:19:36', '2025-11-26 18:19:36'), (2, 3, NULL, 'questions/2025/o6mhFztPPRz81PRdwoU6YCOtfHDOzoNMV33SeGwY.png', 'Ifsul', 2025, '2025-11-26 18:20:47', '2025-11-26 18:20:47'), (3, 1, NULL, 'questions/2025/uTDHxgwSSAUvTZO1BVqkLf9v778lDWzhk1uzij4Z.png', 'Ifsul', 2025, '2025-11-26 18:22:25', '2025-11-26 18:22:26'), (4, 2, NULL, 'questions/2025/cNLaa90fPbnf1axjqXTz8WdmtmeUxREtyREbBYj8.png', NULL, NULL, '2025-11-26 18:23:41', '2025-11-26 18:23:42');
COMMIT;

-- ----------------------------
-- Table structure for study_goals
-- ----------------------------
DROP TABLE IF EXISTS `study_goals`;
CREATE TABLE `study_goals`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `topic_id` bigint UNSIGNED NULL DEFAULT NULL,
  `attempt_id` bigint UNSIGNED NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('pending','done') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `due_date` date NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `study_goals_user_id_foreign`(`user_id` ASC) USING BTREE,
  INDEX `study_goals_topic_id_foreign`(`topic_id` ASC) USING BTREE,
  INDEX `study_goals_attempt_id_foreign`(`attempt_id` ASC) USING BTREE,
  CONSTRAINT `study_goals_attempt_id_foreign` FOREIGN KEY (`attempt_id`) REFERENCES `attempts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `study_goals_topic_id_foreign` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `study_goals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Records of study_goals
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for subjects
-- ----------------------------
DROP TABLE IF EXISTS `subjects`;
CREATE TABLE `subjects`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `subjects_name_unique`(`name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Records of subjects
-- ----------------------------
BEGIN;
INSERT INTO `subjects` (`id`, `name`, `created_at`, `updated_at`) VALUES (1, 'Matemática', '2025-11-13 02:54:09', '2025-11-13 02:54:09'), (2, 'Português', '2025-11-13 02:54:09', '2025-11-13 02:54:09'), (3, 'História', '2025-11-13 02:54:09', '2025-11-13 02:54:09'), (4, 'Geografia', '2025-11-13 02:54:09', '2025-11-13 02:54:09');
COMMIT;

-- ----------------------------
-- Table structure for topics
-- ----------------------------
DROP TABLE IF EXISTS `topics`;
CREATE TABLE `topics`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `topics_subject_id_name_unique`(`subject_id` ASC, `name` ASC) USING BTREE,
  CONSTRAINT `topics_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Records of topics
-- ----------------------------
BEGIN;
INSERT INTO `topics` (`id`, `subject_id`, `name`, `created_at`, `updated_at`) VALUES (1, 1, 'Operações Básicas', '2025-11-13 02:54:09', '2025-11-13 02:54:09'), (2, 1, 'Porcentagem', '2025-11-13 02:54:09', '2025-11-13 02:54:09'), (3, 1, 'Equações do 1º Grau', '2025-11-13 02:54:09', '2025-11-13 02:54:09'), (4, 2, 'Interpretação de Texto', '2025-11-13 02:54:09', '2025-11-13 02:54:09'), (5, 2, 'Ortografia', '2025-11-13 02:54:09', '2025-11-13 02:54:09'), (6, 2, 'Classes Gramaticais', '2025-11-13 02:54:09', '2025-11-13 02:54:09'), (7, 3, 'Brasil Colônia', '2025-11-13 02:54:09', '2025-11-13 02:54:09'), (8, 3, 'Idade Média', '2025-11-13 02:54:09', '2025-11-13 02:54:09'), (9, 3, 'Revolução Industrial', '2025-11-13 02:54:09', '2025-11-13 02:54:09'), (10, 4, 'Relevo Brasileiro', '2025-11-13 02:54:09', '2025-11-13 02:54:09'), (11, 4, 'Climas do Brasil', '2025-11-13 02:54:09', '2025-11-13 02:54:09'), (12, 4, 'Globalização', '2025-11-13 02:54:09', '2025-11-13 02:54:09'), (13, 4, 'Cooredenadas Geográficas', '2025-11-26 18:44:40', '2025-11-26 18:44:40');
COMMIT;

-- ----------------------------
-- Table structure for user_topic_stats
-- ----------------------------
DROP TABLE IF EXISTS `user_topic_stats`;
CREATE TABLE `user_topic_stats`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `topic_id` bigint UNSIGNED NOT NULL,
  `total_attempts` int UNSIGNED NOT NULL DEFAULT 0,
  `correct_attempts` int UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `user_topic_stats_user_id_topic_id_unique`(`user_id` ASC, `topic_id` ASC) USING BTREE,
  INDEX `user_topic_stats_topic_id_foreign`(`topic_id` ASC) USING BTREE,
  CONSTRAINT `user_topic_stats_topic_id_foreign` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `user_topic_stats_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Records of user_topic_stats
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `is_admin`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES (1, 'Administrador', 'admin@if.com', '2025-11-26 18:01:05', 1, '$2y$12$pibg4FpN0ANl0aNfDZBil.3ZdeHUAFEKvA883HSp8SCJO7sx9ix3C', NULL, '2025-11-26 18:01:05', '2025-11-26 18:01:05');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;

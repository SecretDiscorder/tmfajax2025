/*
//============================================================+
File name   : db_data.sql
Begin       : 2004-04-28
Last Update : 2017-04-22

Description : Installation (default) data for TCExam DB
Database    : PostgreSQL 8+ / MySQL 4.1+

Author: Nicola Asuni

(c) Copyright:
              Nicola Asuni
              Tecnick.com LTD
              www.tecnick.com
              info@tecnick.com

License:
Copyright (C) 2004-2018 Nicola Asuni - Tecnick.com LTD
   See LICENSE.TXT file for more information.
//============================================================+
*/
SET FOREIGN_KEY_CHECKS = 0;
INSERT INTO `tce_user_groups` (`group_id`, `group_name`) VALUES
(1, 'default');
INSERT INTO `tce_usrgroups` (`usrgrp_user_id`, `usrgrp_group_id`) VALUES
(2, 1),
(9, 1);
INSERT INTO tce_users (user_id,user_regdate,user_ip,user_name,user_password,user_level) VALUES (2,'2001-01-01 01:01:01', '0.0.0.0', 'anonymous', 'anonymous', 1);
INSERT INTO tce_users (user_id,user_regdate,user_ip,user_name,user_password,user_level) VALUES (9,'2001-01-01 01:01:01', '127.0.0.0', 'admin', 'admin12345678', 10);
INSERT INTO tce_modules (module_name,module_enabled) VALUES ('default', '1');
INSERT INTO `tce_chat_log` (`log_id`, `user_id`, `login_time`, `logout_time`) VALUES
(1, 9, '2025-03-26 13:51:20', NULL),
(2, 2, '2025-03-26 13:57:20', NULL),
(3, 9, '2025-03-26 13:58:00', NULL),
(4, 2, '2025-03-26 14:34:09', NULL),
(5, 9, '2025-03-26 14:49:48', NULL),
(6, 9, '2025-03-26 14:52:06', NULL),
(7, 9, '2025-03-26 14:56:13', NULL),
(8, 2, '2025-03-26 16:03:08', NULL),
(9, 2, '2025-03-26 16:03:17', NULL),
(10, 2, '2025-03-26 16:03:27', NULL),
(11, 2, '2025-03-26 16:03:44', NULL),
(12, 2, '2025-03-26 16:05:50', NULL),
(13, 2, '2025-03-27 03:30:01', NULL);


INSERT INTO `tce_chat_msg` (`msg_id`, `log_id`, `sender_id`, `receiver_id`, `message`, `sent_at`, `status`, `to_group_id`) VALUES
(12, 5, 9, 2, 'HALLO', '2025-03-26 14:49:48', 2, 0),
(13, 6, 9, 2, 'What do you do?', '2025-03-26 14:52:06', 2, 0),
(14, 7, 9, 2, 'HALLO', '2025-03-26 14:56:13', 2, 0),
(15, 9, 2, 28, 'hallo', '2025-03-26 16:03:17', 1, 0);
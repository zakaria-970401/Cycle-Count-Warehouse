/*
SQLyog Ultimate
MySQL - 10.4.22-MariaDB : Database - cycle-count
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `auth_group` */

DROP TABLE IF EXISTS `auth_group`;

CREATE TABLE `auth_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `auth_group` */

insert  into `auth_group`(`id`,`name`,`created_at`,`updated_at`) values 
(1,'Super Admin',NULL,NULL),
(2,'Admin',NULL,NULL),
(3,'User Gudang',NULL,NULL),
(4,'Manager',NULL,NULL);

/*Table structure for table `auth_group_permission` */

DROP TABLE IF EXISTS `auth_group_permission`;

CREATE TABLE `auth_group_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `auth_group_permission` */

insert  into `auth_group_permission`(`id`,`group_id`,`permission_id`,`created_at`,`updated_at`) values 
(6,2,1,'2022-08-07 20:13:38','2022-08-07 20:13:38'),
(7,2,3,'2022-08-07 20:13:38','2022-08-07 20:13:38'),
(8,1,1,'2022-08-07 20:15:37','2022-08-07 20:15:37'),
(9,1,2,'2022-08-07 20:15:37','2022-08-07 20:15:37'),
(10,1,3,'2022-08-07 20:15:37','2022-08-07 20:15:37'),
(11,1,4,'2022-08-07 20:15:37','2022-08-07 20:15:37'),
(12,1,5,'2022-08-07 20:15:37','2022-08-07 20:15:37'),
(18,3,2,'2022-08-10 20:55:45','2022-08-10 20:55:45'),
(19,4,5,'2022-08-10 21:39:06','2022-08-10 21:39:06');

/*Table structure for table `auth_permission` */

DROP TABLE IF EXISTS `auth_permission`;

CREATE TABLE `auth_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codename` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `auth_permission` */

insert  into `auth_permission`(`id`,`name`,`codename`,`created_at`,`updated_at`) values 
(1,'menu_admin','menu_gudang',NULL,NULL),
(2,'menu_gudang','gudang',NULL,NULL),
(3,'generate_excel','generate_excel',NULL,NULL),
(4,'menu_superadmin','menu_superadmin','2022-08-07 20:12:52',NULL),
(5,'report','report','2022-08-07 20:15:30',NULL);

/*Table structure for table `cycle_count` */

DROP TABLE IF EXISTS `cycle_count`;

CREATE TABLE `cycle_count` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `blok` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `material` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `case_qty` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `case_uom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '-',
  `qty_lapangan` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty_validasi` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `selisih` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upload_at` datetime DEFAULT NULL,
  `upload_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `count_at` datetime DEFAULT NULL,
  `count_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revisi_at` datetime DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `view_schedule` (`blok`,`reason`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cycle_count` */

insert  into `cycle_count`(`id`,`blok`,`material`,`description`,`case_qty`,`case_uom`,`qty_lapangan`,`qty_validasi`,`reason`,`selisih`,`upload_at`,`upload_by`,`count_at`,`count_by`,`revisi_at`,`status`) values 
(1,'BK','AF09WR(S)','ALUMINIUM FERRULE DIN 3093 STANDARD LENGTH TYPEM ALUMINIUM FERRULE DIN 3093 STANDARD','60','PCS',NULL,NULL,NULL,NULL,'2022-07-11 21:18:50','ini admin','2022-07-11 21:18:50','Yanto Supranto',NULL,0),
(2,'BK','29   AS-LBG-346- C13-G','13MM X 2 TON LB-346 BOLT TYPE GALVD ANCHOR SHACKLE LEBEON FRANCE','61','PCS',NULL,NULL,NULL,NULL,'2022-07-11 21:18:50','ini admin','2022-07-11 21:18:50','Yanto Supranto',NULL,0),
(3,'BK','30   AS-LBG-346- C16-G','16MM X 3.25 TON LB-346 BOLT TYPE GALVD ANCHOR SHACKLE LEBEON FRANCE','62','PCS',NULL,NULL,NULL,NULL,'2022-07-11 21:18:50','ini admin','2022-07-11 21:18:50','Yanto Supranto',NULL,0),
(4,'BK','31   AS-LBG-346- C25-G','25MM X 8.5 TON LB-346 BOLT TYPE GALVD ANCHOR SHACKLE LEBEON FRANCE','63','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:18:50','Yanto Supranto',NULL,0),
(5,'BK','32   AS-LBG-346- C29-G','29MM X 9.5 TON LB-346 BOLT TYPE GALVD ANCHOR SHACKLE LEBEON FRANCE','64','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:18:50','Yanto Supranto',NULL,0),
(6,'BK','33   AS-LBG-346- C35-G','35MM X 13.5 TON LB-346 BOLT TYPE GALVD ANCHOR SHACKLE LEBEON FRANCE','65','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:18:50','Yanto Supranto',NULL,0),
(7,'BK','34   AS-LBG-346- C76-G','76MM X 85 TON LB-346 BOLT TYPE GALVD ANCHOR SHACKLE LEBEON FRANCE','66','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:18:50','Yanto Supranto',NULL,0),
(8,'BK','34   AS-LBG-346- C77-G','26MM WLL 6.7T MASTERLINK ASSEMBLY FOR 10MM CHAIN TWN-','67','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:18:50','Yanto Supranto',NULL,0),
(9,'BK','34   AS-LBG-346- C78-G','0809 THIELE GERMANY','68','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:18:50','Yanto Supranto',NULL,0),
(10,'BA','34   AS-LBG-346- C79-G','32MM WLL 11.2T MASTERLINK ASSEMBLY FOR 13MM CHAIN TWN-','69','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:20:11','Yanto Supranto',NULL,0),
(11,'BA','34   AS-LBG-346- C80-G','0809 THIELE GERMANY','70','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:20:11','Yanto Supranto',NULL,0),
(12,'BA','34   AS-LBG-346- C81-G','40MM WLL 17T MASTERLINK ASSEMBLY FOR 16MM CHAIN TWN-','71','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:20:11','Yanto Supranto',NULL,0),
(13,'BA','34   AS-LBG-346- C82-G','0809 THIELE GERMANY','578','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:20:11','Yanto Supranto',NULL,0),
(14,'BA','34   AS-LBG-346- C83-G','45MM WLL 21.2T MASTERLINK ASSEMBLY FOR 18MM CHAIN TWN-','579','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:20:11','Yanto Supranto',NULL,0),
(15,'BA','34   AS-LBG-346- C84-G','0809 THIELE GERMANY','580','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:20:11','Yanto Supranto',NULL,0),
(16,'BA','34   AS-LBG-346- C85-G','50MM WLL 31.5T MASTERLINK ASSEMBLY FOR 22MM CHAIN TWN-','581','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:20:11','Yanto Supranto',NULL,0),
(17,'BA','34   AS-LBG-346- C86-G','0809 THIELE GERMANY','582','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:20:11','Yanto Supranto',NULL,0),
(18,'BA','34   AS-LBG-346- C87-G','1.1/4 INCH WLL 39100LBS CROSBY A-342W ALLOY (WIDER)','583','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:20:11','Yanto Supranto',NULL,0),
(19,'BA','34   AS-LBG-346- C88-G','MASTERLINK','79','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:20:11','Yanto Supranto',NULL,0),
(20,'BA','34   AS-LBG-346- C89-G','1.1/2 INCH WLL 61100LBS CROSBY A-342W ALLOY (WIDER)','57','PCS',NULL,'57','SALAH INPUT',NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:20:11','Yanto Supranto','2022-08-10 21:20:29',0),
(21,'AA','34   AS-LBG-346- C90-G','MASTERLINK','58','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:16:52','Ahmad Zakaria',NULL,0),
(22,'AA','34   AS-LBG-346- C91-G','7/16 INCH - 12MM CROSBY A-344 WELDED MASTERLINK','59','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:16:52','Ahmad Zakaria',NULL,0),
(23,'AA','34   AS-LBG-346- C92-G','3/4 INCH - 19/20MM CROSBY A-344 WELDED MASTERLINK','60','PCS',NULL,NULL,NULL,NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:16:52','Ahmad Zakaria',NULL,0),
(24,'AA','34   AS-LBG-346- C93-G','7/8 INCH - 22MM CROSBY A-344 WELDED MASTERLINK','61','PCS',NULL,'61','SALAH PACKING',NULL,'2022-08-10 21:08:50','ini admin','2022-08-10 21:16:52','Ahmad Zakaria','2022-08-10 21:17:47',0),
(25,'AC','34   AS-LBG-346- C94-G','1 INCH - 25MM CROSBY A-344 WELDED MASTERLINK','62','PCS',NULL,'62','SALAH INPUT',NULL,'2022-09-10 21:08:50','ini admin','2022-08-10 21:12:00','Ilham Naxx Bekasi','2022-08-10 21:14:31',0),
(26,'AC','34   AS-LBG-346- C95-G','1.1/8 INCH - 28MM CROSBY A-344 WELDED MASTERLINK','63','PCS',NULL,'63','SALAH INPUT',NULL,'2022-09-10 21:08:50','ini admin','2022-08-10 21:12:00','Ilham Naxx Bekasi','2022-08-10 21:14:31',0),
(27,'AC','34   AS-LBG-346- C96-G','1.7/32 INCH - 31MM CROSBY A-344 WELDED MASTERLINK','64','PCS',NULL,'64','TERSELIP',NULL,'2022-09-10 21:08:50','ini admin','2022-08-10 21:12:00','Ilham Naxx Bekasi','2022-08-10 21:14:31',0),
(28,'AC','34   AS-LBG-346- C97-G','1.7/16 INCH - 36MM CROSBY A-344 WELDED MASTERLINK','65','PCS',NULL,'65','SALAH INPUT',NULL,'2022-09-10 21:08:50','ini admin','2022-08-10 21:12:00','Ilham Naxx Bekasi','2022-08-10 21:14:56',0),
(29,'BB','30001630','OUTER SUKSES\'S SOTO ALLERGEN','3','ROL',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(30,'BA','30001630','OUTER SUKSES\'S SOTO ALLERGEN','4','ROL',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(31,'BC','30001630','OUTER SUKSES\'S SOTO ALLERGEN','5','ROL',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(32,'KB','30001630','OUTER SUKSES\'S SOTO ALLERGEN','3','ROL','80','3','SALAH PACKING',NULL,'2022-08-13 13:27:30','Anton Cahyo','2022-08-13 13:34:59','Heri Lesmana','2022-08-13 13:35:18',0),
(33,'KA','30001630','OUTER SUKSES\'S SOTO ALLERGEN','4','ROL',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(34,'KC','30001630','OUTER SUKSES\'S SOTO ALLERGEN','5','ROL','5',NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo','2022-08-13 13:34:10','Heri Lesmana',NULL,0),
(35,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(36,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(37,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(38,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(39,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(40,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(41,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(42,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(43,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(44,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(45,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(46,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(47,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(48,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(49,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','600','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(50,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(51,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(52,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(53,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(54,'BK','30001654','KARDUS GORENG MALAYSIA REJUVENATE 2021','800','PCS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(55,'BK','30001630','OUTER SUKSES\'S SOTO ALLERGEN','6','ROL',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(56,'BK','30001630','OUTER SUKSES\'S SOTO ALLERGEN','42','ROL',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(57,'BK','30001630','OUTER SUKSES\'S SOTO ALLERGEN','48','ROL',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(58,'BK','30001630','OUTER SUKSES\'S SOTO ALLERGEN','48','ROL',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(59,'BK','30001630','OUTER SUKSES\'S SOTO ALLERGEN','48','ROL',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(60,'BK','30001630','OUTER SUKSES\'S SOTO ALLERGEN','2','ROL',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(61,'BB','30001630','OUTER SUKSES\'S SOTO ALLERGEN','3','ROL',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(62,'BA','30001630','OUTER SUKSES\'S SOTO ALLERGEN','4','ROL',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(63,'AD','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(64,'AD','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(65,'AD','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(66,'AD','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(67,'AD','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(68,'AD','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(69,'AD','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(70,'AD','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(71,'AD','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(72,'AD','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(73,'AD','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(74,'AD','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(75,'AD','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(76,'AD','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(77,'AD','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(78,'AE','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(79,'AE','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(80,'AE','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(81,'AE','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(82,'AE','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1),
(83,'BN','40001490','OIL MIE GORENG RTG RACKING NEW','65','DUS',NULL,NULL,NULL,NULL,'2022-08-13 13:27:30','Anton Cahyo',NULL,NULL,NULL,1);

/*Table structure for table `cycle_count_logg` */

DROP TABLE IF EXISTS `cycle_count_logg`;

CREATE TABLE `cycle_count_logg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `konten` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;

/*Data for the table `cycle_count_logg` */

insert  into `cycle_count_logg`(`id`,`konten`,`type`,`created_at`,`created_by`,`status`) values 
(1,'ini admin Mengupload data excel','admin','2022-08-10 21:08:50','ini admin',1),
(2,'Ilham Naxx Bekasi Memulai Perhitungan Cycle Count di BLOK AC','gudang','2022-08-10 21:10:19','Ilham Naxx Bekasi',1),
(3,'Ilham Naxx Bekasi Menyelesaikan Perhitungan Cycle Count di BLOK AC','gudang','2022-08-10 21:12:00','Ilham Naxx Bekasi',1),
(4,'Ilham Naxx Bekasi Menyelesaikan Proses Revisi Cycle Count di BLOK AC','gudang','2022-08-10 21:14:31','Ilham Naxx Bekasi',1),
(5,'Ilham Naxx Bekasi Menyelesaikan Proses Revisi Cycle Count di BLOK AC','gudang','2022-08-10 21:14:31','Ilham Naxx Bekasi',1),
(6,'Ilham Naxx Bekasi Menyelesaikan Proses Revisi Cycle Count di BLOK AC','gudang','2022-08-10 21:14:31','Ilham Naxx Bekasi',1),
(7,'Ilham Naxx Bekasi Menyelesaikan Proses Revisi Cycle Count di BLOK AC','gudang','2022-08-10 21:14:56','Ilham Naxx Bekasi',1),
(8,'Ahmad Zakaria Memulai Perhitungan Cycle Count di BLOK AA','gudang','2022-08-10 21:16:02','Ahmad Zakaria',1),
(9,'Ahmad Zakaria Menyelesaikan Perhitungan Cycle Count di BLOK AA','gudang','2022-08-10 21:16:52','Ahmad Zakaria',1),
(10,'Ahmad Zakaria Menyelesaikan Proses Revisi Cycle Count di BLOK AA','gudang','2022-08-10 21:17:47','Ahmad Zakaria',1),
(11,'Yanto Supranto Memulai Perhitungan Cycle Count di BLOK BK','gudang','2022-08-10 21:18:20','Yanto Supranto',1),
(12,'Yanto Supranto Menyelesaikan Perhitungan Cycle Count di BLOK BK','gudang','2022-08-10 21:18:50','Yanto Supranto',1),
(13,'Yanto Supranto Memulai Perhitungan Cycle Count di BLOK BA','gudang','2022-08-10 21:19:19','Yanto Supranto',1),
(14,'Yanto Supranto Menyelesaikan Perhitungan Cycle Count di BLOK BA','gudang','2022-08-10 21:20:11','Yanto Supranto',1),
(15,'Yanto Supranto Menyelesaikan Proses Revisi Cycle Count di BLOK BA','gudang','2022-08-10 21:20:29','Yanto Supranto',1),
(16,'Anton Cahyo Mengupload data excel','admin','2022-08-13 13:27:13','Anton Cahyo',1),
(17,'Anton Cahyo Mengupload data excel','admin','2022-08-13 13:27:30','Anton Cahyo',1),
(18,'Heri Lesmana Memulai Perhitungan Cycle Count di BLOK KC','gudang','2022-08-13 13:28:06','Heri Lesmana',1),
(19,'Heri Lesmana Menyelesaikan Perhitungan Cycle Count di BLOK KC','gudang','2022-08-13 13:34:10','Heri Lesmana',1),
(20,'Heri Lesmana Memulai Perhitungan Cycle Count di BLOK KB','gudang','2022-08-13 13:34:53','Heri Lesmana',1),
(21,'Heri Lesmana Menyelesaikan Perhitungan Cycle Count di BLOK KB','gudang','2022-08-13 13:34:59','Heri Lesmana',1),
(22,'Heri Lesmana Menyelesaikan Proses Revisi Cycle Count di BLOK KB','gudang','2022-08-13 13:35:18','Heri Lesmana',1);

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'0000_00_00_000000_create_websockets_statistics_entries_table',1);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auth_group` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`username`,`password`,`auth_group`,`created_at`,`created_by`,`updated_by`,`updated_at`,`status`) values 
(13,'Anton Cahyo','1122','$2y$10$I65gQamoLjk6iHQR/8uoFemrM8PIHKcw0emS5BdTHbDg8td.6Fh3y','1','2022-08-07 19:58:31','admin','Anton Cahyo','2022-08-12 22:55:28',1),
(14,'Ahmad Zakaria','021','$2y$10$JUv.6D/V9w8AYberpPjCH.TZKwWeP00kBfWiI42pLPlDHghi3Y8Jm','3','2022-08-07 20:16:52','Anton Cahyo','Anton Cahyo','2022-08-07 20:39:02',1),
(15,'ini admin','112233','$2y$10$rXxZt8kGXRJ1KSkR0xAUtO8mQ5X28nCJTbEBuDlPncp7GnrN5w6S6','2','2022-08-07 20:20:34','Anton Cahyo',NULL,NULL,1),
(16,'Heri Lesmana','022','$2y$10$OLGAds6OJA.2RkOPom7edO34.ydnsrc9QmRMi2yZur2NIhlsIx0s.','3','2022-08-07 20:39:15','Anton Cahyo',NULL,NULL,1),
(17,'Yanto Supranto','023','$2y$10$DxPGT8Z8uGTyxA9wZbW6he33CFgaX4MzWgCMJXTpBqaZBvagy2SWy','3','2022-08-07 20:39:28','Anton Cahyo',NULL,NULL,1),
(19,'Ilham Naxx Bekasi','045','$2y$10$tSecrd9qtt9uPnHqRK/VkuMEwHwqJFG0fZtcmG.kTB76tkfQxEBM2','3','2022-08-10 20:51:36','Anton Cahyo','Anton Cahyo','2022-08-10 20:53:45',1),
(20,'Andi Farandi','0895','$2y$10$nqM9WRXaj2NRfBZmBExNvuZNA9fLb1/XzIa3JVmG.nTj9uqmrtYvi','4','2022-08-10 21:35:38','Anton Cahyo','Anton Cahyo','2022-08-10 21:38:31',1);

/*Table structure for table `websockets_statistics_entries` */

DROP TABLE IF EXISTS `websockets_statistics_entries`;

CREATE TABLE `websockets_statistics_entries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `peak_connection_count` int(11) NOT NULL,
  `websocket_message_count` int(11) NOT NULL,
  `api_message_count` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `websockets_statistics_entries` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

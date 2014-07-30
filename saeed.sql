-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2012 at 10:23 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `saeed`
--

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE IF NOT EXISTS `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `name`, `phone`, `address`) VALUES
(1, 'غير محدد', NULL, NULL),
(13, 'شركة برسيل', '2566766', NULL),
(14, 'شركة مدار', NULL, NULL),
(15, 'شركة دعبول', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` varchar(20) NOT NULL,
  `displayName` varchar(100) NOT NULL,
  `description` text,
  `law` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `name`, `value`, `displayName`, `description`, `law`) VALUES
(1, 'Period Between Visit', '3', 'الفترة الفاصلة بين الزيارات', NULL, 'يجب ترك عدد من الايام بين زيارتين  متتاليتين للطبيب لا يقل عن '),
(2, 'Period Between Visit And Treatment', '7', 'الفترة الفاصلة بين زيارة طبيب والحصول على الاستشفاء ', NULL, 'في حال طلب الطبيب دواء او معالجة في مستشفى فيجب ان تتم هذه العملية بعد زيارة الطبيب بعدد من الايام اقصاه '),
(3, 'Max Money Per Year', '200000', 'السقف المالي السنوي', NULL, 'يجب على المشترك ألا يتجاوز المبلغ التاميني السنوي المخصص له والمقدر ب '),
(4, 'Max Visits Per Year', '20', 'سقف عدد المعالجات الأقصى السنوي', NULL, 'يجب على المشترك ألا يتجاوز عدد الزيارات السنوي المخصص له والمقدر ب '),
(5, 'Annual Subscription', '20000', 'رسم الاشتراك السنوي', NULL, 'ان رسم الاشتراك السنوي للفرد هو ');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE IF NOT EXISTS `doctor` (
  `user_id` int(11) NOT NULL,
  `firstName` varchar(25) NOT NULL,
  `lastName` varchar(25) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `specialization_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `specialization_id` (`specialization_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`user_id`, `firstName`, `lastName`, `gender`, `specialization_id`) VALUES
(87, 'بسام ', 'الشريفي', 'male', 1);

-- --------------------------------------------------------

--
-- Table structure for table `doctorspecialization`
--

CREATE TABLE IF NOT EXISTS `doctorspecialization` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `doctorspecialization`
--

INSERT INTO `doctorspecialization` (`id`, `name`) VALUES
(1, 'جراحة عظمية'),
(2, 'جراحة عامة'),
(3, 'أذن، انف، حنجرة'),
(4, 'عينية'),
(5, 'أسنان'),
(6, 'داخلية'),
(7, 'نسائية');

-- --------------------------------------------------------

--
-- Table structure for table `doctorvisit`
--

CREATE TABLE IF NOT EXISTS `doctorvisit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` double NOT NULL,
  `date` datetime NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `notes` text,
  `paid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `doctor_id` (`doctor_id`),
  KEY `patient_id` (`patient_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `doctorvisit`
--

INSERT INTO `doctorvisit` (`id`, `price`, `date`, `doctor_id`, `patient_id`, `notes`, `paid`) VALUES
(4, 600, '2012-12-13 12:00:09', 87, 16, NULL, 0),
(5, 1000, '2012-12-13 12:19:36', 87, 17, NULL, 0),
(6, 500, '2012-12-20 06:08:30', 87, 16, NULL, 0),
(7, 10, '2012-12-25 02:38:03', 87, 14, NULL, 0),
(8, 50000, '2012-12-10 04:55:08', 87, 15, NULL, 0),
(10, 500, '2012-12-26 10:26:40', 87, 16, 'nmjbhj', 0);

-- --------------------------------------------------------

--
-- Table structure for table `hospital`
--

CREATE TABLE IF NOT EXISTS `hospital` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hospital`
--

INSERT INTO `hospital` (`user_id`, `name`) VALUES
(93, 'مشفى عبد'),
(96, 'مشفى المنار'),
(97, 'مشفى دار الشفاء'),
(98, 'مشفى المواساة'),
(99, 'مشفى دار الشفاء');

-- --------------------------------------------------------

--
-- Table structure for table `hospitalservice`
--

CREATE TABLE IF NOT EXISTS `hospitalservice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doctorVisit_id` int(11) NOT NULL,
  `dateOfDoctorVisit` datetime NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` double DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `hospital_id` int(11) DEFAULT NULL,
  `notes` text,
  `paid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `doctorVisit_id` (`doctorVisit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `hospitalservice`
--

INSERT INTO `hospitalservice` (`id`, `doctorVisit_id`, `dateOfDoctorVisit`, `name`, `price`, `date`, `hospital_id`, `notes`, `paid`) VALUES
(1, 4, '2012-12-23 12:17:05', 'صورة شعاعية للحنجرة', 2000, '2012-12-26 10:25:31', 93, '', 0),
(2, 5, '2012-12-23 12:20:31', 'اختبار الجهد', NULL, NULL, NULL, NULL, 0),
(3, 6, '2012-12-24 06:09:21', 'تحليل من أجل البحصة', 1111, '2012-12-24 20:58:37', 93, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE IF NOT EXISTS `medicine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doctorVisit_id` int(11) NOT NULL,
  `dateOfDoctorVisit` datetime NOT NULL,
  `name` varchar(50) NOT NULL,
  `alternateName` varchar(50) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `pharmacy_id` int(11) DEFAULT NULL,
  `notes` text,
  `paid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `doctorVisit_id` (`doctorVisit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `medicine`
--

INSERT INTO `medicine` (`id`, `doctorVisit_id`, `dateOfDoctorVisit`, `name`, `alternateName`, `price`, `date`, `pharmacy_id`, `notes`, `paid`) VALUES
(2, 4, '2012-12-23 12:16:04', 'اكتفيد', NULL, NULL, NULL, NULL, NULL, 0),
(3, 4, '2012-12-10 12:16:16', 'بانادول', NULL, 50, '2012-12-10 20:57:59', 86, '', 0),
(4, 4, '2012-12-23 12:16:25', 'بروفين عيار 500', 'بروفين عيار 400', 200, '2012-12-26 09:56:38', 86, '', 0),
(5, 5, '2012-12-23 12:19:54', 'اسبيرين 100', NULL, NULL, NULL, NULL, NULL, 0),
(6, 5, '2012-12-23 12:20:13', 'لوتيد', NULL, NULL, NULL, NULL, NULL, 0),
(7, 6, '2012-12-24 06:08:39', 'باراسيتامول', 'سيتامول', 200, '2012-12-26 10:24:32', 86, '', 0),
(8, 6, '2012-12-24 06:08:57', 'سباسموسبلجين', NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `link` varchar(100) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `description`, `link`, `date`) VALUES
(8, 'ساعد للتأمين في معرض السيارات سيرموتورشو', 'زورونا في معرض السيارات سيرموتورشو Syrmotorshow 2010   المقام في مدينة المعارض الجديدة في دمشق من 1-7-2010 وحتى 7-7-2010 في الجناح رقم 2   تحت شعار دربك أخضر حسم 10% بمناسبة المعرض*', NULL, '2012-12-24 21:24:55'),
(9, 'إفطار رمضاني', 'أقامت شركة ساعد للتأمين في دمشق مأدبة إفطار رمضانية لموظفيها في مطعم نادي الشرق يوم الثلاثاء 7-9-2010        \r\n', NULL, '2012-12-24 21:26:05'),
(11, 'تخفيض الرسم التاميني السنوي', 'يسر شركة ساعد للتأمين ان تبلغكم عن تخفيض رسم التأمين السنوي للفرد الواحد الى 20000 ليرة سورية على ان يلتزم الفرد بالشروط و القوانين السارية في الشركة و المتعارف عليها', NULL, '2012-12-24 21:29:27');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE IF NOT EXISTS `patient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `firstName` varchar(25) NOT NULL,
  `lastName` varchar(25) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `nationalNumber` varchar(25) NOT NULL,
  `email` varchar(25) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `expiryDate` datetime NOT NULL,
  `numOfVisits` int(11) DEFAULT NULL,
  `money` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nationalNumber` (`nationalNumber`),
  KEY `FK_Patient_Company` (`company_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `company_id`, `firstName`, `lastName`, `gender`, `nationalNumber`, `email`, `mobile`, `phone`, `address`, `expiryDate`, `numOfVisits`, `money`) VALUES
(14, 14, 'أحمد', 'الشامي', 'male', '1234', 'bilalo89@gmail.com', '093434w', '2320394231', 'برامكة', '2013-12-22 00:00:00', 12, 1560),
(15, 14, 'أحمد', 'مككي', 'male', '4321', 'bilalo89@gmail.com', NULL, NULL, NULL, '2013-12-22 00:00:00', 6, 50030),
(16, 14, 'تحسين', 'المنفلوطي', 'male', '0011', 'bilalo89@gmail.com', '0999111222', '1112223', 'ركن الدين', '2013-12-23 00:00:00', 4, 7616),
(17, 14, 'سامر', 'المنفلوطي', 'male', '1100', 'bilalo89@gmail.com', '0999111333', '1122334', 'مساكن برزة', '2013-12-23 00:00:00', 1, 1900);

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE IF NOT EXISTS `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `displayName` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `name`, `displayName`, `description`) VALUES
(1, 'manageUsers', 'إدارة المستخدمين', 'تشمل هذه الصلاحية :إضافة وحذف المستخدمين، بالإضافة الى تعديل معلومات المستخدمين و التحكم بصلاحياتهم'),
(2, 'managePatients', 'إدارة المرضى', 'تشمل هذه الصلاحية :إضافة وحذف المرضى، بالإضافة الى تعديل معلوماتهم'),
(3, 'manageRoles', 'إدارة الأدوار', 'تشمل هذه الصلاحية :إضافة وحذف الأدوار، بالإضافة الى التحكم بالصلاحيات التابعة لكل دور'),
(4, 'manageConfig', 'ادارة اعدادات النظام', 'تشمل هذه الصلاحية إدارة الاعدادات الأساسية الخاصة بالنظام'),
(5, 'browsePaymentsForPatient', 'استعراض الدفعات الخاصة بالمرضى', 'تشمل هذه الصلاحية : استعراض الدفعات الخاصة بالمرضى (المشتركين)'),
(7, 'manageCompanies', 'إدارة الشركات', 'تشمل هذه الصلاحية :إضافة وحذف الشركات، بالإضافة الى تعديل معلوماتهم'),
(8, 'manageNews', 'إدارة الاخبار', 'تشمل هذه الصلاحية :إضافة وحذف الأخبار، بالإضافة الى تعديل معلوماتهم'),
(9, 'browseStatistics', 'استعراض الاحصائيات', 'تشمل هذه الصلاحية استعراض الاحصائيات الخاصة بالشركة');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy`
--

CREATE TABLE IF NOT EXISTS `pharmacy` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pharmacy`
--

INSERT INTO `pharmacy` (`user_id`, `name`) VALUES
(86, 'صيدلية راما'),
(100, 'صديلية ندى ندور'),
(101, 'الصدلية المركزية');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `description`) VALUES
(1, 'مدير', 'كافة الصلاحيات'),
(2, 'موظف', 'ادارة المرضى (المشتركين)');

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE IF NOT EXISTS `role_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_id` (`permission_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=84 ;

--
-- Dumping data for table `role_permission`
--

INSERT INTO `role_permission` (`id`, `role_id`, `permission_id`, `date`) VALUES
(75, 2, 2, '2012-12-26 00:00:00'),
(76, 1, 1, '2012-12-26 00:00:00'),
(77, 1, 2, '2012-12-26 00:00:00'),
(78, 1, 3, '2012-12-26 00:00:00'),
(79, 1, 4, '2012-12-26 00:00:00'),
(80, 1, 5, '2012-12-26 00:00:00'),
(81, 1, 7, '2012-12-26 00:00:00'),
(82, 1, 8, '2012-12-26 00:00:00'),
(83, 1, 9, '2012-12-26 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(25) NOT NULL,
  `password` varchar(50) NOT NULL,
  `type` varchar(10) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=102 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `type`, `mobile`, `phone`, `address`, `longitude`, `latitude`, `notes`) VALUES
(1, 'bilalo89@gmail.com', '25d55ad283aa400af464c76d713c07ad', 'مستخدم', '0999640736', '2755255', 'ركن الدين', NULL, NULL, 'ملاحظة1'),
(86, 'rama@gmail.com', '25d55ad283aa400af464c76d713c07ad', 'صيدلية', NULL, NULL, NULL, NULL, NULL, NULL),
(87, 'bassam@gmail.com', '25d55ad283aa400af464c76d713c07ad', 'طبيب', '0988204923', NULL, NULL, 33.534527, 36.296366, NULL),
(93, 'abd@gmail.com', '25d55ad283aa400af464c76d713c07ad', 'مشفى', NULL, NULL, NULL, NULL, NULL, NULL),
(96, 'manar@gmail.com', '25d55ad283aa400af464c76d713c07ad', 'مشفى', '094567899', '2756789', 'ركن الدين-ابن العميد', 33.543648, 36.307451, NULL),
(97, 'shefaa@gmail.com', '25d55ad283aa400af464c76d713c07ad', 'مشفى', '0944345678', '266767898', 'الحياة', 33.526442, 36.305338, NULL),
(98, 'moasah@gmail.com', '25d55ad283aa400af464c76d713c07ad', 'مشفى', NULL, NULL, NULL, 33.514707, 36.263196, NULL),
(99, 'alshami@gmail.com', '25d55ad283aa400af464c76d713c07ad', 'مشفى', NULL, NULL, NULL, NULL, NULL, NULL),
(100, 'nada@gmail.com', '25d55ad283aa400af464c76d713c07ad', 'صيدلية', NULL, NULL, 'مساكن برزة', NULL, NULL, NULL),
(101, 'central@gmail.com', '25d55ad283aa400af464c76d713c07ad', 'صيدلية', NULL, NULL, NULL, 33.522435, 36.295811, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `user_id`, `role_id`, `date`) VALUES
(7, 87, 2, '2012-12-19 00:00:00'),
(13, 1, 1, '2012-12-21 00:00:00');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `FK_Doctor_DoctorSpecialization` FOREIGN KEY (`specialization_id`) REFERENCES `doctorspecialization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Doctor_ServiceProvider` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctorvisit`
--
ALTER TABLE `doctorvisit`
  ADD CONSTRAINT `FK_DoctorVisit_Doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_DoctorVisit_Patient` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hospital`
--
ALTER TABLE `hospital`
  ADD CONSTRAINT `FK_Hospital_ServiceProvider` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hospitalservice`
--
ALTER TABLE `hospitalservice`
  ADD CONSTRAINT `FK_HospitalService_DoctorVisit` FOREIGN KEY (`doctorVisit_id`) REFERENCES `doctorvisit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `medicine`
--
ALTER TABLE `medicine`
  ADD CONSTRAINT `FK_Medicine_DoctorVisit` FOREIGN KEY (`doctorVisit_id`) REFERENCES `doctorvisit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `FK_Patient_Company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pharmacy`
--
ALTER TABLE `pharmacy`
  ADD CONSTRAINT `FK_Pharmacy_ServiceProvider` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `FK_Role_Permission_Permission` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Role_Permission_Role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `FK_User_Role_Role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_User_Role_User` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

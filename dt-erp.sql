-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2022 at 06:09 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dt-erp`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE IF NOT EXISTS `announcements` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserId` int(11) DEFAULT NULL,
  `Title` text DEFAULT NULL,
  `Attachment` text DEFAULT NULL,
  `Body` text DEFAULT NULL,
  `Created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `announcements`
--

TRUNCATE TABLE `announcements`;
--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`Id`, `UserId`, `Title`, `Attachment`, `Body`, `Created`) VALUES
(1, 1, 'sering sering', NULL, 'lorem ispum dolor', '2022-05-24 11:28:13');

-- --------------------------------------------------------

--
-- Table structure for table `daily_attendance`
--

DROP TABLE IF EXISTS `daily_attendance`;
CREATE TABLE IF NOT EXISTS `daily_attendance` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserId` int(11) DEFAULT NULL,
  `AttendanceDate` date DEFAULT NULL,
  `AttendanceType` int(11) DEFAULT NULL,
  `AttendanceTime` time DEFAULT NULL,
  `WorkingType` text DEFAULT NULL,
  `Latitude` text DEFAULT NULL,
  `Longitude` text DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `daily_attendance`
--

TRUNCATE TABLE `daily_attendance`;
--
-- Dumping data for table `daily_attendance`
--

INSERT INTO `daily_attendance` (`Id`, `UserId`, `AttendanceDate`, `AttendanceType`, `AttendanceTime`, `WorkingType`, `Latitude`, `Longitude`) VALUES
(1, 1, '2022-05-24', 0, '08:00:00', 'wfh', NULL, NULL),
(2, 1, '2022-05-24', 1, '17:00:00', 'wfh', NULL, NULL),
(4, 1, '2022-06-06', 0, '08:00:00', 'wfh', '', ''),
(5, 1, '2022-06-06', 1, '17:00:00', 'wfh', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `daily_report`
--

DROP TABLE IF EXISTS `daily_report`;
CREATE TABLE IF NOT EXISTS `daily_report` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserId` int(11) DEFAULT NULL,
  `ApprovalUserId` int(11) DEFAULT NULL,
  `OccupationId` int(11) DEFAULT NULL,
  `ActivityNote` text DEFAULT NULL,
  `ActivityPhoto` text DEFAULT NULL,
  `ActivityDate` date DEFAULT NULL,
  `StartTime` time DEFAULT NULL,
  `EndTime` time DEFAULT NULL,
  `CostCenter` int(11) DEFAULT NULL,
  `ReportStatus` int(11) NOT NULL DEFAULT 0,
  `ActivityTitle` text DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `daily_report`
--

TRUNCATE TABLE `daily_report`;
--
-- Dumping data for table `daily_report`
--

INSERT INTO `daily_report` (`Id`, `UserId`, `ApprovalUserId`, `OccupationId`, `ActivityNote`, `ActivityPhoto`, `ActivityDate`, `StartTime`, `EndTime`, `CostCenter`, `ReportStatus`, `ActivityTitle`) VALUES
(1, 1, 2, 1, 'hello world', NULL, '2020-01-01', '00:00:00', '01:30:00', 5, 0, NULL),
(2, 1, 2, 1, 'hello world 99', NULL, '2022-03-10', '05:00:00', '07:30:00', 5, 1, 'test me');

-- --------------------------------------------------------

--
-- Table structure for table `daily_worship`
--

DROP TABLE IF EXISTS `daily_worship`;
CREATE TABLE IF NOT EXISTS `daily_worship` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserId` int(11) DEFAULT NULL,
  `WorshipCategory` text DEFAULT NULL,
  `WorshipType` text DEFAULT NULL,
  `WorshipTypeValue` text DEFAULT NULL,
  `Created` date DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `daily_worship`
--

TRUNCATE TABLE `daily_worship`;
--
-- Dumping data for table `daily_worship`
--

INSERT INTO `daily_worship` (`Id`, `UserId`, `WorshipCategory`, `WorshipType`, `WorshipTypeValue`, `Created`) VALUES
(1, 1, 'ilmu', 'menyimak_talim_kajian_keislaman', 'true', '2022-05-31'),
(2, 1, 'al_quran', 'juz', '2', '2022-05-31');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

DROP TABLE IF EXISTS `donations`;
CREATE TABLE IF NOT EXISTS `donations` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `DonationDate` date DEFAULT NULL,
  `DonatorId` int(11) DEFAULT NULL,
  `DonatorPhone` text DEFAULT NULL,
  `DonatorName` text DEFAULT NULL,
  `TrxId` text DEFAULT NULL,
  `ViaHimpun` text DEFAULT NULL,
  `TrxType` text DEFAULT NULL,
  `PIC` int(11) DEFAULT NULL,
  `MaterialGroup` text DEFAULT NULL,
  `MaterialNumber` int(11) DEFAULT NULL,
  `SubMaterialNumber` int(11) DEFAULT NULL,
  `Amount` double DEFAULT NULL,
  `Note` text DEFAULT NULL,
  `UserId` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `donations`
--

TRUNCATE TABLE `donations`;
--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`Id`, `DonationDate`, `DonatorId`, `DonatorPhone`, `DonatorName`, `TrxId`, `ViaHimpun`, `TrxType`, `PIC`, `MaterialGroup`, `MaterialNumber`, `SubMaterialNumber`, `Amount`, `Note`, `UserId`) VALUES
(1, '2022-05-01', 1, '08561234567', 'astomo', 'DT-Donasi-20220525103631', 'hello', '1', 1, '1', 1, 1, 1000, 'lorem ipsum', 1),
(2, '2022-06-09', 2, '345', 'abc', 'DT-Donasi-20220609112106', NULL, NULL, 3, 'matahari', 1, 2, 10000, 'abcd', 2),
(3, '2022-06-09', 1, '08561234567', 'astomo', 'DT-Donasi-20220609112543', NULL, NULL, 3, 'matahari', 1, 1, 34000, 'yes', 2);

-- --------------------------------------------------------

--
-- Table structure for table `donator`
--

DROP TABLE IF EXISTS `donator`;
CREATE TABLE IF NOT EXISTS `donator` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `DonatorName` text DEFAULT NULL,
  `NickName` text DEFAULT NULL,
  `Gender` text DEFAULT NULL,
  `BirthPlace` text DEFAULT NULL,
  `BirthDate` date DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `Phone` text DEFAULT NULL,
  `Email` text DEFAULT NULL,
  `KTP` text DEFAULT NULL,
  `OfficeReg` text DEFAULT NULL,
  `MaterialGroup` int(11) DEFAULT NULL,
  `PIC` int(11) DEFAULT NULL,
  `Religion` text DEFAULT NULL,
  `Marital` text DEFAULT NULL,
  `Income` double NOT NULL DEFAULT 0,
  `Degree` text DEFAULT NULL,
  `Tag` text DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `donator`
--

TRUNCATE TABLE `donator`;
--
-- Dumping data for table `donator`
--

INSERT INTO `donator` (`Id`, `DonatorName`, `NickName`, `Gender`, `BirthPlace`, `BirthDate`, `Address`, `Phone`, `Email`, `KTP`, `OfficeReg`, `MaterialGroup`, `PIC`, `Religion`, `Marital`, `Income`, `Degree`, `Tag`) VALUES
(1, 'astomo', 'tomo', 'm', 'yogya', '1982-08-11', NULL, '08561234567', 'abc@mail.com', '123456', '1', 1, 1, '1', 'none', 100000, '2', 'mata,hari,ini'),
(2, 'abc', 'abc', 'Ipsum', 'abc', '2022-06-10', 'abc', '345', 'altshueid@gmail.com', '1234', 'sit amet', 0, 0, 'Lorem', 'Ipsum', 0, 'sit amet', 'a,b,c');

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

DROP TABLE IF EXISTS `leaves`;
CREATE TABLE IF NOT EXISTS `leaves` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserId` int(11) DEFAULT NULL,
  `Reason` text DEFAULT NULL,
  `Start` date DEFAULT NULL,
  `Finish` date DEFAULT NULL,
  `Status` int(11) DEFAULT 0,
  `Created` date DEFAULT curdate(),
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `leaves`
--

TRUNCATE TABLE `leaves`;
--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`Id`, `UserId`, `Reason`, `Start`, `Finish`, `Status`, `Created`) VALUES
(1, 1, 'Istri tetangga lahiran', '2022-05-20', '2022-05-22', 1, '2022-05-27');

-- --------------------------------------------------------

--
-- Table structure for table `material_group`
--

DROP TABLE IF EXISTS `material_group`;
CREATE TABLE IF NOT EXISTS `material_group` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `GroupName` text DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `material_group`
--

TRUNCATE TABLE `material_group`;
--
-- Dumping data for table `material_group`
--

INSERT INTO `material_group` (`Id`, `GroupName`) VALUES
(1, 'matahari');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Parent` int(11) DEFAULT NULL,
  `ModuleName` text DEFAULT NULL,
  `Link` text DEFAULT NULL,
  `Status` int(11) DEFAULT NULL,
  `AppType` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `modules`
--

TRUNCATE TABLE `modules`;
--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`Id`, `Parent`, `ModuleName`, `Link`, `Status`, `AppType`) VALUES
(1, 0, 'Corporate', NULL, 1, 1),
(2, 1, 'Overview', 'overview', 1, 1),
(3, 0, 'Transaction', 'transaction', 1, 2),
(4, 0, 'Customer Master Data', 'custdata', 1, 2),
(5, 0, 'Program', 'prog', 1, 2),
(6, 8, 'Gaji Pokok', 'wage', 1, 2),
(7, 8, 'Tunjangan Jenis Pekerjaan', 'subsider', 1, 2),
(8, 0, 'Payroll', '', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `overtime`
--

DROP TABLE IF EXISTS `overtime`;
CREATE TABLE IF NOT EXISTS `overtime` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserId` int(11) DEFAULT NULL,
  `Reason` text DEFAULT NULL,
  `Clockify` text DEFAULT NULL,
  `Start` time DEFAULT NULL,
  `Finish` time DEFAULT NULL,
  `Status` int(11) DEFAULT 0,
  `Hours` double DEFAULT NULL,
  `Created` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `overtime`
--

TRUNCATE TABLE `overtime`;
-- --------------------------------------------------------

--
-- Table structure for table `permit`
--

DROP TABLE IF EXISTS `permit`;
CREATE TABLE IF NOT EXISTS `permit` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserId` int(11) DEFAULT NULL,
  `Start` date DEFAULT NULL,
  `Finish` date DEFAULT NULL,
  `DoctorNote` text DEFAULT NULL,
  `PermitType` int(11) DEFAULT NULL,
  `Reason` text DEFAULT NULL,
  `Status` int(11) DEFAULT 0,
  `Created` date DEFAULT curdate(),
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `permit`
--

TRUNCATE TABLE `permit`;
--
-- Dumping data for table `permit`
--

INSERT INTO `permit` (`Id`, `UserId`, `Start`, `Finish`, `DoctorNote`, `PermitType`, `Reason`, `Status`, `Created`) VALUES
(1, 1, '2022-06-09', '2022-05-22', NULL, 1, 'kawin lagi', 1, '2022-05-30');

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

DROP TABLE IF EXISTS `program`;
CREATE TABLE IF NOT EXISTS `program` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ProgramName` text DEFAULT NULL,
  `Pilar` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `program`
--

TRUNCATE TABLE `program`;
--
-- Dumping data for table `program`
--

INSERT INTO `program` (`Id`, `ProgramName`, `Pilar`) VALUES
(1, 'Mencari Data Single', 1),
(2, 'Mencari Anak Muda', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sub_material_group`
--

DROP TABLE IF EXISTS `sub_material_group`;
CREATE TABLE IF NOT EXISTS `sub_material_group` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `MaterialGroupId` int(11) DEFAULT NULL,
  `SubName` text DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `sub_material_group`
--

TRUNCATE TABLE `sub_material_group`;
--
-- Dumping data for table `sub_material_group`
--

INSERT INTO `sub_material_group` (`Id`, `MaterialGroupId`, `SubName`) VALUES
(1, 1, 'matahari juga'),
(2, 1, 'stores');

-- --------------------------------------------------------

--
-- Table structure for table `sub_program`
--

DROP TABLE IF EXISTS `sub_program`;
CREATE TABLE IF NOT EXISTS `sub_program` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ProgramId` int(11) DEFAULT NULL,
  `Pilar` int(11) DEFAULT NULL,
  `SubProgramName` text DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `sub_program`
--

TRUNCATE TABLE `sub_program`;
--
-- Dumping data for table `sub_program`
--

INSERT INTO `sub_program` (`Id`, `ProgramId`, `Pilar`, `SubProgramName`) VALUES
(1, 1, 1, 'hello world');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Email` text DEFAULT NULL,
  `Password` text DEFAULT NULL,
  `UserType` int(11) DEFAULT NULL,
  `RealName` text DEFAULT NULL,
  `Phone` text DEFAULT NULL,
  `Created` datetime NOT NULL DEFAULT current_timestamp(),
  `UserPhoto` text DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `users`
--

TRUNCATE TABLE `users`;
--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `Email`, `Password`, `UserType`, `RealName`, `Phone`, `Created`, `UserPhoto`) VALUES
(1, 'tes@email.com', '$2y$10$wKIDAqDnifQtVczM9fGDPOFcLs2roF4pza9il0cfe9U1BYX9Bxwhm', 1, 'HibiscusHT', '0812345678', '2022-05-20 09:57:51', NULL),
(2, 'tes2@email.com', '$2y$10$/jmHOWAmMq0rmffHL6JFu.pyPw.AOBJy3BJ2TaQ1sgtnAipx2c/uu', 1, 'Hibiscus', '0812345699', '2022-05-20 10:15:51', NULL),
(3, 'a@b.com', NULL, 3, 'Picollo', '1234', '2022-06-09 16:11:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
CREATE TABLE IF NOT EXISTS `user_role` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ParentUserId` int(11) DEFAULT NULL,
  `UserId` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `user_role`
--

TRUNCATE TABLE `user_role`;
--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`Id`, `ParentUserId`, `UserId`) VALUES
(1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

DROP TABLE IF EXISTS `user_type`;
CREATE TABLE IF NOT EXISTS `user_type` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserType` text DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `user_type`
--

TRUNCATE TABLE `user_type`;
--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`Id`, `UserType`) VALUES
(1, 'Non Fundraising'),
(2, 'Internal Divisi'),
(3, 'Freelance');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

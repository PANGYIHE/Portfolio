-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2024 at 01:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kiosksys`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `AdminID` varchar(255) NOT NULL,
  `AdminName` varchar(255) DEFAULT NULL,
  `AdminPassword` varchar(255) DEFAULT NULL,
  `AdminProfile` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`AdminID`, `AdminName`, `AdminPassword`, `AdminProfile`) VALUES
('A0001', 'CD21074', 'cdcd21074', 'profilePictures/CD21074_Passport Pic..jpg'),
('A0002', 'CA19117', 'caca19117', 'profilePictures/CA19117_CA19117.jpg'),
('A0003', 'CA20054', 'caca20054', 'profilePictures/CA20054_CA20054.png'),
('A0004', 'CA20069', 'caca20069', 'profilePictures/CA20069_CA20069.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `foodvendor`
--

CREATE TABLE `foodvendor` (
  `VendorID` varchar(255) NOT NULL,
  `AdminID` varchar(255) DEFAULT NULL,
  `VendorName` varchar(255) DEFAULT NULL,
  `VendorEmail` varchar(255) DEFAULT NULL,
  `VendorPassword` varchar(255) DEFAULT NULL,
  `VendorQRCode` varchar(255) DEFAULT NULL,
  `ApprovalStatus` varchar(255) DEFAULT NULL,
  `VendorProfile` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `foodvendor`
--

INSERT INTO `foodvendor` (`VendorID`, `AdminID`, `VendorName`, `VendorEmail`, `VendorPassword`, `VendorQRCode`, `ApprovalStatus`, `VendorProfile`) VALUES
('V0001', 'A0001', 'Mokhtar', 'Mokhtar@gmail.com', 'mokhtar1993', '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=Mokhtar&size=80x80\">', 'Approved', 'profilePictures/Mokhtar_mokhtar.jpg'),
('V0002', 'A0001', 'Amril', 'Amril@gmail.com', 'amril1993', '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=Amril&size=80x80\">', 'Rejected', 'profilePictures/Amril_Amril.jpg'),
('V0003', 'A0001', 'Adam', 'Adam@gmail.com', 'adam1995', '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=Adam&size=80x80\">', 'Approved', 'profilePictures/Adam_Adam.jpg'),
('V0004', 'A0001', 'Atika', 'Atika@gmail.com', 'atika1999', '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=Atika&size=80x80\">', 'Pending', 'profilePictures/Atika_Atika.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `instoreselling`
--

CREATE TABLE `instoreselling` (
  `SellingID` varchar(255) NOT NULL,
  `VendorID` varchar(255) NOT NULL,
  `SellingDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instoreselling`
--

INSERT INTO `instoreselling` (`SellingID`, `VendorID`, `SellingDate`) VALUES
('IS0001', 'V0001', '2023-12-20'),
('IS0002', 'V0002', '2023-12-20'),
('IS0003', 'V0003', '2023-12-20'),
('IS0004', 'V0003', '2023-12-20');

-- --------------------------------------------------------

--
-- Table structure for table `kiosk`
--

CREATE TABLE `kiosk` (
  `KioskID` varchar(255) NOT NULL,
  `KioskName` varchar(255) DEFAULT NULL,
  `KioskPhoneNo` varchar(255) DEFAULT NULL,
  `KioskOperateHour` varchar(255) DEFAULT NULL,
  `KioskStatus` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kiosk`
--

INSERT INTO `kiosk` (`KioskID`, `KioskName`, `KioskPhoneNo`, `KioskOperateHour`, `KioskStatus`) VALUES
('KK0001', 'Gerai Nasi Kak Anisa', '01121308473', '0800-1600', 'open'),
('KK0002', 'Brew Lab', '0136901301', '0700-1400', 'close'),
('KK0003', 'Gerai Sarap', '01119804411', '0900-1600', 'open'),
('KK0004', 'Foodie FKOM', '0149927601', '0800-1500', 'open');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `MenuID` varchar(255) NOT NULL,
  `VendorID` varchar(255) NOT NULL,
  `AdminID` varchar(255) NOT NULL,
  `MenuName` varchar(255) DEFAULT NULL,
  `MenuQuantity` int(255) DEFAULT NULL,
  `MenuPrice` float DEFAULT NULL,
  `MenuQRCode` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`MenuID`, `VendorID`, `AdminID`, `MenuName`, `MenuQuantity`, `MenuPrice`, `MenuQRCode`) VALUES
('M0001', 'V0001', 'A0001', 'Nasi Ayam Pedas', 50, 5, '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=M0001&size=80x80\">'),
('M0002', 'V0001', 'A0001', 'Nasi Minyak', 50, 5, '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=M0002&size=80x80\">'),
('M0003', 'V0001', 'A0001', 'Nasi Lemak Ayam', 20, 4.5, '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=M0003&size=80x80\">'),
('M0004', 'V0001', 'A0001', 'Nasi Kukus Ayam Goreng', 50, 5, '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=M0004&size=80x80\">');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `OrderID` varchar(255) NOT NULL,
  `VendorID` varchar(255) NOT NULL,
  `UserID` varchar(255) NOT NULL,
  `OrderDate` timestamp(6) NULL DEFAULT NULL,
  `OrderStatus` varchar(255) DEFAULT NULL,
  `OrderQRCode` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`OrderID`, `VendorID`, `UserID`, `OrderDate`, `OrderStatus`, `OrderQRCode`) VALUES
('O0001', 'V0001', 'U0001', '2023-12-24 03:40:46.000000', 'Pending', '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=O0001&size=80x80\">'),
('O0002', 'V0002', 'U0002', '2023-12-20 03:35:46.000000', 'Ordered', '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=O0002&size=80x80\">'),
('O0003', 'V0003', 'U0003', '2023-12-20 03:35:46.000000', 'Pickup', '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=O0003&size=80x80\">'),
('O0004', 'V0003', 'U0004', '2023-12-20 03:35:46.000000', 'Completed', '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=O0004&size=80x80\">');

-- --------------------------------------------------------

--
-- Table structure for table `orderline`
--

CREATE TABLE `orderline` (
  `OrderLineID` varchar(255) NOT NULL,
  `MenuID` varchar(255) NOT NULL,
  `OrderID` varchar(255) DEFAULT NULL,
  `SellingID` varchar(255) DEFAULT NULL,
  `OrderQuantity` int(255) DEFAULT NULL,
  `OrderDiscount` float DEFAULT NULL,
  `OrderTotalAmount` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderline`
--

INSERT INTO `orderline` (`OrderLineID`, `MenuID`, `OrderID`, `SellingID`, `OrderQuantity`, `OrderDiscount`, `OrderTotalAmount`) VALUES
('OL0001', 'M0001', 'O0001', NULL, 2, 0.2, 6),
('OL0002', 'M0002', 'O0002', NULL, 3, 0.5, 12),
('OL0003', 'M0003', 'O0003', NULL, 3, 0.95, 10),
('OL0004', 'M0004', NULL, 'IS0001', 1, 0.8, 3),
('OL0005', 'M0001', NULL, 'IS0002', 2, 0.5, 9),
('OL0006', 'M0002', NULL, 'IS0003', 3, 0.4, 6);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `PaymentID` varchar(255) NOT NULL,
  `UserPoints` varchar(255) DEFAULT NULL,
  `PaymentType` varchar(255) DEFAULT NULL,
  `PaymentDate` timestamp(6) NULL DEFAULT NULL,
  `PaymentStatus` varchar(255) DEFAULT NULL,
  `PaymentQRCode` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`PaymentID`, `UserPoints`, `PaymentType`, `PaymentDate`, `PaymentStatus`, `PaymentQRCode`) VALUES
('P0001', '60', 'Online', '2023-12-28 01:40:46.000000', 'Cancelled', '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=P0001&size=80x80\">'),
('P0002', '120', 'Online', '2023-12-20 03:35:46.000000', 'Successful', '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=P0002&size=80x80\">'),
('P0003', '100', 'In-store', '2023-12-20 03:35:46.000000', 'Successful', '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=P0003&size=80x80\">'),
('P0004', '30', 'In-store', '2023-12-20 03:35:46.000000', 'Refunded', '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=P0004&size=80x80\">');

-- --------------------------------------------------------

--
-- Table structure for table `registereduser`
--

CREATE TABLE `registereduser` (
  `UserID` varchar(255) NOT NULL,
  `UserName` varchar(255) DEFAULT NULL,
  `UserEmail` varchar(255) DEFAULT NULL,
  `UserPassword` varchar(255) DEFAULT NULL,
  `UserQRCode` varchar(255) DEFAULT NULL,
  `UserProfile` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registereduser`
--

INSERT INTO `registereduser` (`UserID`, `UserName`, `UserEmail`, `UserPassword`, `UserQRCode`, `UserProfile`) VALUES
('U0001', 'CB19066', 'CB19066@student.umpsa.edu.my', 'cbcb19066', '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=CB19066&size=80x80\">', 'profilePictures/CB19066_cb19066.jpg'),
('U0002', 'CA20067', 'CA20067@student.umpsa.edu.my', 'caca20067', '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=CA20067&size=80x80\">', 'profilePictures/CA20067_CA20067.jpg'),
('U0003', 'Dr. Rahmah', 'drrahmah@umpsa.edu.my', 'rahmah1983', '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=Dr. Rahmah&size=80x80\">', 'profilePictures/Dr. Rahmah_Dr. Rahmah.jpg'),
('U0004', 'Dr. Junaidah', 'drjunaidah@umpsa.edu.my', 'junaidah1988', '<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=Dr. Junaidah&size=80x80\">', 'profilePictures/Dr. Junaidah_Dr. Junaidah.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `foodvendor`
--
ALTER TABLE `foodvendor`
  ADD PRIMARY KEY (`VendorID`),
  ADD KEY `AdminID` (`AdminID`);

--
-- Indexes for table `instoreselling`
--
ALTER TABLE `instoreselling`
  ADD PRIMARY KEY (`SellingID`),
  ADD KEY `VendorID` (`VendorID`);

--
-- Indexes for table `kiosk`
--
ALTER TABLE `kiosk`
  ADD PRIMARY KEY (`KioskID`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`MenuID`),
  ADD KEY `AdminID` (`AdminID`),
  ADD KEY `VendorID` (`VendorID`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `VendorID` (`VendorID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `orderline`
--
ALTER TABLE `orderline`
  ADD PRIMARY KEY (`OrderLineID`),
  ADD KEY `MenuID` (`MenuID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `InStoreSellingID` (`SellingID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`PaymentID`);

--
-- Indexes for table `registereduser`
--
ALTER TABLE `registereduser`
  ADD PRIMARY KEY (`UserID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `foodvendor`
--
ALTER TABLE `foodvendor`
  ADD CONSTRAINT `foodvendor_ibfk_1` FOREIGN KEY (`AdminID`) REFERENCES `administrator` (`AdminID`);

--
-- Constraints for table `instoreselling`
--
ALTER TABLE `instoreselling`
  ADD CONSTRAINT `instoreselling_ibfk_2` FOREIGN KEY (`VendorID`) REFERENCES `foodvendor` (`VendorID`);

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`AdminID`) REFERENCES `administrator` (`AdminID`),
  ADD CONSTRAINT `menu_ibfk_2` FOREIGN KEY (`VendorID`) REFERENCES `foodvendor` (`VendorID`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`VendorID`) REFERENCES `foodvendor` (`VendorID`),
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `registereduser` (`UserID`);

--
-- Constraints for table `orderline`
--
ALTER TABLE `orderline`
  ADD CONSTRAINT `orderline_ibfk_1` FOREIGN KEY (`MenuID`) REFERENCES `menu` (`MenuID`),
  ADD CONSTRAINT `orderline_ibfk_2` FOREIGN KEY (`OrderID`) REFERENCES `order` (`OrderID`),
  ADD CONSTRAINT `orderline_ibfk_3` FOREIGN KEY (`SellingID`) REFERENCES `instoreselling` (`SellingID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

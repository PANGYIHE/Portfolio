-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2023 at 02:48 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `carsalesmanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `usernameA` varchar(50) NOT NULL,
  `idA` varchar(10) NOT NULL,
  `emailA` varchar(50) NOT NULL,
  `phoneA` varchar(10) NOT NULL,
  `passwordA` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `award`
--

CREATE TABLE `award` (
  `nameA` varchar(50) NOT NULL,
  `sales` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `carID` varchar(10) NOT NULL,
  `model` varchar(50) NOT NULL,
  `arriveDate` varchar(20) NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `usernameC` varchar(50) NOT NULL,
  `idC` varchar(20) NOT NULL,
  `emailC` varchar(50) NOT NULL,
  `phoneC` varchar(20) NOT NULL,
  `passwordC` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ordercar`
--

CREATE TABLE `ordercar` (
  `ID` int(11) NOT NULL,
  `carID` varchar(10) NOT NULL,
  `model` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double NOT NULL,
  `totalPrice` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ordersparepart`
--

CREATE TABLE `ordersparepart` (
  `ID` int(11) NOT NULL,
  `sparePartID` varchar(10) NOT NULL,
  `sparePartName` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double NOT NULL,
  `totalPrice` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paymentwithloan`
--

CREATE TABLE `paymentwithloan` (
  `paymentID` int(11) NOT NULL,
  `paymentMethod` varchar(50) NOT NULL,
  `paymentDate` varchar(20) NOT NULL,
  `paymentPrice` int(11) NOT NULL,
  `bankName` varchar(50) NOT NULL,
  `lenderName` varchar(20) NOT NULL,
  `lenderPhoneNumber` varchar(10) NOT NULL,
  `guarantorName` varchar(50) NOT NULL,
  `loanAmount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paymentwithoutloan`
--

CREATE TABLE `paymentwithoutloan` (
  `paymentID` int(10) NOT NULL,
  `paymentMethod` varchar(50) NOT NULL,
  `paymentDate` varchar(20) NOT NULL,
  `paymentPrice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sparepart`
--

CREATE TABLE `sparepart` (
  `sparePartID` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` double NOT NULL,
  `supplier` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`carID`);

--
-- Indexes for table `ordercar`
--
ALTER TABLE `ordercar`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ordersparepart`
--
ALTER TABLE `ordersparepart`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `paymentwithloan`
--
ALTER TABLE `paymentwithloan`
  ADD PRIMARY KEY (`paymentID`);

--
-- Indexes for table `paymentwithoutloan`
--
ALTER TABLE `paymentwithoutloan`
  ADD PRIMARY KEY (`paymentID`);

--
-- Indexes for table `sparepart`
--
ALTER TABLE `sparepart`
  ADD PRIMARY KEY (`sparePartID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ordercar`
--
ALTER TABLE `ordercar`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `ordersparepart`
--
ALTER TABLE `ordersparepart`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `paymentwithloan`
--
ALTER TABLE `paymentwithloan`
  MODIFY `paymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `paymentwithoutloan`
--
ALTER TABLE `paymentwithoutloan`
  MODIFY `paymentID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

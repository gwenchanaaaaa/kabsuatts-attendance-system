-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2025 at 03:57 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `attendancemsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `emailAddress` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`Id`, `firstName`, `lastName`, `emailAddress`, `password`) VALUES
(1, 'Admin', 'User', 'admin@gmail.com', '$2y$10$ZLwl9fTBUSZU/fd6iq15J.c9i8Bb19rVWG27xlR2.3zRZxOzB2fJm');

-- --------------------------------------------------------

--
-- Table structure for table `tblattendance`
--

CREATE TABLE `tblattendance` (
  `attendanceID` int(50) NOT NULL,
  `studentRegistrationNumber` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `attendanceStatus` varchar(100) NOT NULL,
  `dateMarked` date NOT NULL,
  `unit` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblattendance`
--

INSERT INTO `tblattendance` (`attendanceID`, `studentRegistrationNumber`, `course`, `attendanceStatus`, `dateMarked`, `unit`) VALUES
(478, 'CIT-222-003-2020', 'BCT', 'Absent', '2024-05-02', 'BCT 2411'),
(479, 'CIT-222-002-2020', 'BCT', 'Absent', '2024-05-02', 'BCT 2411'),
(480, 'CIT-222-001-2020', 'BCT', 'Absent', '2024-05-02', 'BCT 2411'),
(481, 'CIT-222-005-2020', 'BCT', 'present', '2024-05-02', 'BCT 2411'),
(482, 'CIT-222-004-2020', 'BCT', 'Absent', '2024-05-02', 'BCT 2411'),
(483, 'CIT-222-003-2020', 'BCT', 'Absent', '2025-05-31', 'BCT 2411'),
(484, 'CIT-222-002-2020', 'BCT', 'Absent', '2025-05-31', 'BCT 2411'),
(485, 'CIT-222-001-2020', 'BCT', 'Absent', '2025-05-31', 'BCT 2411'),
(486, 'CIT-222-005-2020', 'BCT', 'Absent', '2025-05-31', 'BCT 2411'),
(487, 'CIT-222-004-2020', 'BCT', 'Absent', '2025-05-31', 'BCT 2411'),
(488, 'CIT-222-003-2020', 'BCT', 'Absent', '2025-05-31', 'BCT 2411'),
(489, 'CIT-222-002-2020', 'BCT', 'Absent', '2025-05-31', 'BCT 2411'),
(490, 'CIT-222-001-2020', 'BCT', 'Absent', '2025-05-31', 'BCT 2411'),
(491, 'CIT-222-005-2020', 'BCT', 'Absent', '2025-05-31', 'BCT 2411'),
(492, 'CIT-222-004-2020', 'BCT', 'Absent', '2025-05-31', 'BCT 2411'),
(494, 'CIT-222-003-2020', 'BCT', 'Absent', '2025-05-31', 'BCT 2411'),
(495, 'CIT-222-002-2020', 'BCT', 'Absent', '2025-05-31', 'BCT 2411'),
(496, 'CIT-222-001-2020', 'BCT', 'Absent', '2025-05-31', 'BCT 2411'),
(497, 'CIT-222-005-2020', 'BCT', 'Absent', '2025-05-31', 'BCT 2411'),
(498, 'CIT-222-004-2020', 'BCT', 'Absent', '2025-05-31', 'BCT 2411'),
(499, 'CIT-222-003-2020', 'BCT', 'Absent', '2025-06-02', 'BCT 2411'),
(500, 'CIT-222-002-2020', 'BCT', 'Absent', '2025-06-02', 'BCT 2411'),
(501, 'CIT-222-001-2020', 'BCT', 'Absent', '2025-06-02', 'BCT 2411'),
(502, 'CIT-222-005-2020', 'BCT', 'Absent', '2025-06-02', 'BCT 2411'),
(503, 'CIT-222-004-2020', 'BCT', 'Absent', '2025-06-02', 'BCT 2411');

-- --------------------------------------------------------

--
-- Table structure for table `tblcourse`
--

CREATE TABLE `tblcourse` (
  `ID` int(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `facultyID` int(50) NOT NULL,
  `dateCreated` date NOT NULL,
  `courseCode` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcourse`
--

INSERT INTO `tblcourse` (`ID`, `name`, `facultyID`, `dateCreated`, `courseCode`) VALUES
(34, 'Bachelor of Science in Business Management', 19, '2025-06-06', 'BSBM'),
(35, 'Bachelor of Science in Office Administration', 19, '2025-06-06', 'BSOA'),
(36, 'Bachelor of Science in Entrepreneurship', 19, '2025-06-06', 'BSE'),
(37, 'Bachelor of Science in Information Technology', 22, '2025-06-06', 'BSIT'),
(38, 'Bachelor of Science in Computer Science', 22, '2025-06-06', 'BSCS'),
(39, 'Bachelor of Science in Hospitality Management', 19, '2025-06-06', 'BSHM'),
(40, 'Bachelor of Early Childhood Education', 20, '2025-06-06', 'BECE'),
(41, 'Bachelor of Elementary Education', 20, '2025-06-06', 'BEEd'),
(42, 'Bachelor of Secondary Education', 20, '2025-06-06', 'BSEd'),
(43, 'Teacher Certificate Program', 20, '2025-06-06', 'TCP'),
(44, 'Bachelor of Arts in Journalism', 21, '2025-06-06', 'AB Journalism'),
(46, 'Bachelor of Arts in Political Science', 21, '2025-06-06', 'BAPS'),
(47, 'Bachelor of Science in Psychology', 21, '2025-06-06', 'BSPsy');

-- --------------------------------------------------------

--
-- Table structure for table `tblfaculty`
--

CREATE TABLE `tblfaculty` (
  `Id` int(10) NOT NULL,
  `facultyName` varchar(255) NOT NULL,
  `facultyCode` varchar(50) NOT NULL,
  `dateRegistered` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblfaculty`
--

INSERT INTO `tblfaculty` (`Id`, `facultyName`, `facultyCode`, `dateRegistered`) VALUES
(19, 'College of Business and Entrepreneurship', 'CBE', '2025-06-06'),
(20, 'College of Education', 'COE', '2025-06-06'),
(21, 'College of Arts and Sciences', 'CAS', '2025-06-06'),
(22, 'Computer and Information Technology', 'CIT', '2025-06-06');

-- --------------------------------------------------------

--
-- Table structure for table `tbllecture`
--

CREATE TABLE `tbllecture` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phoneNo` varchar(50) NOT NULL,
  `facultyCode` varchar(50) NOT NULL,
  `dateCreated` varchar(50) NOT NULL,
  `passwordresetrequired` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbllecture`
--

INSERT INTO `tbllecture` (`Id`, `firstName`, `lastName`, `emailAddress`, `password`, `phoneNo`, `facultyCode`, `dateCreated`, `passwordresetrequired`) VALUES
(24, 'Czyrell', 'Abelita', 'czyrellabelita@gmail.com', '$2y$10$ZagY7l9C0rvnn88WGsNw5Owk5faQfe7GbeudBkdxZCW5DRi2uDGCm', '9519405122', 'CIT', '2025-05-31', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblstudents`
--

CREATE TABLE `tblstudents` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `registrationNumber` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `faculty` varchar(10) NOT NULL,
  `courseCode` varchar(20) NOT NULL,
  `studentImage1` varchar(300) NOT NULL,
  `studentImage2` varchar(300) NOT NULL,
  `dateRegistered` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstudents`
--

INSERT INTO `tblstudents` (`Id`, `firstName`, `lastName`, `registrationNumber`, `email`, `faculty`, `courseCode`, `studentImage1`, `studentImage2`, `dateRegistered`) VALUES
(3, 'John', 'Macharia', 'CIT-222-003-2020', 'john@gmail.com', 'CIT', 'BCT', 'CIT-222-003-2020_image1.png', 'CIT-222-003-2020_image2.png', '2024-04-09');

-- --------------------------------------------------------

--
-- Table structure for table `tblunit`
--

CREATE TABLE `tblunit` (
  `ID` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `unitCode` varchar(50) NOT NULL,
  `courseID` varchar(50) NOT NULL,
  `dateCreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblunit`
--

INSERT INTO `tblunit` (`ID`, `name`, `unitCode`, `courseID`, `dateCreated`) VALUES
(9, 'Introduction to Human Computer Interaction', 'ITEC 80A', '37', '2025-06-06'),
(10, ' Human Computer Interaction 2', 'ITEC 101A', '37', '2025-06-06'),
(11, 'Financial Management', 'FM 101A', '34', '2025-06-06'),
(12, 'Costing and Pricing', 'COP102B', '34', '2025-06-06'),
(13, 'Office Procedures and Systems', 'OPS 201C', '35', '2025-06-06'),
(14, 'Business Communication', 'BCOM 104A', '35', '2025-06-06'),
(15, 'Entrepreneurial Management', 'EMG 105C', '36', '2025-06-06'),
(16, 'Small Business Operations', 'SBO 101A', '36', '2025-06-06'),
(17, 'System Analysis and Design', 'SAAD', '38', '2025-06-06'),
(18, 'Network Fundamentals', 'INSY 55', '38', '2025-06-06'),
(19, 'Food And Beverage', 'FAB 51', '39', '2025-06-06'),
(20, 'Cookery', 'COR 102', '39', '2025-06-06'),
(21, 'Social Studies in Early Childhood Education', 'SSCE 121', '40', '2025-06-06'),
(22, 'Technology for Teaching and Learning 2', 'TTL 41', '40', '2025-06-06'),
(23, 'Curriculum Development', 'CDEV 11', '41', '2025-06-06'),
(24, 'Educational Assessment', 'EAS 101', '41', '2025-06-06'),
(25, 'Teaching Methods in Secondary Education', 'TMSE 101C', '42', '2025-06-06'),
(26, 'Classroom Management', 'CM 101B', '42', '2025-06-06'),
(27, 'Foundations of Education', 'FOE 104', '43', '2025-06-06'),
(28, 'Instructional Planning and Assessment', 'IPA 102F', '43', '2025-06-06'),
(29, 'News Writing and Reporting', 'NWR 102', '44', '2025-06-06'),
(30, 'Broadcast Journalism', 'BOJ 101C', '44', '2025-06-06'),
(31, 'Political Theories and Ideologies', 'PTI 203F', '46', '2025-06-06'),
(32, 'International Relations', 'IRE 106F', '46', '2025-06-06'),
(33, 'Abnormal Psychology', 'ABP 201B', '47', '2025-06-06'),
(34, 'Psychological Assessment', 'PAS 204A', '47', '2025-06-06');

-- --------------------------------------------------------

--
-- Table structure for table `tblvenue`
--

CREATE TABLE `tblvenue` (
  `ID` int(10) NOT NULL,
  `className` varchar(50) NOT NULL,
  `subjectName` varchar(255) DEFAULT NULL,
  `facultyCode` varchar(50) NOT NULL,
  `currentStatus` varchar(50) NOT NULL,
  `capacity` int(10) NOT NULL,
  `classification` varchar(50) NOT NULL,
  `dateCreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblvenue`
--

INSERT INTO `tblvenue` (`ID`, `className`, `subjectName`, `facultyCode`, `currentStatus`, `capacity`, `classification`, `dateCreated`) VALUES
(9, 'CL1', 'Introduction to Human Computer Interaction', 'CIT', 'scheduled', 50, 'laboratory', '2025-06-06'),
(12, 'CL4', NULL, 'CIT', 'available', 50, 'class', '2025-06-06'),
(13, 'CL5', NULL, 'CIT', 'available', 50, 'class', '2025-06-06'),
(14, 'CL6', NULL, 'CIT', 'available', 50, 'class', '2025-06-06'),
(15, 'CL7', NULL, 'CIT', 'available', 50, 'class', '2025-06-06'),
(16, 'CL8', NULL, 'CIT', 'available', 50, 'class', '2025-06-06'),
(17, '112', NULL, 'CIT', 'available', 50, 'class', '2025-06-06'),
(18, '113', NULL, 'CIT', 'available', 50, 'class', '2025-06-06'),
(19, '201', NULL, 'CIT', 'available', 50, 'class', '2025-06-06'),
(20, '202', NULL, 'CIT', 'available', 50, 'class', '2025-06-06'),
(21, '301', NULL, 'CIT', 'available', 50, 'class', '2025-06-06'),
(22, '302', NULL, 'CIT', 'available', 50, 'class', '2025-06-06'),
(23, '303', NULL, 'CIT', 'available', 50, 'class', '2025-06-06'),
(24, 'CL2', 'Financial Management', 'CBE', 'available', 12, 'laboratory', '2025-06-06'),
(25, 'CL3', 'Office Procedures and Systems', 'CBE', 'available', 21, 'lectureHall', '2025-06-06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD PRIMARY KEY (`attendanceID`);

--
-- Indexes for table `tblcourse`
--
ALTER TABLE `tblcourse`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblfaculty`
--
ALTER TABLE `tblfaculty`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tbllecture`
--
ALTER TABLE `tbllecture`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblunit`
--
ALTER TABLE `tblunit`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblvenue`
--
ALTER TABLE `tblvenue`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblattendance`
--
ALTER TABLE `tblattendance`
  MODIFY `attendanceID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=504;

--
-- AUTO_INCREMENT for table `tblcourse`
--
ALTER TABLE `tblcourse`
  MODIFY `ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `tblfaculty`
--
ALTER TABLE `tblfaculty`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tbllecture`
--
ALTER TABLE `tbllecture`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tblstudents`
--
ALTER TABLE `tblstudents`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `tblunit`
--
ALTER TABLE `tblunit`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `tblvenue`
--
ALTER TABLE `tblvenue`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

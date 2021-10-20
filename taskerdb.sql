-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2020 at 11:24 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `taskerdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorytable`
--

CREATE TABLE `categorytable` (
  `image` mediumtext NOT NULL,
  `name` varchar(255) NOT NULL,
  `numberOfServices` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categorytable`
--

INSERT INTO `categorytable` (`image`, `name`, `numberOfServices`) VALUES
('images/wallpaint.png', 'Artistry', 3),
('images/carpenter.png', 'Carpentry', 3),
('images/pizza.png', 'Food', 4),
('images/teacher.png', 'Education', 4),
('images/photographer.jpg', 'Photography', 4);

-- --------------------------------------------------------

--
-- Table structure for table `reviewtable`
--

CREATE TABLE `reviewtable` (
  `contents` mediumtext NOT NULL,
  `date` date NOT NULL,
  `id` int(11) NOT NULL,
  `rating` decimal(1,1) NOT NULL,
  `serviceId` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `servicetable`
--

CREATE TABLE `servicetable` (
  `ageGroup0` int(7) NOT NULL DEFAULT '0',
  `ageGroup1` int(7) NOT NULL DEFAULT '0',
  `ageGroup2` int(7) NOT NULL DEFAULT '0',
  `ageGroup3` int(7) NOT NULL DEFAULT '0',
  `ageGroup4` int(7) NOT NULL DEFAULT '0',
  `ageGroup5` int(7) NOT NULL DEFAULT '0',
  `category` varchar(255) NOT NULL,
  `country` tinytext NOT NULL,
  `description` mediumtext,
  `email` tinytext NOT NULL,
  `id` int(11) NOT NULL,
  `images` mediumtext NOT NULL,
  `isPremium` tinyint(1) NOT NULL,
  `latitude` decimal(4,4) NOT NULL DEFAULT '0.0000',
  `location` mediumtext NOT NULL,
  `longitude` decimal(4,4) NOT NULL DEFAULT '0.0000',
  `mobile` varchar(15) DEFAULT NULL,
  `popularity` decimal(9,2) NOT NULL DEFAULT '0.00',
  `price` decimal(13,2) NOT NULL,
  `rating` decimal(2,1) NOT NULL DEFAULT '0.0',
  `telephone` varchar(15) DEFAULT NULL,
  `title` tinytext NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `servicetable`
--

INSERT INTO `servicetable` (`ageGroup0`, `ageGroup1`, `ageGroup2`, `ageGroup3`, `ageGroup4`, `ageGroup5`, `category`, `country`, `description`, `email`, `id`, `images`, `isPremium`, `latitude`, `location`, `longitude`, `mobile`, `popularity`, `price`, `rating`, `telephone`, `title`, `userId`) VALUES
(3, 0, 0, 0, 0, 0, 'Photography', 'Malta', 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum.', 'cik@email.com', 103, 'images/cake_decoration.jpg', 1, '0.0000', 'N.A', '0.0000', '356-9999-9999', '0.16', '9.99', '4.0', '356-9999-9999', 'Wedding Photography', 51),
(3, 0, 0, 0, 0, 0, 'Food', 'Malta', 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum.', 'cik@email.com', 104, 'images/cake_decoration.jpg', 1, '0.0000', 'N.A', '0.0000', '356-9999-9999', '0.04', '14.99', '4.5', '356-9999-9999', 'Cake Decoration', 51),
(1, 0, 0, 0, 0, 0, 'Artistry', 'Malta', 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum.', 'cik@email.com', 105, 'images/wallpaint.png', 1, '0.0000', 'N.A', '0.0000', '356-9999-9999', '0.01', '14.99', '5.0', '356-9999-9999', 'Wall Painting', 51),
(15, 0, 0, 0, 0, 0, 'Carpentry', 'Malta', 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum.', 'johnson@email.com', 111, 'images/th.jpg', 1, '0.0000', 'N.A', '0.0000', '356-9999-9999', '0.80', '40.00', '3.5', '356-9999-9999', 'Furniture Repair', 53),
(3, 0, 0, 0, 0, 0, 'Education', 'Malta', 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum.', 'johnson@email.com', 110, 'images/violin.jpg', 1, '0.0000', 'N.A', '0.0000', '356-9999-9999', '0.13', '9.99', '4.0', '356-9999-9999', 'Violin Lessons', 53),
(5, 0, 0, 0, 0, 0, 'Carpentry', 'Malta', 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum.', 'briffa@email.com', 113, 'images/carpenter.png', 0, '0.0000', 'N.A', '0.0000', '356-9999-9999', '0.25', '39.99', '4.0', '356-9999-9999', 'Home Repairs', 54),
(0, 0, 0, 0, 0, 0, 'Food', 'Malta', 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum.', 'briffa@email.com', 114, 'images/pizza_boxes.jpg', 0, '0.0000', 'N.A', '0.0000', '356-9999-9999', '0.00', '9.99', '3.0', '356-9999-9999', 'Food Delivery', 54),
(23, 0, 0, 0, 0, 0, 'Photography', 'Malta', 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum.', 'twos@email.com', 100, 'images/dog.jpg', 0, '0.0000', 'N.A', '0.0000', '356-9999-9999', '0.93', '20.00', '3.5', '356-9999-9999', 'Pet Photography', 50),
(8, 3, 0, 0, 0, 0, 'Artistry', 'Malta', 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum.', 'twos@email.com', 101, 'images/violin.jpg', 1, '0.0000', 'N.A', '0.0000', '356-9999-9999', '0.36', '10.00', '3.5', '356-9999-9999', 'Violin Performance', 50),
(8, 2, 0, 0, 0, 0, 'Education', 'Malta', 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum.', 'twos@email.com', 102, 'images/pizza.png', 1, '0.0000', 'N.A', '0.0000', '356-9999-9999', '0.30', '10.00', '4.0', '356-9999-9999', 'Cooking Lessons', 50),
(11, 9, 0, 0, 0, 0, 'Carpentry', 'Malta', 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum.', 'steve@email.com', 107, 'images/th.jpg', 0, '0.0000', 'N.A', '0.0000', '356-9999-9999', '0.80', '30.00', '5.0', '356-9999-9999', 'Furniture Repair', 52),
(1, 2, 0, 0, 0, 0, 'Food', 'Malta', 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum.', 'johnson@email.com', 109, 'images/pizza.png', 1, '0.0000', 'N.A', '0.0000', '356-9999-9999', '0.08', '10.00', '4.5', '356-9999-9999', 'Homemade Pizza', 53),
(19, 0, 0, 0, 0, 0, 'Photography', 'Malta', 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum.', 'steve@email.com', 108, 'images/photographer.jpg', 1, '0.0000', 'N.A', '0.0000', '356-9999-9999', '0.99', '12.00', '4.5', '356-9999-9999', 'Photography Services', 52),
(0, 2, 0, 0, 0, 0, 'Education', 'Malta', 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum.', 'briffa@email.com', 112, 'images/teacher.png', 1, '0.0000', 'N.A', '0.0000', '356-9999-9999', '0.23', '10.00', '4.5', '356-9999-9999', 'Mathematics Lessons', 54),
(13, 0, 0, 0, 0, 0, 'Artistry', 'Malta', 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum.', 'steve@email.com', 106, 'images/sculpture.jpg', 1, '0.0000', 'N.A', '0.0000', '356-9999-9999', '0.38', '10.00', '4.0', '356-9999-9999', 'Wood Sculpting', 52);

-- --------------------------------------------------------

--
-- Table structure for table `usertable`
--

CREATE TABLE `usertable` (
  `address` mediumtext,
  `bio` text,
  `country` tinytext NOT NULL,
  `dateOfBirth` date NOT NULL,
  `description` mediumtext,
  `email` tinytext NOT NULL,
  `firstName` tinytext NOT NULL,
  `id` int(11) NOT NULL,
  `joinDate` date NOT NULL,
  `lastName` tinytext NOT NULL,
  `likes` longtext,
  `mobile` varchar(15) DEFAULT NULL,
  `password` longtext NOT NULL,
  `picture` tinytext,
  `telephone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `usertable`
--

INSERT INTO `usertable` (`address`, `bio`, `country`, `dateOfBirth`, `description`, `email`, `firstName`, `id`, `joinDate`, `lastName`, `likes`, `mobile`, `password`, `picture`, `telephone`) VALUES
('N.A', NULL, 'Malta', '1995-03-03', NULL, 'cik@email.com', 'Chica', 51, '2020-04-26', 'Baxter', '{\"Carpentry\":0}', NULL, '$2y$10$W0Bvq3CL9QcjRxMFQlDteONVkh5cdx43wxHOJvpsE7zf56pgjaSdW', NULL, NULL),
('N.A', NULL, 'Malta', '1990-03-03', NULL, 'steve@email.com', 'Steve', 52, '2020-04-26', 'Morgan', '{\"Carpentry\":0}', NULL, '$2y$10$XxPwsEhZX4mms86N8hlxaO66Cy/xlYnDREy/vMMo4zvamX1woIoBS', NULL, NULL),
('N.A', NULL, 'Malta', '1995-01-01', NULL, 'johnson@email.com', 'Cal', 53, '2020-04-26', 'Johnson', '{\"Carpentry\":0}', NULL, '$2y$10$MBrElE.JOyw16pPwCVik9uV1iGjOFxOY3n2Uz6cC6TDQs0y0EBevm', NULL, NULL),
('N.A', NULL, 'Malta', '1993-03-03', NULL, 'briffa@email.com', 'Alexia', 54, '2020-06-07', 'Briffa', '{\"Carpentry\":81,\"Artistry\":22,\"Education\":11,\"Food\":11}', NULL, '$2y$10$36KvwIjjWeR8t5sLPECtHe/c/6IoA3VTZOyFufQ7aQw7MvL6bDfau', NULL, NULL),
('N.A', NULL, 'Malta', '2000-02-02', NULL, 'twos@email.com', 'Chris', 50, '2020-06-27', 'Robinson', '{\"Carpentry\":0}', NULL, '$2y$10$FPKtRzGxzs0h9trLT95oZOvDjdSAtTi1EMT2LkI92/DqhZp2QJCgG', NULL, NULL),
('N.A', NULL, 'Malta', '2002-06-07', NULL, 'Johny@email.com', 'John', 55, '2020-06-30', 'Doe', '{\"Carpentry\":11,\"Artistry\":24,\"Education\":0,\"Food\":0,\"Photography\":229}', NULL, '$2y$10$BYpH4MJn3y6eWJy7V7dJyuC6fLmhWQVgqUgWe2jqE.5K2u/Pm.ngG', NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

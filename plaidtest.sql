-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2019 at 06:57 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `plaidtest`
--

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `card_id` int(11) NOT NULL,
  `account_id` varchar(100) DEFAULT NULL,
  `balance` float DEFAULT NULL,
  `available` float DEFAULT NULL,
  `current` float DEFAULT NULL,
  `limits` float DEFAULT NULL,
  `last_updated` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `bank` varchar(30) NOT NULL,
  `nickname` varchar(60) DEFAULT NULL,
  `official_name` varchar(150) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`card_id`, `account_id`, `balance`, `available`, `current`, `limits`, `last_updated`, `user_id`, `bank`, `nickname`, `official_name`, `name`) VALUES
(1, 'RbW43QrAxgsNnRWpVXK3FX3P8XdK7gCRqnLVR', NULL, NULL, NULL, NULL, '2019-10-23 00:00:00', 1, 'Wells Fargo', NULL, 'Plaid Diamond 12.5% APR Interest Credit Card', 'Plaid Credit Card');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category` varchar(200) DEFAULT NULL,
  `date_posted` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_transacted` date DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `transaction_type` varchar(25) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `transaction_id2` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `card_id`, `user_id`, `category`, `date_posted`, `date_transacted`, `description`, `transaction_type`, `amount`, `transaction_id2`) VALUES
(1, 1, 1, '["Food and Drink","Restaurants"]', '2019-10-23 00:12:42', '2019-07-30', 'Tectra Inc', 'place', 500, 'mEQBygqw8Vha1ymZLWodHdzkKDq1oNfLXAykQ'),
(2, 1, 1, '["Payment"]', '2019-10-23 00:12:42', '2019-07-29', 'AUTOMATIC PAYMENT - THANK', 'special', 2078.5, 'b7B9E61omVFAdymZoqEnSqdX9ZL45KHV73bP7'),
(3, 1, 1, '["Food and Drink","Restaurants"]', '2019-10-23 00:12:42', '2019-07-29', 'KFC', 'place', 500, 'nWZ3EMK98Vu4LdNX1pyghakl7j8o6ES6QGlDb'),
(4, 1, 1, '["Shops","Sporting Goods"]', '2019-10-23 00:12:42', '2019-07-29', 'Madison Bicycle Shop', 'place', 500, 'G1eEZyp6NPslnJyz6A9ZINRoyazwAPu1onMwP'),
(5, 1, 1, '["Recreation","Gyms and Fitness Centers"]', '2019-10-23 00:12:42', '2019-07-18', 'Touchstone Climbing', 'place', 78.5, 'AjavNxeWB3SJxWL5wBr6i19eawRZW7f15orME'),
(6, 1, 1, '["Travel","Airlines and Aviation Services"]', '2019-10-23 00:12:42', '2019-07-05', 'United Airlines', 'special', 500, 'WrmeNLdAPGhQZ4l8zpxRHpm7wA4bG3clj4Zx7'),
(7, 1, 1, '["Food and Drink","Restaurants"]', '2019-10-23 00:12:42', '2019-06-30', 'Tectra Inc', 'place', 500, 'EZjgew1Wk4IABed7g6xySK71gBbPWrUXmExz1'),
(8, 1, 1, '["Payment"]', '2019-10-23 00:12:42', '2019-06-29', 'AUTOMATIC PAYMENT - THANK', 'special', 2078.5, '8zl8KD3P9GI976jLVDJ4hRZ9KJxynAiw8r35q'),
(9, 1, 1, '["Food and Drink","Restaurants"]', '2019-10-23 00:12:42', '2019-06-29', 'KFC', 'place', 500, 'g14L9P86mVsAjLlyqonvSljvZoqBnPFg5M89z'),
(10, 1, 1, '["Shops","Sporting Goods"]', '2019-10-23 00:12:42', '2019-06-29', 'Madison Bicycle Shop', 'place', 500, 'XkyDPgaRjnIZape8QoANfnMQAgjXZ8idLxKJe'),
(11, 1, 1, '["Recreation","Gyms and Fitness Centers"]', '2019-10-23 00:12:42', '2019-06-18', 'Touchstone Climbing', 'place', 78.5, '6KR1lez49Wu6MdVlJgbLUpXnz7MRgWcgexDzw'),
(12, 1, 1, '["Travel","Airlines and Aviation Services"]', '2019-10-23 00:12:42', '2019-06-05', 'United Airlines', 'special', 500, 'RbW43QrAxgsNnRWpVXK3FX3dkjaqRNiRAGpZe'),
(13, 1, 1, '["Food and Drink","Restaurants"]', '2019-10-23 00:12:42', '2019-05-31', 'Tectra Inc', 'place', 500, 'vKE7XM4J8VueGVaylW1NH6jE4MAN7xiW1X6w9'),
(14, 1, 1, '["Payment"]', '2019-10-23 00:12:42', '2019-05-30', 'AUTOMATIC PAYMENT - THANK', 'special', 2078.5, '9Bqgrdxj9nSgxM6e8K7VUWRxPy1mnLSRgExK7'),
(15, 1, 1, '["Food and Drink","Restaurants"]', '2019-10-23 00:12:42', '2019-05-30', 'KFC', 'place', 500, 'yBGZqMjR8lSnbQV76KEPcyvGpznx7dtywD6gL'),
(16, 1, 1, '["Shops","Sporting Goods"]', '2019-10-23 00:12:42', '2019-05-30', 'Madison Bicycle Shop', 'place', 500, 'mEQBygqw8Vha1ymZLWodHdzkKDq1oNfLXAyr3'),
(17, 1, 1, '["Recreation","Gyms and Fitness Centers"]', '2019-10-23 00:12:42', '2019-05-19', 'Touchstone Climbing', 'place', 78.5, 'b7B9E61omVFAdymZoqEnSqdX9ZL45KHV73bBr'),
(18, 1, 1, '["Travel","Airlines and Aviation Services"]', '2019-10-23 00:12:42', '2019-05-06', 'United Airlines', 'special', 500, 'nWZ3EMK98Vu4LdNX1pyghakl7j8o6ES6QGlJD'),
(19, 1, 1, '["Food and Drink","Restaurants"]', '2019-10-23 00:12:42', '2019-05-01', 'Tectra Inc', 'place', 500, 'G1eEZyp6NPslnJyz6A9ZINRoyazwAPu1onMZZ'),
(20, 1, 1, '["Payment"]', '2019-10-23 00:12:42', '2019-04-30', 'AUTOMATIC PAYMENT - THANK', 'special', 2078.5, 'AjavNxeWB3SJxWL5wBr6i19eawRZW7f15orBa'),
(21, 1, 1, '["Food and Drink","Restaurants"]', '2019-10-23 00:12:42', '2019-04-30', 'KFC', 'place', 500, 'WrmeNLdAPGhQZ4l8zpxRHpm7wA4bG3clj4ZBv'),
(22, 1, 1, '["Shops","Sporting Goods"]', '2019-10-23 00:12:42', '2019-04-30', 'Madison Bicycle Shop', 'place', 500, 'EZjgew1Wk4IABed7g6xySK71gBbPWrUXmEx6E'),
(23, 1, 1, '["Recreation","Gyms and Fitness Centers"]', '2019-10-23 00:12:42', '2019-04-19', 'Touchstone Climbing', 'place', 78.5, '8zl8KD3P9GI976jLVDJ4hRZ9KJxynAiw8r3NK'),
(24, 1, 1, '["Travel","Airlines and Aviation Services"]', '2019-10-23 00:12:42', '2019-04-06', 'United Airlines', 'special', 500, 'g14L9P86mVsAjLlyqonvSljvZoqBnPFg5M8yL'),
(25, 1, 1, '["Food and Drink","Restaurants"]', '2019-10-23 00:12:42', '2019-04-01', 'Tectra Inc', 'place', 500, 'oAMkEJrowVFmpvd95NElU4v8Bdoy65tRbKqvo'),
(26, 1, 1, '["Payment"]', '2019-10-23 00:12:42', '2019-03-31', 'AUTOMATIC PAYMENT - THANK', 'special', 2078.5, 'pG1dwPzmWVHAoyngJ5VqSQvlKZAnV6uLzKoXL'),
(27, 1, 1, '["Food and Drink","Restaurants"]', '2019-10-23 00:12:42', '2019-03-31', 'KFC', 'place', 500, 'L8Lzl4WwqZsBxW3Xg7nliD3GN84xK1iPE9j3L'),
(28, 1, 1, '["Shops","Sporting Goods"]', '2019-10-23 00:12:42', '2019-03-31', 'Madison Bicycle Shop', 'place', 500, '1nbDZ4GB9phL5yK6gxepHDlQpjMwWxi5rmjDb'),
(29, 1, 1, '["Recreation","Gyms and Fitness Centers"]', '2019-10-23 00:12:42', '2019-03-20', 'Touchstone Climbing', 'place', 78.5, 'MkpBqjAVKMI3VvyzJ8Wqs6b1qxkEpzi9176mA'),
(30, 1, 1, '["Travel","Airlines and Aviation Services"]', '2019-10-23 00:12:42', '2019-03-07', 'United Airlines', 'special', 500, 'Z3vG78NAmVCvlD5pyB4mHNlBKLoZzaugGbqxv'),
(31, 1, 1, '["Food and Drink","Restaurants"]', '2019-10-23 00:12:42', '2019-03-02', 'Tectra Inc', 'place', 500, 'QnEaBXKA3ohvB41rQVqoHKE5PozyxVUpDamjV'),
(32, 1, 1, '["Payment"]', '2019-10-23 00:12:42', '2019-03-01', 'AUTOMATIC PAYMENT - THANK', 'special', 2078.5, 'eZd8EPoDmVIJNRxl3E5ziEN4K3Lo5mTLmV9xQ'),
(33, 1, 1, '["Food and Drink","Restaurants"]', '2019-10-23 00:12:42', '2019-03-01', 'KFC', 'place', 500, '7r6WqaBN9AhgKLaoQJXVUo5J7r1GBWtgx1JwQ'),
(34, 1, 1, '["Shops","Sporting Goods"]', '2019-10-23 00:12:42', '2019-03-01', 'Madison Bicycle Shop', 'place', 500, 'jojKEJA68VieQaj1MWyLHBqVwE5kA9f1aGWQM'),
(35, 1, 1, '["Recreation","Gyms and Fitness Centers"]', '2019-10-23 00:12:42', '2019-02-18', 'Touchstone Climbing', 'place', 78.5, 'PG8dzQqABpHa6KBpERlzHqG9NdyxpZH7AN6Ke'),
(36, 1, 1, '["Travel","Airlines and Aviation Services"]', '2019-10-23 00:12:42', '2019-02-05', 'United Airlines', 'special', 500, 'NWo3K7Gyr1ux91vEBdWXIKmqyZ1Gp3UWQ76V3'),
(37, 1, 1, '["Food and Drink","Restaurants"]', '2019-10-23 00:12:42', '2019-01-31', 'Tectra Inc', 'place', 500, '4l9gLaKdJ8UZ13BdWvp4f19gMRw4vefdL3bme'),
(38, 1, 1, '["Payment"]', '2019-10-23 00:12:42', '2019-01-30', 'AUTOMATIC PAYMENT - THANK', 'special', 2078.5, 'ajzLpBMGmVSWxrBpNGA1UnxBN563Dzi7De5XW'),
(39, 1, 1, '["Food and Drink","Restaurants"]', '2019-10-23 00:12:42', '2019-01-30', 'KFC', 'place', 500, 'dQxAEDp9mVUn4XmK6Bo1cn4jgMbkoLiZR6EX3'),
(40, 1, 1, '["Shops","Sporting Goods"]', '2019-10-23 00:12:42', '2019-01-30', 'Madison Bicycle Shop', 'place', 500, 'xzAZlgPmQVIJba9GqDVNiVkeX3R56wCneA4Q3'),
(41, 1, 1, '["Recreation","Gyms and Fitness Centers"]', '2019-10-23 00:12:42', '2019-01-19', 'Touchstone Climbing', 'place', 78.5, '3myD1Gqx9giX5KlDEVdLTMGwn4vaV5FqE8lWy'),
(42, 1, 1, '["Travel","Airlines and Aviation Services"]', '2019-10-23 00:12:42', '2019-01-06', 'United Airlines', 'special', 500, 'B74gPDQW3xFM9mkdZgzpUK6NaJejEPUwgLMkM'),
(43, 1, 1, '["Food and Drink","Restaurants"]', '2019-10-23 00:12:42', '2019-01-01', 'Tectra Inc', 'place', 500, 'z3kEKa71DqCVBW17D6bNFkEAZ5bmjJCoQgNeB');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `account_type` int(1) DEFAULT NULL,
  `last_active` date DEFAULT NULL,
  `public_token_last` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `account_type`, `last_active`, `public_token_last`) VALUES
(1, 'test_user', 3, '2019-10-23', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`card_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `card_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

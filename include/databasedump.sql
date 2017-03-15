-- phpMyAdmin SQL Dump
-- version 4.6.5.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `boardVote`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit`
--

CREATE TABLE `audit` (
  `audit_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `action` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `audit`
--

-- --------------------------------------------------------

--
-- Table structure for table `banned`
--

CREATE TABLE `banned` (
  `banned_id` int(11) NOT NULL,
  `banned_by` int(11) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `description` varchar(500) NOT NULL,
  `location` varchar(200) NOT NULL,
  `datebanned` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banned`
--

INSERT INTO `banned` (`banned_id`, `banned_by`, `first_name`, `last_name`, `description`, `location`, `datebanned`) VALUES
(1, 7, 'Test', 'User', '5\'10\" Male, ', 'Basketball Court', '2017-03-10 21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `discussion`
--

CREATE TABLE `discussion` (
  `discussion_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `motion_id` int(11) NOT NULL,
  `discussion_text` varchar(1000) NOT NULL,
  `dateadded` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `discussion`
--

-- --------------------------------------------------------

--
-- Table structure for table `management`
--

CREATE TABLE `management` (
  `managementID` int(11) NOT NULL,
  `first_name` varchar(200) NOT NULL,
  `last_name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `fenabled` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `management`
--

INSERT INTO `management` (`managementID`, `first_name`, `last_name`, `email`, `fenabled`) VALUES
(1, 'FirstName', 'LastName', 'manager1@management.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `messagesID` int(11) NOT NULL,
  `DateSent` datetime NOT NULL,
  `userTo` int(11) NOT NULL,
  `userFrom` int(11) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `messageRead` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `motionChangeLog`
--

CREATE TABLE `motionChangeLog` (
  `changelogID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `motionid` int(11) NOT NULL,
  `field` varchar(200) NOT NULL,
  `oldValue` varchar(500) NOT NULL,
  `newValue` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `motions`
--

CREATE TABLE `motions` (
  `motion_id` int(11) NOT NULL,
  `motion_name` varchar(50) NOT NULL,
  `motion_description` varchar(500) NOT NULL,
  `dateadded` datetime DEFAULT NULL,
  `motion_disposition` varchar(20) DEFAULT NULL,
  `sent` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `motions`
--


-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `position_id` int(11) NOT NULL,
  `position_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`position_id`, `position_name`) VALUES
(6, 'President'),
(7, 'Vice-President'),
(8, 'Treasurer'),
(9, 'Secretary'),
(10, 'Director');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `users_id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(900) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `position_id` int(11) NOT NULL,
  `enabled` int(11) NOT NULL,
  `temppw` int(1) NOT NULL,
  `lastlogin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`users_id`, `username`, `password`, `first_name`, `last_name`, `email`, `position_id`, `enabled`, `temppw`, `lastlogin`) VALUES
(6, 'jwalters', 'bab4b75fe3a410ba39cee1493bdc79ecf5f1c739', 'FirstName', 'LastName', 'user@email.com', 6, 1, 0, '2017-03-10 14:11:53'),


-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicles_id` int(11) NOT NULL,
  `addedby` int(11) NOT NULL,
  `year` int(4) DEFAULT NULL,
  `make` varchar(200) DEFAULT NULL,
  `model` varchar(200) DEFAULT NULL,
  `color` varchar(15) DEFAULT NULL,
  `plate` varchar(50) DEFAULT NULL,
  `location` varchar(200) NOT NULL,
  `picture` int(11) NOT NULL,
  `dateobserved` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `votes_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `motions_id` int(11) NOT NULL,
  `vote` varchar(10) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `votes
--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit`
--
ALTER TABLE `audit`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `banned`
--
ALTER TABLE `banned`
  ADD PRIMARY KEY (`banned_id`),
  ADD KEY `banned_by` (`banned_by`);

--
-- Indexes for table `discussion`
--
ALTER TABLE `discussion`
  ADD PRIMARY KEY (`discussion_id`),
  ADD KEY `motion_id` (`motion_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `management`
--
ALTER TABLE `management`
  ADD PRIMARY KEY (`managementID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`messagesID`),
  ADD KEY `userTo` (`userTo`),
  ADD KEY `userFrom` (`userFrom`);

--
-- Indexes for table `motionChangeLog`
--
ALTER TABLE `motionChangeLog`
  ADD PRIMARY KEY (`changelogID`),
  ADD KEY `userid` (`userid`),
  ADD KEY `motionid` (`motionid`);

--
-- Indexes for table `motions`
--
ALTER TABLE `motions`
  ADD PRIMARY KEY (`motion_id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`position_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`users_id`),
  ADD UNIQUE KEY `position_id` (`position_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicles_id`),
  ADD KEY `addedby` (`addedby`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`votes_id`),
  ADD UNIQUE KEY `users_id` (`users_id`,`motions_id`),
  ADD KEY `motions_id` (`motions_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit`
--
ALTER TABLE `audit`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;
--
-- AUTO_INCREMENT for table `banned`
--
ALTER TABLE `banned`
  MODIFY `banned_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `discussion`
--
ALTER TABLE `discussion`
  MODIFY `discussion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `management`
--
ALTER TABLE `management`
  MODIFY `managementID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `messagesID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `motionChangeLog`
--
ALTER TABLE `motionChangeLog`
  MODIFY `changelogID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `motions`
--
ALTER TABLE `motions`
  MODIFY `motion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `users_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicles_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `votes_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit`
--
ALTER TABLE `audit`
  ADD CONSTRAINT `audit_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`users_id`);

--
-- Constraints for table `banned`
--
ALTER TABLE `banned`
  ADD CONSTRAINT `banned_ibfk_1` FOREIGN KEY (`banned_by`) REFERENCES `users` (`users_id`);

--
-- Constraints for table `discussion`
--
ALTER TABLE `discussion`
  ADD CONSTRAINT `discussion_ibfk_1` FOREIGN KEY (`motion_id`) REFERENCES `motions` (`motion_id`),
  ADD CONSTRAINT `discussion_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`users_id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`userTo`) REFERENCES `users` (`users_id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`userFrom`) REFERENCES `users` (`users_id`);

--
-- Constraints for table `motionChangeLog`
--
ALTER TABLE `motionChangeLog`
  ADD CONSTRAINT `motionChangeLog_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`users_id`),
  ADD CONSTRAINT `motionChangeLog_ibfk_2` FOREIGN KEY (`motionid`) REFERENCES `motions` (`motion_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`position_id`) REFERENCES `positions` (`position_id`);

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`addedby`) REFERENCES `users` (`users_id`);

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`users_id`),
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`motions_id`) REFERENCES `motions` (`motion_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

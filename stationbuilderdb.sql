SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `commodities` (
  `commodity_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `economy` varchar(32) NOT NULL,
  `edsm_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `relations` (
  `station_id` int(11) NOT NULL,
  `commodity_id` int(11) NOT NULL,
  `original_amount` int(11) NOT NULL,
  `current_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `stations` (
  `station_id` int(11) NOT NULL,
  `system_name` varchar(32) NOT NULL,
  `system_architect` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


ALTER TABLE `commodities`
  ADD PRIMARY KEY (`commodity_id`);

ALTER TABLE `relations`
  ADD KEY `relations_FK_1` (`station_id`),
  ADD KEY `relations_FK_2` (`commodity_id`);

ALTER TABLE `stations`
  ADD PRIMARY KEY (`station_id`);


ALTER TABLE `commodities`
  MODIFY `commodity_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stations`
  MODIFY `station_id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `relations`
  ADD CONSTRAINT `relations_FK_1` FOREIGN KEY (`station_id`) REFERENCES `stations` (`station_id`),
  ADD CONSTRAINT `relations_FK_2` FOREIGN KEY (`commodity_id`) REFERENCES `commodities` (`commodity_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

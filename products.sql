SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `products` (
  `p_id` int(12) NOT NULL,
  `p_name` varchar(64) NOT NULL,
  `p_price` decimal(65,2) NOT NULL,
  `p_stocks` int(3) NOT NULL,
  `p_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `products` (`p_id`, `p_name`, `p_price`, `p_stocks`, `p_image`) VALUES
(1, 'QWERT', 99.99, 12, 'feat1.jpg'),
(2, 'QWERT1', 100.00, 12, 'feat1.jpg'),
(3, 'QWERT2', 10.00, 12, 'feat1.jpg'),
(4, 'QWERT3', 70.00, 81, 'feat1.jpg'),
(5, 'QWERT4', 100.00, 56, 'feat1.jpg'),
(6, 'QWERT5', 100.00, 54, 'feat1.jpg'),
(7, 'QWERT6', 100.00, 54, 'feat1.jpg'),
(8, 'QWERT6', 100.00, 54, 'feat1.jpg');

ALTER TABLE `products`
  ADD PRIMARY KEY (`p_id`);

ALTER TABLE `products`
  MODIFY `p_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;
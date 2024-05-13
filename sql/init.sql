DROP DATABASE if EXISTS quanlicuahanggiay;

CREATE DATABASE IF NOT EXISTS quanlicuahanggiay;

USE quanlicuahanggiay;

SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
  time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;

/*!40101 SET NAMES utf8mb4 */
;

--
-- Database: `quanlicuahanggiay`
--
-- --------------------------------------------------------
--
-- Table structure for table `carts`
--
CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size_id` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `categories`
--
CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `coupons`
--
CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `percent` int(3) NOT NULL,
  `expired` date NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `import`
--
CREATE TABLE `import` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` double NOT NULL,
  `import_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `import_items`
--
CREATE TABLE `import_items` (
  `id` int(11) NOT NULL,
  `import_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` double NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `orders`
--
CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `total_amount` double NOT NULL,
  `customer_name` varchar(50) NOT NULL,
  `customer_phone` varchar(10) NOT NULL,
  `customer_address` varchar(255) NOT NULL,
  `status` enum(
    'pending',
    'accepted',
    'completed',
    'shipping',
    'canceled'
  ) NOT NULL DEFAULT 'pending'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `order_items`
--
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `size_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` double NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `payments`
--
CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `method_id` int(11) DEFAULT NULL,
  `payment_date` datetime NOT NULL DEFAULT current_timestamp(),
  `total_price` double NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `payment_methods`
--
CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `method_name` varchar(50) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `permissions`
--
CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `products`
--
CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `price` double NOT NULL,
  `description` text NOT NULL,
  `image` longtext NOT NULL,
  `gender` int(11) NOT NULL DEFAULT 0,
  `status` enum('active', 'inactive') NOT NULL DEFAULT 'active'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = UTF8MB4_GENERAL_CI;

CREATE TABLE `sizes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `size_items`
--
CREATE TABLE `size_items` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `size_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = UTF8MB4_GENERAL_CI;


CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `gender` tinyint(4) NOT NULL DEFAULT 0,
  `image` longtext DEFAULT NULL,
  `maNhomQuyen` varchar(50) DEFAULT NULL,
  `status` enum('active', 'inactive', 'banned') NOT NULL DEFAULT 'active',
  `address` varchar(255) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;


ALTER TABLE
  `carts`
ADD
  PRIMARY KEY (`id`),
ADD
  KEY `user_id` (`user_id`, `product_id`),
ADD
  KEY `product_id` (`product_id`),
ADD
  KEY `size_id` (`size_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE
  `categories`
ADD
  PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE
  `coupons`
ADD
  PRIMARY KEY (`id`);

--
-- Indexes for table `import`
--
ALTER TABLE
  `import`
ADD
  PRIMARY KEY (`id`),
ADD
  KEY `user_id` (`user_id`);

--
-- Indexes for table `import_items`
--
ALTER TABLE
  `import_items`
ADD
  PRIMARY KEY (`id`),
ADD
  KEY `import_id` (`import_id`, `product_id`, `size_id`),
ADD
  KEY `product_id` (`product_id`),
ADD
  KEY `size_id` (`size_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE
  `orders`
ADD
  PRIMARY KEY (`id`),
ADD
  KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE
  `order_items`
ADD
  PRIMARY KEY (`id`),
ADD
  KEY `order_id` (`order_id`, `product_id`, `size_id`),
ADD
  KEY `product_id` (`product_id`),
ADD
  KEY `size_id` (`size_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE
  `payments`
ADD
  PRIMARY KEY (`id`),
ADD
  KEY `order_id` (`order_id`, `method_id`),
ADD
  KEY `method_id` (`method_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE
  `payment_methods`
ADD
  PRIMARY KEY (`id`);
  
ALTER TABLE
  `products`
ADD
  PRIMARY KEY (`id`),
ADD
  KEY `category_id` (`category_id`);
  
  
ALTER TABLE
  `sizes`
ADD
  PRIMARY KEY (`id`);

--
-- Indexes for table `size_items`
--
ALTER TABLE
  `size_items`
ADD
  PRIMARY KEY (`id`),
ADD
  KEY `product_id` (`product_id`, `size_id`),
ADD
  KEY `size_id` (`size_id`);
  
CREATE TABLE IF NOT EXISTS chucnang (
    maChucNang VARCHAR(50) NOT NULL,
    tenChucNang VARCHAR(255),
    PRIMARY KEY (maChucNang)
);

-- Tạo bảng nhomquyen
CREATE TABLE IF NOT EXISTS nhomquyen (
    maNhomQuyen VARCHAR(50) NOT NULL,
    tenNhomQuyen VARCHAR(255),
    trangThai BIT NOT NULL DEFAULT 1,
    PRIMARY KEY (maNhomQuyen)
);

-- Tạo bảng quyen
CREATE TABLE IF NOT EXISTS quyen (
    maQuyen VARCHAR(50) NOT NULL,
    tenQuyen VARCHAR(255),
    PRIMARY KEY (maQuyen)
);

-- Tạo bảng chitietquyen
CREATE TABLE IF NOT EXISTS chitietquyen (
    maNhomQuyen VARCHAR(50) NOT NULL,
    maChucNang VARCHAR(50) NOT NULL,
    maQuyen VARCHAR(50) NOT NULL,
    PRIMARY KEY (maNhomQuyen, maChucNang, maQuyen)
);


-- Thêm ràng buộc khóa ngoại cho bảng chitietquyen
ALTER TABLE chitietquyen
ADD CONSTRAINT fk_chitietquyen_nhomquyen FOREIGN KEY (maNhomQuyen) REFERENCES nhomquyen(maNhomQuyen) ON UPDATE RESTRICT ON DELETE RESTRICT;

ALTER TABLE chitietquyen
ADD CONSTRAINT fk_chitietquyen_chucnang FOREIGN KEY (maChucNang) REFERENCES chucnang(maChucNang) ON UPDATE RESTRICT ON DELETE RESTRICT;

ALTER TABLE chitietquyen
ADD CONSTRAINT fk_chitietquyen_quyen FOREIGN KEY (maQuyen) REFERENCES quyen(maQuyen) ON UPDATE RESTRICT ON DELETE RESTRICT;


ALTER TABLE `users`
ADD CONSTRAINT fk_users_nhomquyen FOREIGN KEY (maNhomQuyen) REFERENCES nhomquyen(maNhomQuyen) ON UPDATE RESTRICT ON DELETE RESTRICT;

ALTER TABLE
  `users`
ADD
  PRIMARY KEY (`id`),
ADD
  UNIQUE KEY `email` (`email`);
  
  
ALTER TABLE
  `carts`
MODIFY
  `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE
  `categories`
MODIFY
  `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE
  `coupons`
MODIFY
  `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `import`
--
ALTER TABLE
  `import`
MODIFY
  `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `import_items`
--
ALTER TABLE
  `import_items`
MODIFY
  `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE
  `orders`
MODIFY
  `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE
  `order_items`
MODIFY
  `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE
  `payments`
MODIFY
  `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE
  `payment_methods`
MODIFY
  `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE
  `products`
MODIFY
  `id` int(11) NOT NULL AUTO_INCREMENT;
  
ALTER TABLE
  `sizes`
MODIFY
  `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `size_items`
--
ALTER TABLE
  `size_items`
MODIFY
  `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE
  `users`
MODIFY
  `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE
  `carts`
ADD
  CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
ADD
  CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
ADD
  CONSTRAINT `carts_ibfk_3` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`);

--
-- Constraints for table `import`
--
ALTER TABLE
  `import`
ADD
  CONSTRAINT `import_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `import_items`
--
ALTER TABLE
  `import_items`
ADD
  CONSTRAINT `import_items_ibfk_1` FOREIGN KEY (`import_id`) REFERENCES `import` (`id`),
ADD
  CONSTRAINT `import_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
ADD
  CONSTRAINT `import_items_ibfk_3` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE
  `orders`
ADD
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE
  `order_items`
ADD
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
ADD
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
ADD
  CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE
  `payments`
ADD
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`method_id`) REFERENCES `payment_methods` (`id`),
ADD
  CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE
  `products`
ADD
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
  
ALTER TABLE
  `size_items`
ADD
  CONSTRAINT `size_items_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
ADD
  CONSTRAINT `size_items_ibfk_2` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`);
  

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;

ALTER TABLE
  `users`
ADD
  COLUMN forgotToken VARCHAR(100);

ALTER TABLE
  `users`
ADD
  COLUMN activeToken VARCHAR(100);

ALTER TABLE
  `users`
ADD
  COLUMN create_at DATETIME;

ALTER TABLE
  `users`
ADD
  COLUMN update_at DATETIME;

CREATE TABLE tokenLogin(
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  token VARCHAR(100),
  create_at DATETIME
);

ALTER TABLE
  tokenLogin
ADD
  CONSTRAINT FK_tokenLogin_user FOREIGN KEY (user_id) REFERENCES `users`(id);
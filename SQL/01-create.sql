--
-- Initialization of database
--

DROP SCHEMA IF EXISTS edeals;
CREATE SCHEMA edeals;
USE edeals;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS user;
CREATE TABLE user (
    email VARCHAR(45) NOT NULL,
    username VARCHAR(45) NOT NULL,
    password VARCHAR(45) NOT NULL,
    current_score SMALLINT UNSIGNED NOT NULL,
    total_score SMALLINT UNSIGNED NOT NULL,
    total_tokens SMALLINT UNSIGNED NOT NULL,
    last_month_tokens SMALLINT UNSIGNED NOT NULL,
    user_type  ENUM('user', 'administrator') NOT NULL,
    UNIQUE KEY(username),
    PRIMARY KEY(email, username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `store`
--

DROP TABLE IF EXISTS store;
CREATE TABLE store (
    store_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(45) NOT NULL,
    coordinates POINT NOT NULL,
    store_type ENUM('supermarket', 'convenience') NOT NULL,
    PRIMARY KEY(store_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS category;
CREATE TABLE category (
    uuid VARCHAR(45) NOT NULL,
    name VARCHAR(45) NOT NULL,
    PRIMARY KEY(uuid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `subcategory`
--

DROP TABLE IF EXISTS subcategory;
CREATE TABLE subcategory (
    uuid VARCHAR(45) NOT NULL,
    name VARCHAR(45) NOT NULL,
    belongs_to VARCHAR(45) NOT NULL,
    CONSTRAINT subcategory_belongs_to FOREIGN KEY(belongs_to) REFERENCES category(uuid) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (uuid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS item;
CREATE TABLE item (
    item_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    photo VARCHAR(100),
    belongs_to VARCHAR(45) NOT NULL,
    PRIMARY KEY(item_id),
    UNIQUE(name),
    CONSTRAINT item_belongs_to FOREIGN KEY(belongs_to) REFERENCES subcategory(uuid) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `price`
--

DROP TABLE IF EXISTS price;
CREATE TABLE price (
    item_name VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    price FLOAT(5,2) NOT NULL,
    CONSTRAINT price_item_name FOREIGN KEY(item_name) REFERENCES item(name) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY(item_name, date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `offer`
--

DROP TABLE IF EXISTS offer;
CREATE TABLE offer (
    offer_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    store_id SMALLINT UNSIGNED NOT NULL,
    item_id SMALLINT UNSIGNED NOT NULL,
    price FLOAT(5,2) NOT NULL,
    registration_date DATE NOT NULL,
    expiration_date DATE NOT NULL,
    number_of_likes SMALLINT UNSIGNED NOT NULL,
    number_of_dislikes SMALLINT UNSIGNED NOT NULL,
    in_stock BIT NOT NULL,
    uploader_email VARCHAR(45) NOT NULL,
    uploader_username VARCHAR(45) NOT NULL,
    CONSTRAINT offer_store_id FOREIGN KEY(store_id) REFERENCES store(store_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT offer_uploader FOREIGN KEY(uploader_email, uploader_username) REFERENCES user(email, username) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT offer_item_id FOREIGN KEY(item_id) REFERENCES item(item_id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY(offer_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS review;
CREATE TABLE review (
    review_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_email VARCHAR(45) NOT NULL,
    user_username VARCHAR(45) NOT NULL,
    offer_id SMALLINT UNSIGNED NOT NULL,
    rating ENUM('like','dislike') NOT NULL,
    CONSTRAINT review_user FOREIGN KEY(user_email, user_username) REFERENCES user(email, username) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT review_offer_id FOREIGN KEY(offer_id) REFERENCES offer(offer_id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE(user_username,offer_id),
    PRIMARY KEY(review_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

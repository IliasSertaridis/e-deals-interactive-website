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
    store_type ENUM('supermarket', 'convenience store') NOT NULL,
    PRIMARY KEY(store_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS category;
CREATE TABLE category (
    name VARCHAR(45) NOT NULL,
    PRIMARY KEY(name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `subcategory`
--

DROP TABLE IF EXISTS subcategory;
CREATE TABLE subcategory (
    name VARCHAR(45) NOT NULL,
    belongs_to VARCHAR(45) NOT NULL,
    PRIMARY KEY (name),
    CONSTRAINT subcategory_belongs_to FOREIGN KEY(belongs_to) REFERENCES category(name) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS item;
CREATE TABLE item (
    name VARCHAR(45) NOT NULL,
    photo blob,
    mean_daily_price float(5,2) NOT NULL,
    mean_weekly_price float(5,2) NOT NULL,
    belongs_to VARCHAR(45) NOT NULL,
    PRIMARY KEY(name),
    CONSTRAINT item_belongs_to FOREIGN KEY(belongs_to) REFERENCES subcategory(name) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `offer`
--

DROP TABLE IF EXISTS offer;
CREATE TABLE offer (
    offer_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    store_id SMALLINT UNSIGNED NOT NULL,
    price FLOAT(5,2) NOT NULL,
    registration_date DATE NOT NULL,
    expiration_date DATE NOT NULL,
    number_of_likes SMALLINT UNSIGNED NOT NULL,
    number_of_dislikes SMALLINT UNSIGNED NOT NULL,
    in_stock BOOLEAN NOT NULL,
    uploader_email VARCHAR(45) NOT NULL,
    uploader_username VARCHAR(45) NOT NULL,
    item_name VARCHAR(45) NOT NULL,
    CONSTRAINT offer_store_id FOREIGN KEY(store_id) REFERENCES store(store_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT offer_uploader FOREIGN KEY(uploader_email, uploader_username) REFERENCES user(email, username) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT offer_item_name FOREIGN KEY(item_name) REFERENCES item(name) ON DELETE CASCADE ON UPDATE CASCADE,
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
    rating BOOLEAN NOT NULL,
    CONSTRAINT review_user FOREIGN KEY(user_email, user_username) REFERENCES user(email, username) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT review_offer_id FOREIGN KEY(offer_id) REFERENCES offer(offer_id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY(review_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `administrator`
--

DROP TABLE IF EXISTS administrator;
CREATE TABLE administrator (
    email VARCHAR(45) NOT NULL,
    username VARCHAR(45) NOT NULL,
    password VARCHAR(45) NOT NULL,
    UNIQUE KEY(username),
    PRIMARY KEY(email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
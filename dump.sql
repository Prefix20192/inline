START TRANSACTION;
CREATE DATABASE inline;
use inline;
CREATE TABLE `posts`(
	`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `title` varchar(100) NOT NULL,
    `body` varchar(255) NOT NULL,
	`userId` int(11) NOT NULL
);

CREATE TABLE `comments`(
	`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` varchar(80) NOT NULL,
    `email` varchar(80) NOT NULL,
    `body` varchar(300) NOT NULL,
	`postId` int(11) NOT NULL
);
COMMIT;
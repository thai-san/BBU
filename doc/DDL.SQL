CREATE TABLE groups (
	group_id	INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	group_name	VARCHAR(40) NOT NULL
);

CREATE TABLE users (
	user_id		INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_name	VARCHAR(40) NOT NULL UNIQUE KEY,
	password	VARCHAR(40) NOT NULL,
	email			VARCHAR(100),
	last_activity 	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	is_enable		TINYINT(1) DEFAULT 1,
	group_id		INT,
	FOREIGN KEY (group_id) REFERENCES groups (group_id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE students (
	student_id		VARCHAR(40) NOT NULL PRIMARY KEY,
	student_name	VARCHAR(40) NOT NULL,
	email			VARCHAR(100),
	password		VARCHAR(40) NOT NULL,
	last_activity 	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	is_enable		TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE posts (
	post_id			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	post_type		ENUM('PDF','IMAGE'),
	post_title		VARCHAR(255) NOT NULL,
	post_attachment VARCHAR(255),
	post_description TEXT NOT NULL,
	post_date		TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	post_owner		INT NOT NULL,
	category_id		INT NOT NULL,
	expire_date		DATE NOT NULL,
	is_enable		TINYINT(1) NOT NULL DEFAULT 1,
	FOREIGN KEY (post_owner) REFERENCES users (user_id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE books (
	book_id				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	book_title			VARCHAR(100) NOT NULL,
	book_uploader		INT NOT NULL,
	book_upload_date	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	total_download		INT NOT NULL DEFAULT 0,
	FOREIGN KEY (book_uploader) REFERENCES users (user_id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE post_viewer (
	view_id		INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ip_address	VARCHAR(45) DEFAULT '0' NOT NULL,
	student_id	VARCHAR(40) NULL,
	post_id	INT NOT NULL,
	FOREIGN KEY (student_id) REFERENCES students (student_id) ON DELETE RESTRICT ON UPDATE CASCADE,
	FOREIGN KEY (post_id) REFERENCES posts (post_id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE ci_sessions (
	session_id varchar(40) DEFAULT '0' NOT NULL PRIMARY KEY,
	ip_address varchar(45) DEFAULT '0' NOT NULL,
	user_agent varchar(120) NOT NULL,
	last_activity int(10) unsigned DEFAULT 0 NOT NULL,
	user_data text NOT NULL,
	KEY `last_activity_idx` (`last_activity`)
);
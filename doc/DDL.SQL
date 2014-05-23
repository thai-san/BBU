CREATE TABLE user (
	user_id			INT NOT NULL AUTO_INCREMENT,
	user_name		VARCHAR(40) NOT NULL,
	password		VARCHAR(40) NOT NULL,
	email			VARCHAR(100),
	last_activity 	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	is_enable		TINYINT(1) DEFAULT 1,
	PRIMARY KEY (user_id),
	UNIQUE(user_name)
) ENGINE = INNODB CHARACTER SET 'utf8';

CREATE TABLE priviledge (
	priviledge_id 	INT NOT NULL AUTO_INCREMENT,
	priviledeg_name VARCHAR(40) NOT NULL,
	group_name		VARCHAR(40)	NOT NULL,
	PRIMARY KEY (priviledge_id)
) ENGINE = INNODB CHARACTER SET 'utf8';

CREATE TABLE permission (
	permission_id 	INT NOT NULL AUTO_INCREMENT,
	user_id 		INT NOT NULL,
	priviledge_id 	INT NOT NULL,
	PRIMARY KEY (permission_id),
	UNIQUE 	KEY (user_id,priviledge_id),
	FOREIGN KEY (user_id) REFERENCES user (user_id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (priviledge_id) REFERENCES priviledge (priviledge_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = INNODB CHARACTER SET = 'utf8';

CREATE TABLE post (
	post_id			INT NOT NULL AUTO_INCREMENT,
	post_title		VARCHAR(255) NOT NULL,
	post_content	TEXT NOT NULL,
	post_date		DATE NOT NULL,
	post_owner		INT NOT NULL,
	PRIMARY KEY (post_id),
	FOREIGN KEY (post_owner) REFERENCES user (user_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = INNODB CHARACTER SET 'utf8';

CREATE TABLE book (
	book_id				INT NOT NULL AUTO_INCREMENT,
	book_title			VARCHAR(100) NOT NULL,
	book_uploader		INT NOT NULL,
	book_upload_date	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	total_download		INT NOT NULL DEFAULT 0,
	PRIMARY KEY (book_id),
	FOREIGN KEY (book_uploader) REFERENCES user (user_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = INNODB CHARACTER SET 'utf8';

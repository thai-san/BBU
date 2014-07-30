
INSERT INTO `permission` VALUES ('1', 'add user', '1');
INSERT INTO `permission` VALUES ('2', 'delete user', '1');
INSERT INTO `permission` VALUES ('3', 'reset password', '1');
INSERT INTO `permission` VALUES ('4', 'add post', '2');
INSERT INTO `permission` VALUES ('5', 'delete post', '2');
INSERT INTO `permission` VALUES ('6', 'edit post', '2');
INSERT INTO `permission` VALUES ('7', 'enable post', '2');
INSERT INTO `permission` VALUES ('8', 'disable post', '2');
INSERT INTO `permission` VALUES ('9', 'enable user', '1');
INSERT INTO `permission` VALUES ('10', 'disable user', '1');
INSERT INTO `permission` VALUES ('11', 'upload ebook', '3');
INSERT INTO `permission` VALUES ('12', 'delete ebook', '3');
INSERT INTO `permission` VALUES ('13', 'enable ebook', '3');
INSERT INTO `permission` VALUES ('14', 'disable ebook', '3');
INSERT INTO `permission` VALUES ('15', 'view info', '4');

INSERT INTO `priviledge` VALUES ('1', '1', '1');
INSERT INTO `priviledge` VALUES ('2', '1', '2');
INSERT INTO `priviledge` VALUES ('3', '1', '3');
INSERT INTO `priviledge` VALUES ('4', '1', '9');
INSERT INTO `priviledge` VALUES ('5', '1', '10');

INSERT INTO `user` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'bbu@gmail.com', '2014-05-10 20:15:04', '0');
INSERT INTO `user` VALUES ('5', 'leak', '202cb962ac59075b964b07152d234b70', 'leak@gmail.com', '2014-05-10 20:15:14', '0');
INSERT INTO `user` VALUES ('6', 'san', '202cb962ac59075b964b07152d234b70', 'san@gmail.com', '2014-05-10 20:18:56', '1');

INSERT INTO `user_group` VALUES ('1', 'admin');
INSERT INTO `user_group` VALUES ('2', 'staff');
INSERT INTO `user_group` VALUES ('3', 'teacher');
INSERT INTO `user_group` VALUES ('4', 'student');

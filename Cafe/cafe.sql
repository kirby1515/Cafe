CREATE DATABASE cafe;
USE cafe;

CREATE TABLE usertype(
id int AUTO_INCREMENT,
name varchar(255) UNIQUE,
PRIMARY KEY(id)
);
INSERT INTO usertype (name) VALUES ('admin');
INSERT INTO usertype (name) VALUES ('member');
INSERT INTO usertype (name) VALUES ('staff');

CREATE TABLE profile(
id int AUTO_INCREMENT,
fname varchar(255),
mname varchar(255),
lname varchar(255),
suffix varchar(255),
user_address varchar(255),
user_phonenumber int(255),
email varchar(255),
utype int(255),
birthdate date,
usertype_id int,
PRIMARY KEY(id),
FOREIGN KEY(usertype_id) REFERENCES usertype(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE login(
id int AUTO_INCREMENT,
username varchar(255) UNIQUE,
password varchar(255),
email varchar(255),
utype int(255),
usertype_id int,
PRIMARY KEY(id),
FOREIGN KEY(usertype_id) REFERENCES usertype(id) ON DELETE CASCADE ON UPDATE CASCADE
);
INSERT INTO login (username,password,usertype_id) VALUES ('admin','admin','1');

CREATE TABLE orders(
id int AUTO_INCREMENT,
info varchar(255),
quantity int(255),
returnPrice int(255),
oname varchar(255),
phone int(12),
address varchar(255),
country varchar(255),
city varchar(255),
login_id int,
returnPrice DECIMAL(10,2) NOT NULL,
info TEXT NOT NULL;
status varchar(50) DEFAULT 'proceed',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id),
FOREIGN KEY (login_id) REFERENCES login(id) ON DELETE CASCADE ON UPDATE CASCADE
);

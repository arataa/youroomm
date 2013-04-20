use youroom;

CREATE TABLE profile (
  user_id int,
  last_name varchar(40),
  first_name varchar(40),
  picture MEDIUMBLOB,
  continent int not null,
  PRIMARY KEY (user_id) );

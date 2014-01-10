CREATE TABLE "users" (
    "id" SERIAL NOT NULL,
    "ip_address" inet NOT NULL,
    "username" varchar(100) NOT NULL,
    "password" varchar(80) NOT NULL,
    "salt" varchar(40),
    "email" varchar(100) NOT NULL,
    "activation_code" varchar(40),
    "forgotten_password_code" varchar(40),
    "forgotten_password_time" int,
    "remember_code" varchar(40),
    "created_on" int NOT NULL,
    "last_login" int,
    "active" int4,
    "first_name" varchar(50),
    "last_name" varchar(50),
    --"company" varchar(100),
    "phone" varchar(50),
    "identification" varchar(20),
    "city" varchar(50)
  PRIMARY KEY("id")
);


CREATE TABLE "groups" (
    "id" SERIAL NOT NULL,
    "name" varchar(20) NOT NULL,
    "description" varchar(100) NOT NULL,
  PRIMARY KEY("id")
);


CREATE TABLE "users_groups" (
    "id" SERIAL NOT NULL,
    "user_id" integer NOT NULL,
    "group_id" integer NOT NULL,
  PRIMARY KEY("id"),
  CONSTRAINT "uc_users_groups" UNIQUE (user_id)
);


INSERT INTO groups (id, name, description) VALUES
    (1,'Administrador','Administrador'),
    (2,'Digitador','Digitador');

INSERT INTO users (ip_address, username, password, salt, email, activation_code, forgotten_password_code, active, first_name, last_name, created_on) VALUES
    ('127.0.0.1','admin','59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4','9462e8eee0','admin@admin.com','',NULL, '1','Admin Plan','Fruticola', extract(epoch from current_timestamp)::int);

INSERT INTO users_groups (user_id, group_id) VALUES
    (1,1);

CREATE TABLE "login_attempts" (
    "id" SERIAL NOT NULL,
    "ip_address" inet NOT NULL,
    "login" varchar(100) NOT NULL,
    "time" int,
  PRIMARY KEY("id")
);

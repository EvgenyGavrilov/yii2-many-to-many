DROP TABLE IF EXISTS "user_group";
DROP TABLE IF EXISTS "user";
DROP TABLE IF EXISTS "group";

CREATE TABLE "user" (
  id serial NOT NULL,
  username character varying(50),
  CONSTRAINT user_pkey PRIMARY KEY (id)
);

INSERT INTO "user" VALUES (1,'user1@mail.com'),(2,'user2@mail.com');

CREATE TABLE "group" (
  id serial NOT NULL,
  "name" varchar(50) DEFAULT NULL,
  CONSTRAINT group_pkey PRIMARY KEY (id)
);

INSERT INTO "group" VALUES (1,'admin'),(2,'reporter'),(3,'manager'),(4,'developer');

CREATE TABLE "user_group" (
  "user_id" integer NOT NULL,
  "group_id" integer NOT NULL,
  CONSTRAINT user_group_pkey PRIMARY KEY ("user_id","group_id"),
  CONSTRAINT "fk_user_group_user_id" FOREIGN KEY ("user_id") REFERENCES "user" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT "fk_user_group_group_id" FOREIGN KEY ("group_id") REFERENCES "group" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION
);

INSERT INTO "user_group" VALUES (2,2),(2,3);
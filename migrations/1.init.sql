

CREATE TABLE discord_inviter_users (
	google_id varchar(21) NOT NULL PRIMARY KEY,
	discord_id varchar(18) UNIQUE,
	school_email text NOT NULL
);


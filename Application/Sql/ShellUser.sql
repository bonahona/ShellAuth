create table ShellUser (
	Id int primary key auto_increment,
	Username varchar(256),
	PasswordHash varchar(256),
	PasswordSalt varchar(256),
	DisplayName varchar(256),
	IsInactive int(1) not null default(0),
	IsDeleted int(1) not null default(0)
);


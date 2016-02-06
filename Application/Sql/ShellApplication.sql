create table ShellApplication(
  Id int primary key auto_increment,
  ApplicationName varchar(512),
  IsInactive int(1) not null default 0,
  IsDeleted int(1) not null default 0,
  DefaultUserLevel int,
  RsaPublicKey varchar(512),
  RsaPrivateKey varchar(2048)
);
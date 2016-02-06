create table ShellUserAccessToken(
  Id int primary key auto_increment,
  Guid varchar(512),
  Issued TimeStamp,
  Expires TimeStamp,
  ShellUserPrivilegeId int not null,
  Foreign key (ShellUserPrivilegeId) references ShellUserPrivilege(Id)
);
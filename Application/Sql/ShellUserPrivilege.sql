create table ShellUserPrivilege(
  Id int primary key auto_increment,
  UserLevel int not null,
  ShellUserId int not null,
  ShellApplicationId int not null,
  Foreign Key(ShellUserId) References ShellUser(Id),
  Foreign KEY(ShellApplicationId) References ShellApplication(Id)
);
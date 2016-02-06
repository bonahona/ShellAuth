create table ShellUserActionLog(
  Id int primary key auto_increment,
  `TimeStamp` TIMESTAMP,
  ActionName varchar(512),
  ShellUserId int not null,
  ShellApplicationId int not null,
  Foreign key (ShellUserId) references ShellUser(Id),
  Foreign key (ShellApplicationId) references ShellApplication(Id)
);
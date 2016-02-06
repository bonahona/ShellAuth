create table ShellUserChangeLog(
 Id int primary key auto_increment,
 `TimeStamp` TimeStamp,
 ShellUserId int not null,
 ShellApplicationId int not null,
 Foreign key (ShellUserId) references ShellUser(Id),
 Foreign key (ShellApplicationId) references ShellApplication(Id)
);
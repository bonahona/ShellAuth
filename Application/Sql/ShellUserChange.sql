create table ShellUserChange(
  Id int primary key auto_increment,
  OldValue varchar(512),
  NewValue varchar(512),
  ShellUserChangeLogId int not null,
  Foreign key (ShellUserChangeLogId) references ShellUserChangeLog(Id)
);
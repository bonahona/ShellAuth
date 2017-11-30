<?php
class DbCreation implements IDatabaseMigration
{
    public function GetUniqueName()
    {
        return '12ffd4965b874481bb6ef8da42e5ebb6';
    }

    public function GetSortOrder()
    {
        return 0;
    }

    public function Up($migrator)
    {
        $migrator->CreateTable('ShellApplication')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('Name', 'varchar(512)')
            ->AddColumn('IsActive', 'int(1)', array('not null', 'default 0'))
            ->AddColumn('IsDeleted', 'int(1)', array('not null', 'default 0'))
            ->AddColumn('DefaultUserLever', 'int', array('not null', 'default 0'))
            ->AddColumn('RsaPublicKey', 'varchar(512)')
            ->AddColumn('RsaPrivateKey', 'varchar(512)');

        $migrator->CreateTable('ShellUser')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('Username', 'varchar(256)')
            ->AddColumn('PasswordHash', 'varchar(256)')
            ->AddColumn('PasswordSalt', 'varchar(256)')
            ->AddColumn('DisplayName', 'varchar(256)')
            ->AddColumn('IsActive', 'int(1)', array('not null', 'default 0'))
            ->AddColumn('IsDeleted', 'int(1)', array('not null', 'default 0'));

        $migrator->CreateTable('ShellUserPrivilege')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('UserLevel', 'int', array('not null', 'default 0'))
            ->AddReference('ShellUser', 'Id')
            ->AddReference('ShellApplication', 'Id');

        $migrator->CreateTable('ShellUserAccessToken')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('Guid', 'varchar(512)')
            ->AddColumn('Issued', 'varchar(512)')
            ->AddColumn('Expires', 'varchar(512)')
            ->AddReference('ShellUserPrivilege', 'Id');

        $migrator->CreateTable('ShellUserActionLog')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('`TimeStamp`', 'varchar(512)')
            ->AddColumn('ActionName', 'varchar(512)')
            ->AddReference('ShellUser', 'Id')
            ->AddReference('ShellApplication', 'Id');
    }

    public function Down($migrator)
    {
        $migrator->DropTable('ShellApplication');
        $migrator->DropTable('ShellUser');
        $migrator->DropTable('ShellUserPrivilege');
        $migrator->DropTable('ShellUserAccessToken');
    }

    public function Seed($migrator)
    {
    }
}
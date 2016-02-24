<?php
class ShellUser extends Model
{
    public $TableName = "shelluser";

    public function Clean()
    {
        $result = array();
        foreach($this->Properties as $name => $value){
            $result[$name] =  $value;
        }

        unset($result['PasswordSalt']);
        unset($result['PasswordHash']);
        unset($result['IsDeleted']);
        unset($result['IsInactive']);

        return $result;
    }

    public function Summary()
    {
        $result = $this->Clean();

        $result['ShellUserPrivileges'] = array();

        foreach($this->ShellUserPrivileges as $privilege){
            $result['ShellUserPrivileges'][] = $privilege->Clean();
        }

        return $result;
    }

    public function CreatePassword($password)
    {
        $this->PasswordSalt = uniqid('', true);
        $this->PasswordHash = hash('sha256', $password . $this->PasswordSalt);
    }

    public function ValidatePassword($password)
    {
        if(empty($password)){
            return false;
        }

        $hashedPassword = hash('sha256', $password . $this->PasswordSalt);
        return $hashedPassword == $this->PasswordHash;

    }
}
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
        return $this;
    }

    public function ValidatePassword($password)
    {
        if(empty($password)){
            return false;
        }

        $hashedPassword = hash('sha256', $password . $this->PasswordSalt);
        return $hashedPassword == $this->PasswordHash;
    }

    public function GetAccessToken($applicationId)
    {
        if($applicationId == null || $applicationId == ''){
            return null;
        }

        $accessTokens = $this->Models->ShellUserAccessToken->Where(['ShellUserId' => $this->Id, 'ShellApplicationId' => $applicationId]);

        $currentDate = date('Y-m-d H:i:s');
        $result = $this->Models->ShellUserAccessToken->Create([
            'ShellUserId' => $this->Id,
            'ShellApplicationId' => $applicationId,
            'Issued' => $currentDate
        ]);

        $result->GenerateGuid();
        $result->SetExpiresDateFromIssuedDate();
        $result->Save();

        return $result;
    }

    public function LogAction($actionName, $applicationId)
    {
        $result = $this->Models->ShelluserActionLog->Create([
            'TimeStamp' => date('Y-m-d H:i:s'),
            'ActionName' => $actionName,
            'ShellUserId' => $this->Id,
            'ShellApplicationId' => $applicationId
        ]);

        $result->Save();
    }
}
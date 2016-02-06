<?php
require_once('BaseController.php');
class UserController extends BaseController
{
    public function Create()
    {
        $shellApplication = $this->ValidateApplication();
        if($shellApplication == null){
            return $this->InvalidApplication();
        }


        $shellUser = $this->Models->ShellUser->Create();
        $shellUser->Username = $this->PayLoad['ShellUser']['Username'];
        $shellUser->DisplayName = $this->PayLoad['ShellUser']['DisplayName'];
        $shellUser->CreatePassword($this->PayLoad['ShellUser']['Password']);

        if($this->Models->ShellUser->Any(array('Username' => $shellUser->Username))){
            return $this->Error('Username already taken');
        }else{
            $shellUser->Save();
        }

        $this->CreateActionLogEntry($shellUser, $shellApplication, 'Create');
        return $this->Response($shellUser->Clean());
    }

    public function Login()
    {
        $shellApplication = $this->ValidateApplication();
        if($shellApplication == null){
            return $this->InvalidApplication();
        }

        $username = $this->PayLoad['ShellUser']['Username'];
        $password = $this->PayLoad['ShellUser']['Password'];

        // Check if the user exists
        $shellUser = $this->Models->User->Where(array('Username' => $username, 'IsDeleted' => 0, 'IsInactive' => 0))->First();
        if($shellUser == null){
            return $this->Error('User could not login');
        }

        // Check if the sure the credentials are valid
        if(!$shellUser->ValidatePassword($password)){
            return $this->Error('User could not login');
        }

        // Make sure the user has access to this specific application
        $privilege = $shellUser->ShellUserPrivileges->Where(array('ShellApplicationId' => $shellApplication->Id))->First();

        if($privilege == null){
            $privilege = $this->Models->ShellUser->Create();
            $privilege->ShellUserId = $shellUser->Id;
            $privilege->ShellApplicationId = $shellApplication->Id;
            $privilege->UserLevel = $shellApplication->DefaultUserLevel;
            $privilege->Save();
        }

        if($privilege->UserLevel == 0){
            return $this->Error('Insufficient privileges');
        }

        // Create an access token and return that back to the user
        $accessToken = $this->Models->ShellUserAccessToken->Create();
        $accessToken->ShellUserPrivilegeId = $privilege->Id;
        $accessToken->Guid = uniqid('', true);
        $accessToken->Issued = date('Y-m-d H:M');
        $accessToken->Save();

        $this->CreateActionLogEntry($shellUser, $shellApplication, 'Login');

        return $this->Response(array(
            'AccessToken' => $accessToken->Guid,
            'User' => $shellUser->Clean()
        ));
    }

    public function Logout()
    {
        $application = $this->ValidateApplication();
        if($application == null){
            return $this->InvalidApplication();
        }

        $accessTokenGuid = $this->PayLoad['AccessToken'];
        $accessToken = $this->Models->ShellUserAccesToken->Where(array('Guid' => $accessTokenGuid))->First();
        if($accessToken == null){
            return $this->Error('Invalid access token');
        }

        $accessToken->Delete();
        return $this->Response();
    }

    public function CheckAccessToken()
    {
        $application = $this->ValidateApplication();
        if($application == null){
            return $this->InvalidApplication();
        }

        $accessTokenGuid = $this->PayLoad['AccessToken'];
        $accessToken = $this->Models->ShellUserAccesToken->Where(array('Guid' => $accessTokenGuid))->First();
        if($accessToken == null){
            return $this->Error('Invalid access token');
        }

        $shellUser = $this->Models->ShellUser->Find($accessToken->ShellUserPrivilege->ShellUserId);
        if($shellUser == null){
            return $this->Error('Failed to associate access token with a user');
        }

        return $this->Response($shellUser->Clean());
    }

    public function Get()
    {
        $application = $this->ValidateApplication();
        if($application == null){
            return $this->InvalidApplication();
        }

        if(isset($this->PayLoad['Id'])){
            $id = $this->PayLoad['Id'];
        }else{
            $id = null;
        }

        if($id == null){
            return $this->Error('No id specified');
        }

        $shellUser = $this->Models->ShellUser->Find($id);
        return $this->Response($shellUser->Clean());
    }

    public function SetPrivilegeLevel()
    {
        $application = $this->ValidateApplication();
        if($application == null){
            return $this->InvalidApplication();
        }

        $shellApplicationId = $this->PayLoad['ShellUserPrivilege']['ShellApplicationId'];
        $shellUserId = $this->PayLoad['ShellUserPrivilege']['UserId'];
        $userLevel = $this->PayLoad['ShellUserPrivilege']['UserLevel'];

        $shellUserPrivilege = $this->Models->ShellUserPrivilege->Where(array('ShellUserId' => $shellUserId, 'ShellApplication' => $shellApplicationId))->First();
        if($shellUserPrivilege == null){
            $shellUserPrivilege = $this->Models->ShellUserPrivilege->Create();
            $shellUserPrivilege->ShellApplicationId = $shellApplicationId;
            $shellUserPrivilege->ShellUserId = $shellUserId;
        }

        $shellUserPrivilege->UserLevel = $userLevel;
        $shellUserPrivilege->Save();
    }

    protected function CreateActionLogEntry($shellUser, $shellApplication, $action)
    {
        $actionLog = $this->Models->ShellUserActionLog->Create();
        $actionLog->ShellUserId = $shellUser->Id;
        $actionLog->ShellApplicationId = $shellApplication->Id;
        $actionLog->TimeStamp = date('Y-m-d H:i:s');
        $actionLog->ActionName = $action;
        $actionLog->Save();
    }
}
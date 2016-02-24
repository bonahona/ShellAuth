<?php
class ShellUserPrivilege extends Model
{
    public $TableName = "shelluserprivilege";

    public function Clean()
    {
        $result = array();
        foreach($this->Properties as $key => $value){
            $result[$key] = $value;
        }

        $result['ShellApplication'] = $this->ShellApplication->Clean();

        return $result;
    }

    public function GetUserSummary()
    {
        $result = array();

        foreach($this->Properties as $key => $value){
            $result[$key] = $value;
        }

        $result['User'] = $this->ShellUser->Clean();

        return $result;
    }
}
<?php
class ShellUserPrivilege extends Model
{
    public $TableName = "shelluserprivilege";

    public function Summary()
    {
        $result = array();

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
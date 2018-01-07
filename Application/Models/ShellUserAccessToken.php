<?php

const DEFAULT_VALIDITY_IN_DAYS = 30;

class ShellUserAccessToken extends Model
{
    public $TableName = "shelluseraccesstoken";

    public function GenerateGuid()
    {
        $this->Guid = uniqid('', true);
    }

    public function SetExpiresDateFromIssuedDate()
    {
        $this->Expires = date('Y-m-d H:i:s', strtotime($this->Issued . ' + ' . DEFAULT_VALIDITY_IN_DAYS .' days'));
    }

    public function IsValid()
    {
        $currentDate = date('Y-m-d H:i:s');

        if($this->Cancelled == 1){
            return false;
        }

        if($currentDate > $this->Expires){
            return false;
        }

        return true;
    }
}
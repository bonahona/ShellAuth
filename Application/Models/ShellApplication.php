<?php
class ShellApplication extends Model
{
    public $TableName = "shellapplication";

    public function Clean()
    {
         $result = array();

        foreach($this->Properties as $name => $value){
            $result[$name] = $value;
        }

        unset($result['RsaPrivateKey']);
        unset($result['IsDeleted']);
        return $result;
    }
}
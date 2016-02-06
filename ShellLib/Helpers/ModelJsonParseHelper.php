<?php

class ModelJsonParseHelper
{
    public $Controller;

    function __construct($controller)
    {
        $this->Controller = $controller;
    }

    public function ParseStringToModel($string, $model)
    {
        $parsedData = json_decode($string, true);
        return $this->ParseJsonToModel($parsedData, $model);
    }

    public function ParseJsonToModel($json, $model)
    {
        $modelCache = $model->ModelCache;
        $modelName = $model->ModelName;

        if(key($json) == $modelName){
            $rootObject = $json[$modelName];
        }else{
            $rootObject = $json;
        }

        $result = $model->Create();
        foreach($rootObject as $property => $value){
            $result->$property = $value;
        }
        return $result;
    }
}
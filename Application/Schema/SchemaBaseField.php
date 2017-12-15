<?php
use \Youshido\GraphQL\Field\AbstractField;
abstract class SchemaBaseField extends AbstractField
{
    public function __construct($controller, array $config = [])
    {
        parent::__construct($config);
        $this->Controller = $controller;
    }

    /* @var Controller $Controller */
    public $Controller;

    public function UpdateNonNullFields($model, $fields){
        foreach($fields as $key => $value){
            $model->$key = $value;
        }
    }
}
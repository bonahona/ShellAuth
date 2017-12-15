<?php
use \Youshido\GraphQL\Type\Object\AbstractObjectType;
abstract class SchemaBaseType extends AbstractObjectType
{
    public function __construct($controller, array $config = [])
    {
        parent::__construct($config);
        $this->Controller = $controller;
    }

    /* @var Controller $Controller */
    public $Controller;
}
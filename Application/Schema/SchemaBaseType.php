<?php
use \Youshido\GraphQL\Type\Object\AbstractObjectType;
abstract class SchemaBaseType extends AbstractObjectType
{
    public function __construct($models, array $config = [])
    {
        parent::__construct($config);
        $this->Models = $models;
    }

    /* @var Models $Models */
    public $Models;
}
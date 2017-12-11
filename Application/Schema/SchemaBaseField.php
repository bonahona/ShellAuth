<?php
use \Youshido\GraphQL\Field\AbstractField;
abstract class SchemaBaseField extends AbstractField
{
    public function __construct($models, array $config = [])
    {
        parent::__construct($config);
        $this->Models = $models;
    }

    /* @var Models $Models */
    public $Models;
}
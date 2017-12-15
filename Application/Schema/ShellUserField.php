<?php
use \Youshido\GraphQL\Execution\ResolveInfo;
use \Youshido\GraphQL\Config\Field\FieldConfig;
use \Youshido\GraphQL\Type\Scalar\StringType;

class ShellUserField extends SchemaBaseField {

    public function build(FieldConfig $config){
        $config->addArguments([
            'id' => new StringType()
        ]);
    }
    public function getType()
    {
        return new ShellUserType($this->Controller);
    }

    public function getName()
    {
        return 'ShellUser';
    }

    public function resolve($value, array $args, ResolveInfo $info)
    {


    }
}
<?php
use \Youshido\GraphQL\Type\Scalar\StringType;
use \Youshido\GraphQL\Type\Scalar\BooleanType;

class ShellUserType extends SchemaBaseType {

    public function build($config){
        $config
            ->addFields([
                'Id' => new StringType(),
                'Username' => new StringType(),
                'DisplayName' => new StringType(),
                'IsActive' => new BooleanType()
        ]);
    }

    public function getName(){
        return "ShellUser";
    }
}
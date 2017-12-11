<?php
use \Youshido\GraphQL\Type\Object\AbstractObjectType;
use \Youshido\GraphQL\Type\Scalar\StringType;
use \Youshido\GraphQL\Type\Scalar\BooleanType;
use \Youshido\GraphQL\Type\Scalar\IntType;

class ShellUserPrivilegeType extends SchemaBaseType {

    public function build($config){
        $config
            ->addFields([
                'UserLevel' => new IntType(),
                'ShellUser' => new ShellUserField($this->Models),
                'ShellUserId' => new IntType(),
                'ShellApplicationId' => new IntType(),
            ]);
    }

    public function getName(){
        return "ShellUserPrivilege";
    }
}
<?php
use \Youshido\GraphQL\Type\Object\AbstractObjectType;
use \Youshido\GraphQL\Type\Scalar\StringType;
use \Youshido\GraphQL\Type\Scalar\BooleanType;
use \Youshido\GraphQL\Type\Scalar\IntType;
use \Youshido\GraphQL\Type\ListType\ListType;

class ShellUserActionLogType extends SchemaBaseType {

    public function build($config){
        $config
            ->addFields([
                'TimeStamp' => new StringType(),
                'ActionName' => new StringType(),
                'ShellUserId' => new StringType(),
                'ShellUserApplicationId' => new StringType()
            ]);
    }

    public function getName(){
        return "ShellUserActionLog";
    }
}
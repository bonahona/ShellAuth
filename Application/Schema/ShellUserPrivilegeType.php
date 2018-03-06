<?php
use \Youshido\GraphQL\Type\Object\AbstractObjectType;
use \Youshido\GraphQL\Type\Scalar\StringType;
use \Youshido\GraphQL\Type\Scalar\BooleanType;
use \Youshido\GraphQL\Type\Scalar\IntType;

class ShellUserPrivilegeType extends SchemaBaseType {

    public function build($config){
        $config
            ->addFields([
                'Id' => new StringType(),
                'UserLevel' => new IntType(),
                'ShellUser' => [
                    'type' => new ShellUserType($this->Controller),
                    'resolve' => function($value, $args, $resolveInfo) {
                        return $this->Controller->Models->ShellUser->Where(['Id' => $value['ShellUserId'], 'IsDeleted' => 0])->First()->Object();
                    }
                ],
                'ShellUserId' => new IntType(),
                'ShellApplication' => [
                    'type' => new ShellApplicationType($this->Controller),
                    'resolve' => function($value, $args, $resolveInfo) {
                        return $this->Controller->Models->ShellApplication->Where(['Id' => $value['ShellApplicationId'], 'IsDeleted' => 0])->First()->Object();
                    }
                ],
                'AccessToken' => [
                    'type' => new ShellUserAccessTokenType($this->Controller),
                    'resolve' => function($value, $args, $info){

                    }
                ]
            ]);
    }

    public function getName(){
        return "ShellUserPrivilege";
    }
}
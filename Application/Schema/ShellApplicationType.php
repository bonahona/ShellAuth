<?php
use \Youshido\GraphQL\Type\Object\AbstractObjectType;
use \Youshido\GraphQL\Type\Scalar\StringType;
use \Youshido\GraphQL\Type\Scalar\BooleanType;
use \Youshido\GraphQL\Type\Scalar\IntType;
use \Youshido\GraphQL\Type\ListType\ListType;

class ShellApplicationType extends SchemaBaseType {

    public function build($config){
        $config
            ->addFields([
                'Id' => new StringType(),
                'Name' => new StringType(),
                'IsActive' => new IntType(),
                'DefaultUserLevel' => new IntType(),
                'ShowInMenu' => new IntType(),
                'MenuName' => new StringType(),
                'Url' => new StringType(),
                'RsaPublicKey' => new StringType(),
                'Privileges' => [
                    'type' => new ListType(new ShellUserPrivilegeType($this->Controller)),
                    'resolve' =>     function ($value, array $args, $info){
                        $result = array();
                        foreach($this->Controller->Models->ShellUserPrivilege->Where(['ShellApplicationId' => $value['Id']]) as $privilege){
                            $result[] = $privilege->Object();
                        }

                        return $result;
                    }
                ],'ActionLog' => [
                    'type' => new ListType(new ShellUserActionLogType($this->Controller)),
                    'resolve' => function($value, $args, $info){
                        $result = array();
                        foreach($this->Controller->Models->ShellUserActionLog->Where(['ShellApplicationId' => $value['Id']])->OrderBy('TimeStamp') as $actionLog){
                            $result[] = $actionLog->Object();
                        }
                        return $result;
                    }
                ]
            ]);
    }

    public function getName(){
        return "ShellApplication";
    }
}
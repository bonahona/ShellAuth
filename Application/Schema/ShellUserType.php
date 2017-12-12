<?php
use \Youshido\GraphQL\Type\Scalar\StringType;
use \Youshido\GraphQL\Type\Scalar\IntType;
use \Youshido\GraphQL\Type\Scalar\BooleanType;
use \Youshido\GraphQL\Type\ListType\ListType;

class ShellUserType extends SchemaBaseType {

    public function build($config){
        $config
            ->addFields([
                'Id' => new StringType(),
                'Username' => new StringType(),
                'DisplayName' => new StringType(),
                'IsActive' => new IntType(),
                'Privileges' => [
                    'type' => new ListType(new ShellUserPrivilegeType($this->Models)),
                    'resolve' => function($value, $args, $resolveInfo) {
                        $result = array();
                        foreach($this->Models->ShellUserPrivilege->Where(['ShellUserId' => $value['Id']]) as $privilege){
                            $result[] = $privilege->Object();
                        }
                        return $result;
                    }
                ],
                'ActionLog' => [
                    'type' => new ListType(new ShellUserActionLogType($this->Models)),
                    'resolve' => function($value, $args, $info){
                        $result = array();
                        foreach($this->Models->ShellUserActionLog->Where(['ShellUserId' => $value['Id']])->OrderBy('TimeStamp') as $actionLog){
                            $result[] = $actionLog->Object();
                        }
                        return $result;
                    }
                ]
        ]);
    }

    public function getName(){
        return "ShellUser";
    }
}
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
                'DefaultUserLever' => new IntType(),
                'RsaPublicKey' => new StringType(),
                'Privileges' => [
                    'type' => new ListType(new ShellUserPrivilegeType($this->Models)),
                    'resolve' =>     function ($value, array $args, $info){
                        $result = array();
                        foreach($this->Models->ShellUserPrivilege->Where(['ShellApplicationId' => $value['Id']]) as $privilege){
                            $result[] = $privilege->Object();
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
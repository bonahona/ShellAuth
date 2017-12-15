<?php
use \Youshido\GraphQL\Type\Object\AbstractObjectType;
use \Youshido\GraphQL\Type\Scalar\StringType;
use \Youshido\GraphQL\Type\Scalar\BooleanType;
use \Youshido\GraphQL\Type\Scalar\IntType;
use \Youshido\GraphQL\Type\ListType\ListType;

class ShellUserAccessTokenType extends SchemaBaseType {

    public function build($config){
        $config
            ->addFields([
                'Guid' => new StringType(),
                'Issued' => new StringType(),
                'Expires' => new StringType(),
                'ShellUserPrivilege' => [
                    'type' => new ListType(new ShellUserPrivilegeType($this->Controller)),
                    'resolve' =>     function ($value, array $args, $info){
                        $result = $this->Controller->Models->ShellUserPrivilege->Where(['Id' => $value['ShellUserPrivilegeId']])->OrderBy('Expires')->First();
                        return $result->Object();
                    }
                ]
            ]);
    }

    public function getName(){
        return "ShellUserAccessToken";
    }
}
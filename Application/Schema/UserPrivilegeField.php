<?php
use \Youshido\GraphQL\Execution\ResolveInfo;
use \Youshido\GraphQL\Config\Field\FieldConfig;
use \Youshido\GraphQL\Type\Scalar\StringType;
use \Youshido\GraphQL\Type\Scalar\IntType;
use \Youshido\GraphQL\Type\NonNullType;

class UserPrivilegeField extends SchemaBaseField {

    public function build(FieldConfig $config){
        $config->addArguments([
            'Id' => new StringType(),
            'ShellUserId' => new StringType(),
            'ShellApplicationId' => new StringType(),
            'UserLevel' => new IntType()
        ]);
    }
    public function getType()
    {
        return new ShellUserPrivilegeType($this->Models);
    }

    public function getName()
    {
        return 'ShellUserPrivilege';
    }

    public function resolve($value, array $args, ResolveInfo $info)
    {
        if(isset($args['Id'])){
            $privilege = $this->Models->ShellUserPrivilege->Find($args['Id']);
        }else if(isset($args['ShellUserId']) && isset($args['ShellApplicationId'])){
            $privilege = $this->Models->ShellUserPrivilege->Where(['ShellUserId' => $args['ShellUserId'], 'ShellApplicationId' => $args['ShellApplicationId']])->First();
        }else{
            $privilege = null;
        }

        if($privilege == null){
            return null;
        }

        if($privilege->ShellUser->IsDeleted == 1 || $privilege->ShellApplication->IsDeleted){
            return null;
        }

        if(isset($args['UserLevel'])) {
            $privilege->UserLevel = $args['UserLevel'];
            $privilege->Save();
        }

        return $privilege->Object();
    }
}
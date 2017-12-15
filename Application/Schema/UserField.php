<?php
use \Youshido\GraphQL\Execution\ResolveInfo;
use \Youshido\GraphQL\Config\Field\FieldConfig;
use \Youshido\GraphQL\Type\Scalar\StringType;
use \Youshido\GraphQL\Type\Scalar\IntType;
use \Youshido\GraphQL\Type\NonNullType;

class UserField extends SchemaBaseField {

    public function build(FieldConfig $config){
        $config->addArguments([
            'Id' => new StringType(),
            'Username' => new StringType(),
            'Password' => new StringType(),
            'DisplayName' => new StringType(),
            'IsActive' => new IntType(),
            'IsDeleted' => new IntType()
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
        $id = $args['Id'];
        if($id == null){
            if(!isset($args['Password'])){
                return null;
            }

            $user = $this->Controller->Models->ShellUser->Create($args);
            $user->CreatePassword($args['Password']);

            $user->Save();
            return $user->Object();

        }else{
            $user = $this->Controller->Models->ShellUser->Where(['Id' => $args['Id'], 'IsDeleted' => 0])->First();
            if($user == null){
                return null;
            }

            $this->UpdateNonNullFields($user, $args);
            if(isset($args['Password'])){
                $user->CreatePassword($args['Password']);
            }

            $user->Save();
            return $user->Object();
        }
    }
}
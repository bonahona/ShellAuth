<?php
use \Youshido\GraphQL\Execution\ResolveInfo;
use \Youshido\GraphQL\Config\Field\FieldConfig;
use \Youshido\GraphQL\Type\Scalar\StringType;
use \Youshido\GraphQL\Type\NonNullType;

class LoginField extends SchemaBaseField {

    public function build(FieldConfig $config){
        $config->addArguments([
            'username' => new NonNullType(new StringType()),
            'password' => new NonNullType(new StringType()),
            'application' => new NonNullType(new StringType())
        ]);
    }
    public function getType()
    {
        return new ShellUserPrivilegeType($this->Controller);
    }

    public function getName()
    {
        return 'Login';
    }

    public function resolve($value, array $args, ResolveInfo $info)
    {
        $user = $this->Controller->Models->ShellUser->Where(['Username' => $args['username'], 'IsDeleted' => 0])->First();
        if($user == null){
            return array();
        }

        if(!$user->ValidatePassword($args['password'])){
            return null;
        }

        $application = $this->Controller->Models->ShellApplication->Where(['Name' => $args['application'], 'IsDeleted' => 0])->First();
        if($application == null){
            return null;
        }

        $accessToken = $user->GetAccessToken($application->Id);
        return $accessToken->Object();
    }
}
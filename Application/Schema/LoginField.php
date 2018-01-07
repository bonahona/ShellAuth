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
        return new ShellUserAccessTokenType($this->Controller);
    }

    public function getName()
    {
        return 'Login';
    }

    public function resolve($value, array $args, ResolveInfo $info)
    {
        $user = $this->Controller->Models->ShellUser->Where(['Username' => $args['username'], 'IsDeleted' => 0])->First();
        if($user == null){
            error_log('User not found');
            throw new Exception("Failed to validate user or application");
        }

        if(!$user->ValidatePassword($args['password'])){
            error_log('Failed to validate password');
            throw new Exception("Failed to validate user or application");
        }

        $application = $this->Controller->Models->ShellApplication->Where(['Name' => $args['application'], 'IsDeleted' => 0])->First();
        if($application == null){
            error_log('Failed to find application');
            throw new Exception("Failed to validate user or application");
        }

        $accessToken = $user->GetAccessToken($application->Id);

        $this->Controller->Models->ShellUserActionLog->Create([
            'TimeStamp' =>  date('Y-m-d H:i:s'),
            'ShellUserId' => $user->Id,
            'ShellApplicationId' => $application->Id,
            'ActionName' => 'Successfull login'
        ])->Save();

        return $accessToken->Object();
    }
}
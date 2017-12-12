<?php
use \Youshido\GraphQL\Execution\ResolveInfo;
use \Youshido\GraphQL\Config\Field\FieldConfig;
use \Youshido\GraphQL\Type\Scalar\StringType;
use \Youshido\GraphQL\Type\Scalar\IntType;
use \Youshido\GraphQL\Type\NonNullType;

class UserField extends SchemaBaseField {

    public function build(FieldConfig $config){
        $config->addArguments([
            'username' => new NonNullType(new StringType()),
            'displayName' => new IntType(),
            'password' => new NonNullType(new StringType()),
        ]);
    }
    public function getType()
    {
        return new ShellUserType($this->Models);
    }

    public function getName()
    {
        return 'User';
    }

    public function resolve($value, array $args, ResolveInfo $info)
    {
        $application = $this->Models->ShellApplication->Create([
            'Name' => $args['name'],
            'DefaultUserLevel' => $args['defaultUserLevel'],
            'RsaPublicKey' => $args['rsaPublicKey']
        ]);
        $application->Save();
        return $application->Object();
    }
}
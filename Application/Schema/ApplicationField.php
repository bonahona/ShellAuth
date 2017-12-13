<?php
use \Youshido\GraphQL\Execution\ResolveInfo;
use \Youshido\GraphQL\Config\Field\FieldConfig;
use \Youshido\GraphQL\Type\Scalar\StringType;
use \Youshido\GraphQL\Type\Scalar\IntType;
use \Youshido\GraphQL\Type\NonNullType;

class ApplicationField extends SchemaBaseField {

    public function build(FieldConfig $config){
        $config->addArguments([
            'Id' => new StringType(),
            'Name' => new StringType(),
            'defaultUserLevel' => new IntType(),
            'rsaPublicKey' => new StringType()
        ]);
    }
    public function getType()
    {
        return new ShellApplicationType($this->Models);
    }

    public function getName()
    {
        return 'ShellApplication';
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
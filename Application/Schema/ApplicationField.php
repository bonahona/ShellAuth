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
            'IsActive' => new IntType(),
            'IsDeleted' => new IntType(),
            'DefaultUserLevel' => new IntType(),
            'RsaPublicKey' => new StringType()
        ]);
    }
    public function getType()
    {
        return new ShellApplicationType($this->Controller);
    }

    public function getName()
    {
        return 'ShellApplication';
    }

    public function resolve($value, array $args, ResolveInfo $info)
    {
        if(!$this->Controller->IsAuthorized()){
            throw new Exception('Not authorized', 401);
        }

        $id = $args['Id'];
        if($id == null){
            $application = $this->Controller->Models->ShellApplication->Create($args);
            $application->Save();
            return $application->Object();
        }else{
            $application = $this->Controller->Models->ShellApplication->Where(['Id' => $args['Id'], 'IsDeleted' =>  0])->First();
            if($application == null){
                return null;
            }

            $this->UpdateNonNullFields($application, $args);
            $application->Save();
            return $application->Object();
        }
    }
}
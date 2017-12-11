<?php
use \Youshido\GraphQL\Execution\ResolveInfo;
use \Youshido\GraphQL\Config\Field\FieldConfig;
use \Youshido\GraphQL\Type\Scalar\StringType;

class ShellUserField extends SchemaBaseField {

    public function build(FieldConfig $config){
        $config->addArguments([
            'id' => new StringType()
        ]);
    }
    public function getType()
    {
        return new ShellUserType($this->Models);
    }

    public function getName()
    {
        return 'ShellUser';
    }

    public function resolve($value, array $args, ResolveInfo $info)
    {

        $model = $this->Models->ShellUser->Where(['id' => $args['id'], 'IsDeleted' => 0])->First();
        if($model == null){
            return null;
        }else{
            return $model->Object();
        }
    }
}
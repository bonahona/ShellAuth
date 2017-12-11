<?php
use \Youshido\GraphQL\Execution\ResolveInfo;
use \Youshido\GraphQL\Config\Field\FieldConfig;
use \Youshido\GraphQL\Type\Scalar\StringType;

class ShellApplicationField extends SchemaBaseField {

    public function build(FieldConfig $config){
        $config->addArguments([
            'id' => new StringType()
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
        $model = $this->Models->ShellApplication->Where(['id' => $args['id'], 'IsDeleted' => 0])->First();
        if($model == null){
            return null;
        }else{
            $result = $model->Object();
            return $result;
        }
    }
}
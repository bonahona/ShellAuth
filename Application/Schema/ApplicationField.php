<?php
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;

class ApplicationField extends AbstractField
{
    public function getType()
    {
        return new ApplicationObjectType();
    }

    public function resolve($value, array $args, ResolveInfo $info)
    {
        return [
            'id' => '01',
            'name' => 'test01',
            'isInactive' => false,
            'defaultUserLevel' => 0,
            'rsaPublicKey' => "test"
        ];
    }
}
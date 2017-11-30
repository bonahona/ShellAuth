<?php
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;

class UserField extends AbstractField
{
    public function getType()
    {
        return new UserObjectType();
    }

    public function resolve($value, array $args, ResolveInfo $info)
    {
        return [
            'id' => '01',
            'name' => 'user'
        ];
    }
}
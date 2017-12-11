<?php
use \Youshido\GraphQL\Type\Object\AbstractObjectType;
use \Youshido\GraphQL\Type\Scalar\StringType;
use \Youshido\GraphQL\Type\Scalar\BooleanType;
use \Youshido\GraphQL\Type\Scalar\IntType;
use \Youshido\GraphQL\Type\ListType\ListType;

class ShellUserPrivilegesField extends SchemaBaseField
{
    public function getType()
    {
        return new ListType(new ShellUserPrivilegeType($this->Models));
    }

    public function getName()
    {
        return 'Privileges';
    }

    public function resolve($value, array $args, ResolveInfo $info)
    {
    }
}
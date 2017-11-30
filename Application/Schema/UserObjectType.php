<?php
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;

class UserObjectType extends AbstractObjectType
{
    public function build($config)
    {
        $config
            ->addField('id', new StringType())
            ->addField('name', new StringType());

    }

    public function getName()
    {
        return 'User';
    }
}
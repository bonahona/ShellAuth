<?php
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\BooleanType;
use Youshido\GraphQL\Type\Scalar\IntType;

class ApplicationObjectType extends AbstractObjectType
{
    public function build($config)
    {
        $config->addFields([
            'id' => new StringType(),
            'name' => new StringType(),
            'isInactive' => new BooleanType(),
            'defaultUserLevel' => new IntType(),
            'rsaPublicKey' => new StringType()
        ]);
    }

    public function getName()
    {
        return 'Application';
    }
}
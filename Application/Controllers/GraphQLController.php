<?php
use Youshido\GraphQL\Schema\Schema;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Execution\Processor;
use \Youshido\GraphQL\Type\Scalar\StringType;
use \Youshido\GraphQL\Type\Scalar\IntType;
use \Youshido\GraphQL\Type\ListType\ListType;

require_once('./Application/Schema/SchemaBaseField.php');
require_once('./Application/Schema/SchemaBaseType.php');
require_once('./Application/Schema/ShellUserType.php');
require_once('./Application/Schema/ShellApplicationType.php');
require_once('./Application/Schema/ShellUserPrivilegeType.php');
require_once('./Application/Schema/ShellUserAccessTokenType.php');
require_once('./Application/Schema/ShellUserActionLogType.php');
require_once('./Application/Schema/LoginField.php');
require_once('./Application/Schema/UserField.php');
require_once('./Application/Schema/ApplicationField.php');
class GraphQLController extends Controller
{
    public $ApplicationSchema;

    protected function GetQueryString()
    {
        $parsedBody = $this->GetBody();
        $payload = json_decode($parsedBody, true);

        if(!isset($payload['query'])){
            return false;
        }else{
            return $payload['query'];
        }
    }
    public function Index()
    {
        $processor = new Processor(new Schema([
            'query' => new ObjectType([
                'name' => 'RootQueryType',
                'fields' => [
                    'ShellUser' => [
                        'type' => new ShellUserType($this->Models),
                        'args' => [
                          'id' => new StringType()
                        ],
                        'resolve' => function($value, $args, $info){
                            $model = $this->Models->ShellUser->Where(['id' => $args['id'], 'IsDeleted' => 0])->First();
                            if($model == null){
                                return null;
                            }else{
                                return $model->Object();
                            }
                        }
                    ],
                    'ShellApplication' => [
                        'type' => new ShellApplicationType($this->Models),
                        'args' => [
                            'id' => new StringType()
                        ],
                        'resolve' => function($value, $args, $info){
                            $model = $this->Models->ShellApplication->Where(['id' => $args['id'], 'IsDeleted' => 0])->First();
                            if($model == null){
                                return null;
                            }else{
                                $result = $model->Object();
                                return $result;
                            }
                        },
                        'ShellUsers' => [
                            'type' => new ListType(new ShellUserType($this->Models)),
                            'resolve' => function($value, $args, $info){
                                $result = array();
                                foreach($this->Models->ShellUser->Where(['IsDeleted' => 0]) as $user){
                                    $result[] = $user->Object();
                                }
                            }
                        ],
                        'ShellApplications' => [
                            'type' => new ListType(new ShellApplicationType($this->Models)),
                            'resolve' => function($value, $args, $info){
                                $result = array();
                                foreach($this->Models->ShellApplication->Where(['IsDeleted' => 0]) as $application){
                                    $result[] = $application->Object();
                                }
                            }
                        ]
                    ]
                ]
            ]),
            'mutation' => new ObjectType([
                'name' => 'RootMutationType',
                'fields' => [
                    'Login' => new LoginField($this->Models),
                    'ShellApplication' => new ApplicationField($this->Models),
                    'ShellUser' => new UserField($this->Models)
                ]
            ])
        ]));

        $queryString = $this->GetQueryString();
        return $this->Json($processor->processPayload($queryString)->getResponseData());
    }
}
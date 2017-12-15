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
    public $AuthToken;
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

    protected function ParseAuthContext()
    {
        $headers = getallheaders();
        if(!isset($headers['Authorization'])){
            return null;
        }

        $authHeader = $headers['Authorization'];
        $authTokenSplit = explode(':', $authHeader);
        if(count($authTokenSplit) < 2){
            return null;
        }

        $authToken = trim($authTokenSplit[1]);
        $authUser = $this->Models->ShellUserAccessToken->Where(['Guid' => $authToken])->First();
        return $authUser;
    }

    public function Index()
    {
        $processor = new Processor(new Schema([
            'query' => new ObjectType([
                'name' => 'RootQueryType',
                'fields' => [
                    'ShellUser' => [
                        'type' => new ShellUserType($this),
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
                        'type' => new ShellApplicationType($this),
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
                        }
                    ],
                    'ShellApplications' => [
                        'type' => new ListType(new ShellApplicationType($this)),
                        'name' => 'ShellApplications',
                        'resolve' => function($value, $args, $info){
                            $result = array();
                            foreach($this->Models->ShellApplication->Where(['IsDeleted' => 0]) as $application){
                                $result[] = $application->Object();
                            }
                            return $result;
                        }
                    ],
                    'ShellUsers' => [
                        'type' => new ListType(new ShellUserType($this)),
                        'name' => 'ShellUsers',
                        'resolve' => function($value, $args, $info){
                            $result = array();
                            foreach($this->Models->ShellUser->Where(['IsDeleted' => 0]) as $application){
                                $result[] = $application->Object();
                            }
                            return $result;
                        }
                    ]
                ]
            ]),
            'mutation' => new ObjectType([
                'name' => 'RootMutationType',
                'fields' => [
                    'Login' => new LoginField($this),
                    'ShellApplication' => new ApplicationField($this),
                    'ShellUser' => new UserField($this)
                ]
            ])
        ]));

        $this->AuthToken = $this->ParseAuthContext();
        $queryString = $this->GetQueryString();
        return $this->Json($processor->processPayload($queryString)->getResponseData());
    }
}
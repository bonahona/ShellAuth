<?php
use Youshido\GraphQL\Schema\Schema;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Execution\Processor;

require_once('./Application/Schema/SchemaBaseField.php');
require_once('./Application/Schema/SchemaBaseType.php');
require_once('./Application/Schema/ShellUserType.php');
require_once('./Application/Schema/ShellUserField.php');
require_once('./Application/Schema/ShellApplicationType.php');
require_once('./Application/Schema/ShellApplicationField.php');
require_once('./Application/Schema/ShellUserPrivilegeType.php');
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
                    'ShellUser' => new ShellUserField($this->Models),
                    'ShellApplication' => new ShellApplicationField($this->Models)
                ]
            ])
        ]));

        $queryString = $this->GetQueryString();
        return $this->Json($processor->processPayload($queryString)->getResponseData());
    }
}
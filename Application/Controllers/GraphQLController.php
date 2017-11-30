<?php
use Youshido\GraphQL\Schema\Schema as Schema;
use Youshido\GraphQL\Type\Object\ObjectType as ObjectType;
use Youshido\GraphQL\Execution\Processor as Processor;


require_once('./Application/Schema/UserObjectType.php');
require_once('./Application/Schema/UserField.php');
require_once('./Application/Schema/ApplicationObjectType.php');
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
        $rootQueryType = new ObjectType([
            'fields' => [
                'name' => 'RootQueryType',
                'fields' => [
                    new ApplicationField()
                ]
            ]
        ]);

        $processor = new Processor(new Schema([
            'query' => $rootQueryType
        ]));

        return $this->Text($this->Getbody());

        /*
        $queryString = $this->GetQueryString();
        $queryString = '{ application { id, name } }';
        return $this->Json($processor->processPayload($queryString)->getResponseData());
        */
    }
}
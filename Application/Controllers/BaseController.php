<?php
class BaseController extends Controller
{
    public $PayLoad;

    protected function ValidateApplication()
    {
        $jsonData = $this->Data['data'];
        $parsedData = json_decode($jsonData, true);

        if($parsedData == null){
            return null;
        }

        if(!isset($parsedData['ShellAuth']['Application'])){
            return null;
        }

        $applicationData = $parsedData['ShellAuth']['Application'];
        if(!isset($applicationData['ApplicationName'])){
            return null;
        }

        $payLoad = $parsedData['ShellAuth']['PayLoad'];
        $this->PayLoad = $payLoad;

        $application = $this->Models->ShellApplication->Where(array('ApplicationName' => $applicationData['ApplicationName'], 'IsDeleted' => 0, 'IsInactive' => 0))->First();
        return $application;
    }

    protected function Response($data = null)
    {
        return $this->Json(array('Error' => 0, 'ErrorList' => array(), 'Data' => $data));
    }

    protected function Error($errors){

        if(is_array($errors)){
            $errorList = $errors;
        }else{
            $errorList = array($errors);
        }

        return $this->Json(array('Error' => 1, 'ErrorList' => $errorList));
    }

    protected function InvalidJson()
    {
        $errorList = array('Not valid JSON in request');
        $this->Json(array('Error' => 1, 'ErrorList' => $errorList));
    }

    protected function InvalidApplication()
    {
        $errorList = array('Invalid application');
        $this->Json(array('Error' => 1, 'ErrorList' => $errorList));
    }
}
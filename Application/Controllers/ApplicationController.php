<?php
require_once('BaseController.php');
class ApplicationController extends BaseController
{
    public function GenerateRsaKeyPair()
    {
        $config = array(
            'config' => 'C:\wamp\bin\php\php5.5.12\extras\ssl',
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );

        $keyPair = $res = openssl_pkey_new($config);

        var_dump(openssl_error_string());
        var_dump($keyPair);

        openssl_pkey_export($keyPair, $privateKey);

        $publicKey = openssl_pkey_get_details($keyPair);
        $publicKey = $publicKey["key"];

        var_dump(array(
            'Pub' => $publicKey,
            'Prv' => $privateKey
        ));
    }

    public function Create()
    {
        $application = $this->ValidateApplication();
        if($application == null){
            return $this->InvalidApplication();
        }

        $applicationName = $this->PayLoad['ShellApplication']['ApplicationName'];

        // Check if the user exists
        if($this->Models->ShellApplication->Any(array('ApplicationName' => $applicationName))){
            return $this->Error('Application name does already exist');
        }

        $shellApplication = $this->Models->ShellApplication->Create();
        $shellApplication->ApplicationName = $applicationName;
        $shellApplication->RsaPublicKey = $this->PayLoad['ShellApplication']['RsaPublicKey'];
        $shellApplication->RsaPrivateKey = $this->PayLoad['ShellApplication']['RsaPrivateKey'];
        $shellApplication->IsInactive = $this->PayLoad['ShellApplication']['IsInactive'];
		if($shellApplication->IsInactive == null){
			$shellApplication->IsInactive = 0;
		}

		$shellApplication->IsDeleted = 0;
        $shellApplication->DefaultUserLevel = $this->PayLoad['ShellApplication']['DefaultUserLevel'];
		
		print_r($shellApplication->Properties);
        $shellApplication->Save();

        return $this->Response($shellApplication->Clean());
    }

    public function Edit()
    {
        $application = $this->ValidateApplication();
        if($application == null){
            return $this->InvalidApplication();
        }

        $shellApplicationId = $this->PayLoad['ShellApplication']['Id'];
        $shellApplication = $this->Models->ShellApplication->Find($shellApplicationId);

        if($shellApplication == null){
            return $this->Error('Application not found');
        }

        $shellApplication->ApplicationName = $this->PayLoad['ShellApplication']['ApplicationName'];;
        $shellApplication->RsaPublicKey = $this->PayLoad['ShellApplication']['RsaPublicKey'];
        $shellApplication->DefaultUserLevel = $this->PayLoad['ShellApplication']['DefaultUserLevel'];

        if(isset($this->PayLoad['ShellApplication']['IsInactive'])){
            $shellApplication->IsInactive = $this->PayLoad['ShellApplication']['IsInactive'];
        }else{
            $shellApplication->IsInactive = 0;
        }

        $shellApplication->Save();

        return $this->Response($shellApplication->Clean());
    }

    public function Delete()
    {
        $application = $this->ValidateApplication();
        if($application == null){
            return $this->InvalidApplication();
        }

        $shellApplicationId = $this->PayLoad;
        $shellApplication = $this->Models->ShellApplication->Find($shellApplicationId);

        if($shellApplication == null){
            return $this->Error('Application not found');
        }else{

            $shellApplication->IsDeleted = 1;
            $shellApplication->Save();

            return $this->Response($application->Clean());
        }
    }

    public function Get()
    {
        $application = $this->ValidateApplication();
        if($application == null){
            return $this->InvalidApplication();
        }

        if(isset($this->PayLoad['Id'])){
            $id = $this->PayLoad['Id'];
        }else{
            $id = null;
        }

        if($id == null){
            $result = array();
            foreach($this->Models->ShellApplication->Where(array('IsDeleted' => 0)) as $application){
                $result[] = $application->Clean();
            }

            return $this->Response($result);
        }else{
            $shellApplication = $this->Models->ShellApplication->Where(array('IsDeleted' => 0, 'Id' => $id))->First();

            if($shellApplication != null){
                return $this->Response(array($shellApplication->Clean()));
            }else{
                return $this->Error('Application Id not found');
            }
        }
    }
}
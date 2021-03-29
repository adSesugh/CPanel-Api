<?php

namespace Keensoen\CPanelApi;

use Config;

class CPanel
{
    protected $config;
    protected $protocol;
    protected $domain;
    protected $port;
    protected $username;
    protected $token;


    //-----------------------------------------------------

    public function __construct($cpanel_domain=null, $cpanel_api_token=null, $cpanel_username=null, $protocol='https', $port=2083)
    {

        $this->config = Config::get('cpanelapi');

        if(isset($cpanel_domain))
        {
            $this->protocol = $protocol;
            $this->port = $port;
            $this->domain = $cpanel_domain;
            $this->username = $cpanel_username;
            $this->token = $cpanel_api_token;
        } else{
            $this->protocol = $this->config['protocol'];
            $this->domain = $this->config['domain'];
            $this->port = $this->config['port'];
            $this->username = $this->config['username'];
            $this->token = $this->config['api_token'];
        }


    }

    //-----------------------------------------------------

    public function getEmailAccounts() 
    {
        $module = "Email";
        $function = "list_pops";
        $parameters = array();
        return $this->call($module, $function, $parameters);
    }

    //-----------------------------------------------------

    public function createEmailAccount($username, $password) 
    {
        $module = 'Email';
        $function = 'add_pop';
        $parameters = array(
            'email' =>  $username,
            'password'  => $password,
            'quota' =>  '1024'
        );

        return $this->call($module, $function, $parameters);
    }

    //-----------------------------------------------------

    public function deleteEmailAccount($email)
    {
        $module = 'Email';
        $function = 'delete_pop';
        $parameters = array(
            'email' =>  $email
        );

        return $this->call($module, $function, $parameters);
    }

    //-----------------------------------------------------

    public function increaseQuota($email, $quota)
    {
        $module = 'Email';
        $function = 'edit_pop_quota';
        $parameters = array(
            'email' => $email, 
            'quota' =>  $quota
        );

        return $this->call($module, $function, $parameters);
    }

    //-----------------------------------------------------

    public function getDiskUsage($email)
    {
        $module = 'Email';
        $function = 'get_disk_usage';
        $parameters = array(
            'user'  =>  $email
        );

        return $this->call($module, $function, $parameters);
    }

    //-----------------------------------------------------

    public function createSubDomain($subdomain, $rootdomain, $dir)
    {
        $module = "SubDomain";
        $function = "addsubdomain";
        $parameters = array(
            'domain'        => $subdomain,
            'rootdomain'    => $rootdomain,
            'canoff'        => 0,
            'dir'           => $dir,
            'disallowdot'   => 0
        );
        return $this->call($module, $function, $parameters);
    }
    //-----------------------------------------------------

    //-----------------------------------------------------

    public function createDatabase($database_name)
    {
        $module = "Mysql";
        $function = "create_database";
        $parameters = array(
            'name'    => $database_name
        );
        return $this->call($module, $function, $parameters);
    }

    //-----------------------------------------------------

    public function deleteDatabase($database_name)
    {
        $module = "Mysql";
        $function = "delete_database";
        $parameters = array(
            'name'    => $database_name
        );
        return $this->call($module, $function, $parameters);
    }

    //-----------------------------------------------------

    public function listDatabases()
    {
        $module = "Mysql";
        $function = "list_databases";
        $parameters = array(
        );
        return $this->call($module, $function, $parameters);
    }

    //-----------------------------------------------------

    public function createDatabaseUser($username, $password)
    {
        $module = "Mysql";
        $function = "create_user";
        $parameters = array(
            'name'    => $username,
            'password'    => $password,
        );
        return $this->call($module, $function, $parameters);
    }

    //-----------------------------------------------------

    public function deleteDatabaseUser($username)
    {
        $module = "Mysql";
        $function = "delete_database";
        $parameters = array(
            'name'    => $username
        );
        return $this->call($module, $function, $parameters);
    }

    //-----------------------------------------------------

    public function setAllPrivilegesOnDatabase($database_user, $database_name)
    {
        $module = "Mysql";
        $function = "set_privileges_on_database";
        $parameters = array(
            'user'    => $database_user,
            'database'    => $database_name,
            'privileges'    => 'ALL PRIVILEGES',
        );
        return $this->call($module, $function, $parameters);
    }

    //-----------------------------------------------------

    //-----------------------------------------------------
    public function callUAPI($Module, $function, $parameters_array = array())
    {
        return $this->call($Module, $function, $parameters_array);
    }

    //-----------------------------------------------------

    public function call($module, $function, $args = array())
    {
        $parameters = '';
        if ( count($args) > 0 ) {
            foreach( $args as $key => $value ) {
                $parameters .= '&' . $key . '=' . $value;
            }
        }

        $url = $this->protocol.'://'.$this->domain . ':' . $this->port . '/execute/' . $module;
        $url .= "/".$function;

        if(count($args) > 0)
        {
            $url .= '?'. $parameters;
        }

        $headers = array(
            "Authorization: cpanel " . $this->username . ':' . $this->token,
            "cache-control: no-cache"
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => $this->port,
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);


        if ($err) {

            $response['status'] = 'failed';
            $response['errors'] = $err;
            $response['inputs']['url'] = $url;

        } else {

            $res = json_decode($response);

            $response = [];
            if(isset($res) && isset($res->status) && $res->status == 0)
            {
                $response['status'] = 'failed';
                $response['errors'][] = $res->errors;
                $response['inputs']['url'] = $url;
            } else
            {
                $response['status'] = 'success';
                $response['data'] = $res;
                $response['inputs']['url'] = $url;
            }
        }

        return $response;
    }

    //-----------------------------------------------------
}

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * One Signal php server work
 *
 * Notification library for PHP
 *
 * @package		One signal
 * @author		Zcodia Tech (http://zcodia.com/)
 * @version		1.0.1
 * @license		MIT License Copyright (c) 2008 Erick Hartanto
 */
class Onesignal {

    private $error = array();
    public $ci = '';
    public $app_id = '';
    public $url = '';
    public $auth = '';
    public $dev_mode = false;

    function __construct() { 
        $this->ci = & get_instance();

        $this->ci->load->config('onesignal', TRUE);
        $this->ci->load->library(array('custom_curl'));

        //configratin 
        $this->app_id = $this->ci->config->item('app_id', 'onesignal');
        $this->auth = $this->ci->config->item('authorization', 'onesignal');
        $this->dev_mode = $this->ci->config->item('debug_mode', 'onesignal');
    }

    function send_notification($message, $data, $tags = FALSE,$schdule = false, $app_id = false, $auth = false) {
        if ($this->dev_mode)
            $this->ci->loglib->logall(array('class' => $this->ci->router->fetch_class(), 'method' => $this->ci->router->fetch_method(), 'data' => 'Onesignal Starts.....'));
        if (isset($app_id) && $app_id != '') {
            $this->app_id = $app_id;
        }
        if (isset($auth) && $auth != '') {
            $this->auth = $auth;
        }
        $content = array(
            "en" => $message,
        );
        $heading = array(
            "en" => 'Motorsports TV',
        );
        $subtitle = array(
            "en" => 'Motorsports TV | 24/7 Motorsports',
        );
        // $big_picture = base_url().'/assets/theme/dist/img/MTV-Logo-200w.png';
        $tag_array = array();
        if (is_array($tags) && !empty($tags)) {
            foreach ($tags as $tag) {
                if(isset($tag['relation']) && $tag['relation'] !='' && isset($tag['key']) && $tag['key'] !='' && isset($tag['value']) && $tag['value'] !=''){
                    $tag_array[] = array(
                        "field" => "tag",
                        "relation" => $tag['relation'],
                        "key" => $tag['key'],
                        "value" => $tag['value'],
                    );
                    $tag_array[] = array("operator" => "OR");
                }
            }
            array_pop($tags);
        } 
        
        $fields = array(
            'app_id' => $this->app_id,
            'data' => $data,
            'contents' => $content,
            'included_segments' => "Active Users",
        );
        if($schdule && !empty($schdule)){
            if(isset($schdule['send_after']))
                $fields['send_after'] = $schdule['send_after'];
            if(isset($schdule['delayed_option']))
                $fields['delayed_option'] = $schdule['delayed_option'];
            if(isset($schdule['delivery_time_of_day']))
                $fields['delivery_time_of_day'] = $schdule['delivery_time_of_day'];
        }
        if(!empty($tag_array)){
            $fields['tags'] = $tag_array;
        }
        if(($heading)){
            $fields['headings'] = $heading;
        }
        if(($subtitle)){
            $fields['subtitle'] = $subtitle;
        }
        // if(($big_picture)){
        //     $fields['big_picture'] = $big_picture;
        // }
        
        // Simple call to CI URI
        $this->ci->custom_curl->create('https://onesignal.com/api/v1/notifications');
        $this->ci->custom_curl->post(json_encode($fields));
        $this->ci->custom_curl->ssl(FALSE);
        $this->ci->custom_curl->http_header("Authorization", "Basic " . $this->auth);
        $this->ci->custom_curl->http_header('Content-Type', 'application/json');

        $res = $this->ci->custom_curl->execute();
        // $this->ci->custom_curl->debug();
        // echo '<pre>';print_r($fields);
        // echo '<pre>';print_r($res);
        // die;
        if (!$res) {
            if ($this->dev_mode)
                $this->ci->loglib->logall(array('class' => $this->ci->router->fetch_class(), 'method' => $this->ci->router->fetch_method(), 'data' => 'Onesignal No Response.....'));
            return false;
        }
        if ($this->dev_mode)
            $this->ci->loglib->logall(array('class' => $this->ci->router->fetch_class(), 'method' => $this->ci->router->fetch_method(), 'data' => 'Onesignal Success.....', 'message' => $fields, 'responce' => $res));
        $response = json_decode($res,true);
        return $response;
    }

    function cancel_notification($id, $app_id = false, $auth = false){
        if ($this->dev_mode)
            $this->ci->loglib->logall(array('class' => $this->ci->router->fetch_class(), 'method' => $this->ci->router->fetch_method(), 'data' => 'Onesignal Starts.....'));
        if (isset($app_id) && $app_id != '') {
            $this->app_id = $app_id;
        }
        if (isset($auth) && $auth != '') {
            $this->auth = $auth;
        }
        $fields = array(
            'app_id' => $this->app_id,
        );

         // Simple call to CI URI
         $this->ci->custom_curl->create('https://onesignal.com/api/v1/notifications/'.$id);
         $this->ci->custom_curl->delete($fields);
         $this->ci->custom_curl->ssl(FALSE);
         $this->ci->custom_curl->http_header("Authorization", "Basic " . $this->auth);
        //  $this->ci->custom_curl->http_header('Content-Type', 'application/json');
 
         $res = $this->ci->custom_curl->execute();
         if (!$res) {
             if ($this->dev_mode)
                 $this->ci->loglib->logall(array('class' => $this->ci->router->fetch_class(), 'method' => $this->ci->router->fetch_method(), 'data' => 'Onesignal No Response.....'));
             return false;
         }
         if ($this->dev_mode)
             $this->ci->loglib->logall(array('class' => $this->ci->router->fetch_class(), 'method' => $this->ci->router->fetch_method(), 'data' => 'Onesignal Success.....', 'message' => $fields, 'responce' => $res));
         $response = json_decode($res,true);
         if(!empty($response) && isset($response['success']) && $response['success']==true){
             return true;
         }else{
             return false;
         }
    }

}

/* End of file onesignal.php */
/* Location: ./application/libraries/Onesignal.php */
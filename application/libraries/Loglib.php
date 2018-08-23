<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FileUpload
 *
 * @author Administrator
 */
class Loglib {

    public $CI;
    public $currentdate = '';
    public $rootfolder = 'uploads/log';
    public $file = 'log';

    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->helper(array("date"));
        $this->currentdate = mdate('%y%n%d', '');
//        $this->rootfolder = APPPATH.'cust-log/';

        //check log folder for today
        $this->_check_folder($this->rootfolder);
    }

    public function logall($test) {
        $this->file = $this->rootfolder . '/' . $this->file . $this->currentdate . '.txt';
        // create dummy array for example
        $array['data'] = $test;
        $array['currenttime'] = mdate('%y%n%d %h:%i:%a (%s sec)', '');

// turn on output buffering
        ob_start();

// use var_dump to output the contents of the array/object
        print_r($array);

// store current output buffer contents and delete buffer
        $string = ob_get_clean();

// pipe the value of $string to the error_log() function
        if (file_exists($this->file)) {
            $current = file_get_contents($this->file);
            $current = $string . "\n" .$current;
            
        } else {
            $current = $string . "\n";
        }
        file_put_contents($this->file, $current);
    }

    //to create folder if not exiest
    function _check_folder($folder_name) {
        if (!file_exists($folder_name)) {
            mkdir($folder_name, 0777, true);
        }
    }

    public function check_and_rename($old_name, $new_name) {
        if (file_exists($old_name)) {
            //rename here
            rename($old_name, $new_name);
        } else {
            //null
            return false;
        }
        return true;
    }

}

?>

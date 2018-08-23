##Onesignal implementation with codeigniter
this is the repo that have onesignal as a library,
this library using the v1/notifications of onesignal reference: https://documentation.onesignal.com/reference

#to use this do follow the following steps
clone this repo, and copy past the files with the project folder..

1) on Application->config->onesignal.php you can config your onesignal account details 
    -> enable the debug flag to log the response with log file (Log file located in (root of project)/uploads/log/(todaydate.txt))
2) include this lib on your autoload.php file
3) now your application is ready to use the onesignal portal to send the notification..
   
    Simple send notification
    ```$this->onesignal->send_notification("this will be showed in notificaion", array('item1' => 'this is additional params'));```
 
    Simple send notification to that device what and all has the tags attached
    ```$this->onesignal->send_notification("this will be showed in notificaion", array('item1' => 'this is additional params'),array(array('relation' = ' = ','key' = 'Device','value' = 'Android')));```
    for more details about the tags and its params refer the onesignal documentation

    Simple send notification that can be schduled in follwoing step
    ```$this->onesignal->send_notification("this will be showed in notificaion", array('item1' => 'this is additional params'),array(array('send_after' = ' 2015-09-24 14:00:00 GMT-0700 ','delayed_option' = 'timezone','delivery_time_of_day' = '9am')));```

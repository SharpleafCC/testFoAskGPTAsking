<?php

class AIOWPS_SMART_404_General_Init_Tasks
{
    public function __construct()
    {
        global $aio_wp_security_premium;
        if($aio_wp_security_premium->configs->get_value('aiowps_enable_smart_404') == '1'){
            if (class_exists('AIOWPSecurity_Blocking')) {
                if (!is_user_logged_in() || !current_user_can('administrator') || !is_admin()) {
                    AIOWPSecurity_Blocking::check_visitor_ip_and_perform_blocking();
                }
            }
        }
        //add_action ('aiowps_hourly_cron_event', array($this, 'smart404_hourly_cron_event_handler'));

        add_action('wp_head',array($this, 'do_head_tasks'));
        //$this->do_smart_404_general_tasks();
        
        //Add more tasks that need to be executed at init time
        
    }


    public function do_head_tasks()
    {
        global $aio_wp_security, $aio_wp_security_premium;
        if($aio_wp_security_premium->configs->get_value('aiowps_enable_smart_404') == '1' || $aio_wp_security_premium->configs->get_value('aiowps_enable_instant_404_string_block') == '1'){
            $aio_wp_security_premium->smart_404_tasks_obj->do_smart_404_general_tasks();
        }
    }
    
    public function smart404_hourly_cron_event_handler()
    {
        global $wpdb;
        $blocked_table = AIOWPSEC_TBL_PERM_BLOCK;
        $now = current_time( 'mysql' );
        
        $unblock_time_length_hrs = '1';
        $newtimestamp = strtotime($lock_time.' + '.$lock_minutes.' minute');
        $release_time = date('Y-m-d H:i:s', $newtimestamp);
                
        $blocked_ips = $wpdb->get_results("SELECT * FROM $blocked_table " . 
                                "WHERE blocked_date + INTERVAL " .
                                $unblock_time_length_hrs . " HOUR < '".$now."'", ARRAY_A);

//error_log(print_r($blocked_ips, true), 3, dirname( __FILE__ ).'/post.log' );
        return;
        
    }

}

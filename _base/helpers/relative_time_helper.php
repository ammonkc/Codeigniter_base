<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 2009-09-30 ammon
 * Helps with relative time
 * http://forrst.com/posts/Relative_time_helper_for_CodeIgniter-BVc
 */
 
function relative_time($datetime)
    {
        $CI =& get_instance();
        $CI->lang->load('date');
        
        if(!is_numeric($datetime))
        {
            $val = explode(" ",$datetime);
           $date = explode("-",$val[0]);
           $time = explode(":",$val[1]);
           $datetime = mktime($time[0],$time[1],$time[2],$date[1],$date[2],$date[0]);
        }
        
        $difference = time() - $datetime;
        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60","60","24","7","4.35","12","10");

        if ($difference > 0) 
        { 
            $ending = $CI->lang->line('date_ago');
        } 
        else 
        { 
            $difference = -$difference;
            $ending = $CI->lang->line('date_to_go');
        }
        for($j = 0; $difference >= $lengths[$j]; $j++)
        {
            $difference /= $lengths[$j];
        } 
        $difference = round($difference);
        
        if($difference != 1) 
        { 
            $period = strtolower($CI->lang->line('date_'.$periods[$j].'s'));
        } else {
            $period = strtolower($CI->lang->line('date_'.$periods[$j]));
        }
        
        return "$difference $period $ending";
    }
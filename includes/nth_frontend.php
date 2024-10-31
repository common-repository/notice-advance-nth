<?php
class NTHFrontend
{   
    public function __construct(){   
        //add_action( 'template_redirect', 'alert_advance_init' );
        add_action( 'template_redirect', array( $this, 'alert_advance_init' ) );
    }   
    public function alert_advance_init(){
    $val=get_option('nth_option', array());
    $active=isset($val['nth_av_active'])?$val['nth_av_active']:"0";
        if($active=="1")
        {
            $today= date("m/d/Y");
            $fromdate=empty($val['nth_av_fromdate'])?"":esc_attr($val['nth_av_fromdate']);
            $todate=empty($val['nth_av_todate'])?$fromdate:esc_attr($val['nth_av_todate']);
            if($fromdate <= $today && $todate >= $today)
            {
                $this->show_notice_bar($val);
            } 
            else if(empty($fromdate) && empty($todate))
            {
               $this->show_notice_bar($val);
            }
        }   
    }
    public function show_notice_bar($val)
    {
                $av_text=$val['nth_av_text'];
                $av_position=esc_attr( $val['nth_av_position'])!=""?esc_attr( $val['nth_av_position']):"bottom";
                $av_BgColor=esc_attr( $val['nth_av_bgcolor'])?esc_attr( $val['nth_av_bgcolor']):"#000000";
                $av_FontColor=esc_attr( $val['nth_av_fontcolor'])?esc_attr( $val['nth_av_fontcolor']):"#ffffff";
                $av_trans=esc_attr( $val['nth_av_trans'])!=""?esc_attr( $val['nth_av_trans']):"100";
                $av_height=esc_attr( $val['nth_av_height'])!=""?esc_attr( $val['nth_av_height']):"30";
                $av_trans1=$av_trans/100;
                if($av_position=='top' && is_admin_bar_showing())
                {
                   $av_position='top:32px';         
                }
                else if($av_position=='top' && !is_admin_bar_showing())
                {
                   $av_position='top:0';        
                }
                else
                {
                   $av_position='bottom:0';           
                }
                echo '<div id="alertContent" style="'.$av_position.';background:'.$av_BgColor.';opacity:'.$av_trans1.';filter: alpha(opacity='.$av_trans.');height:'.$av_height.'px;line-height:'.$av_height.'px;">';
                echo '<div class="alertText" style="color:'.$av_FontColor.';">';
                echo html_entity_decode($av_text);
                echo '</div>';
                echo '</div>';
    }
    
}
?>
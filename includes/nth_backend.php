<?php

class NTHBackend
{
private $_menuSlug = 'alert-advance-nth';
private $_setting_option;
   public function __construct(){
    
        $this->_setting_option = get_option('nth_option', array());
        add_action('admin_menu', array($this, 'settingMenu'));
        add_action('admin_init', array($this, 'register_setting_and_field'));
    }   
    
    public function register_setting_and_field(){
        register_setting(
                       'nth_setting_options', 
                       'nth_option', 
                       array($this, 'validate_setting')
               );
     $main_section='nth_admin_main_section';
     add_settings_section($main_section, 'Properties', '', $this->_menuSlug);
    
     //Add active Field
     add_settings_field( 'nth_av_active', 'Status', array( $this, 'av_Active_field' ), $this->_menuSlug,$main_section );
     //Add from date Field
     add_settings_field( 'nth_av_fromdate', 'From Date', array( $this, 'av_FromDate_field' ), $this->_menuSlug,$main_section ); 
     //Add to date Field
      add_settings_field( 'nth_av_todate', 'To Date',array( $this, 'av_ToDate_field' ), $this->_menuSlug,$main_section ); 
     //Add text Field
     add_settings_field( 'nth_av_text', 'Alert Text', array( $this, 'av_Text_field' ), $this->_menuSlug,$main_section ); 
     //Add Posistion Field
     add_settings_field( 'nth_av_position', 'Position', array( $this, 'av_Position_field' ),$this->_menuSlug,$main_section );
     //Add Transparent Field  
     add_settings_field( 'nth_av_trans', 'Transparent', array( $this, 'av_Trans_field' ),$this->_menuSlug,$main_section );
     //Add height Field  
     add_settings_field( 'nth_av_height', 'Height of bar', array( $this, 'av_Height_field' ), $this->_menuSlug,$main_section );
     //Add Background Color Field  
     add_settings_field( 'nth_av_bgcolor', 'Background Color', array( $this, 'av_BgColor_field' ), $this->_menuSlug,$main_section ); 
     //Add Font Color Field  
     add_settings_field( 'nth_av_fontcolor', 'Font Color', array( $this, 'av_FontColor_field' ), $this->_menuSlug,$main_section ); 
        
     
    }
    public function validate_setting($data_input){
        $arrErrors = array();
        $today= date("m/d/Y"); 
        $fromdate=esc_attr($data_input['nth_av_fromdate']);
        $todate=esc_attr($data_input['nth_av_todate']);
        if(!empty($fromdate))
        {
            if($this->validateDate($fromdate))
            {
                if(!empty($todate))
                {
                   if($this->validateDate($todate))
                   {
                       if($todate < $today)
                       {
                            $arrErrors['nth_av_todate'] = "Please enter valid [to-date].It must larger or equal today.";
                            $data_input['nth_av_todate'] =""; 
                       }
                       else if($todate < $fromdate)
                       {
                            $arrErrors['nth_av_todate'] = "Please enter valid [to-date].It must larger or equal [from-date].";
                            $data_input['nth_av_todate'] ="";                   
                       }           
                   }
                   else
                   {
                        $arrErrors['nth_av_todate'] = "Please enter valid [to-date] - mm/dd/yyyy!";      
                        $data_input['nth_av_todate'] ="";            
                   }
                }
            }
            else
            {
              $arrErrors['nth_av_fromdate'] = "Please enter valid [from-date] - mm/dd/yyyy!";    
              $data_input['nth_av_fromdate'] =""; 
            }
        }
        if(!empty($todate))
        {
            if(!$this->validateDate($todate))
            {
                $arrErrors['nth_av_todate'] = "Please enter valid [to-date] - mm/dd/yyyy!";      
                $data_input['nth_av_todate'] ="";
            } 
        }
        $data_input['nth_av_text']=esc_attr($data_input['nth_av_text']);
        
        //validate alert text
        if($data_input['nth_av_text']=='')
        {
            $arrErrors['nth_av_text'] = "Please enter alert text!";            
        }
        //validate position
        if($data_input['nth_av_position']!='top' && $data_input['nth_av_position']!='bottom')
        {
            $arrErrors['nth_av_position'] = "Position is not valid!";  
            $data_input['nth_av_position']="bottom";          
        }
        //validate transparent
        if($data_input['nth_av_trans']<0 || $data_input['nth_av_trans']>100)
        {
            $arrErrors['nth_av_trans'] = "Value of transparent is not valid! (value: 0 => 100)";     
            $data_input['nth_av_trans']=100;       
        }
        //validate background
        if(FALSE ===$this->check_color($data_input['nth_av_bgcolor']))
        {
            $arrErrors['nth_av_bgcolor'] = "Background Color is not valid!";    
            $data_input['nth_av_bgcolor']="#000000";
        }
        //validate height
        if(!is_numeric($data_input['nth_av_height']) || $data_input['nth_av_height']<0)
        {
            $arrErrors['nth_av_height'] = "Height field not valid!";    
            $data_input['nth_av_height'] = 30;        
        }
        //validate font
        if(FALSE ===$this->check_color($data_input['nth_av_fontcolor']))
        {
            $arrErrors['nth_av_fontcolor'] = "Font Color is not valid!";  
            $data_input['nth_av_fontcolor']="#ffffff";          
        }
        if(count($arrErrors)>0){
            $strErrors = 'Update fail,'.count($arrErrors).' error fields reset to default!. Please correct errors below:<br/>';
            foreach ($arrErrors as $key => $val){
                $strErrors .='<font style="color:red;">- '. $val . '</font><br/>';
            }           
            add_settings_error($this->_menuSlug, 'nth-setting', $strErrors,'error');
            return $data_input;
        }
        else{
            add_settings_error($this->_menuSlug, 'nth-setting', 'Complete Updated','updated');
             return $data_input;  
        }
             
    }
     public function settingMenu(){
        add_menu_page(
                      'Notice Setting Page', 
                      'Notice Setting', 
                      'manage_options'
                      ,$this->_menuSlug, 
                       array($this, 'settingPage')
                    );
    }
    public function settingPage(){
        require_once('views/admin.php');
    }

    public function av_Active_field() { 
         
        $val = ( isset( $this->_setting_option['nth_av_active'] ) ) ? $this->_setting_option['nth_av_active'] : '0';
        echo '<label class="switch-light switch-candy" onclick="">';
        echo '<input name="nth_option[nth_av_active]" type="checkbox" value="1" '.checked( 1, $val, false ).'>'; 
        echo  '<strong>';
        echo    '';
        echo  '</strong>';
        echo  '<span>';
        echo    '<span>Off</span>';
        echo    '<span>On</span>';
        echo    '<a></a>';
        echo '</span>';
        echo '</label>';
        echo '<p><i>Turn On/Turn Off notice bar on your site.If your date range expire for current day,the notice bar will do not show. Please leave date empty for show all the time.</i></p>';
    } 
    public function av_FromDate_field($args) { 
          extract( $args );
        
        $val = ( isset( $this->_setting_option['nth_av_fromdate'] ) ) ? $this->_setting_option['nth_av_fromdate'] : '';      
        echo '<input id="datepickerFrom" name="nth_option[nth_av_fromdate]" value="'.$val.'" class="datepicker-fields" placeholder="mm/dd/yyyy"/>';
        
    }
     public function av_ToDate_field($args) { 
          extract( $args );
        $val = ( isset( $this->_setting_option['nth_av_todate'] ) ) ? $this->_setting_option['nth_av_todate'] : '';
        echo '<input id="datepickerTo" name="nth_option[nth_av_todate]" value="'.$val.'" class="datepicker-fields" placeholder="mm/dd/yyyy"/>';
    }
    public function av_Text_field() { 
         
        $val = ( isset( $this->_setting_option['nth_av_text'] ) ) ? $this->_setting_option['nth_av_text'] : '';
        $content=$val;
        $id='wpEditor';
        $settings =  array(
        'media_buttons' => false,
        'quicktags'     => array("buttons"=>"link,close"),
        'textarea_name' => "nth_option[nth_av_text]",
        //'tinymce'       => false,
        'editor_height' => 100,
    );
        wp_editor( $content, $id,$settings );
        //echo '<textarea style="width:100%" name="nth_option[nth_av_text]">' . $val . '</textarea>';
    }   
    public function av_Position_field() { 
         
        $val = ( isset( $this->_setting_option['nth_av_position'] ) ) ? $this->_setting_option['nth_av_position'] : '';
        $top='';
        $bottom='';
        if($val=='top')
        {
            $top='selected';
        }
        else
        {
            $bottom='selected';
        }
        echo '<select  name="nth_option[nth_av_position]"><option value="top" '.$top.'>Top</option><option value="bottom" '.$bottom.'>Bottom</option></select>';
    }
    public function av_Trans_field() { 
         
        $val = ( isset( $this->_setting_option['nth_av_trans'] ) ) ? $this->_setting_option['nth_av_trans'] : '100';
        echo '<input id="transBar" type="number" min="0" max="100" name="nth_option[nth_av_trans]" value="'.$val.'"/> (%)';
    } 
    public function av_BgColor_field() { 
         
        $val = ( isset( $this->_setting_option['nth_av_bgcolor'] ) ) ? $this->_setting_option['nth_av_bgcolor'] : '#000000';
        echo '<input type="text" name="nth_option[nth_av_bgcolor]" value="'.$val.'" class="color-field"/>';
    }
    public function av_FontColor_field() {          
        $val = ( isset( $this->_setting_option['nth_av_fontcolor'] ) ) ? $this->_setting_option['nth_av_fontcolor'] : '#ffffff';
        echo '<input type="text" name="nth_option[nth_av_fontcolor]" value="'.$val.'" class="color-field"/>';
    }
    public function CheckNumbers($num){
    		if($str <=100 && $str >=0){
    			return true;
    		}	
		return false;
    }
    public function av_Height_field() { 
         
        $val = ( isset( $this->_setting_option['nth_av_height'] ) ) ? $this->_setting_option['nth_av_height'] : '30';
        echo '<input id="heightBar" type="number" min="0"  name="nth_option[nth_av_height]" value="'.$val.'"/> (px)';
    } 
    public function check_color( $value ) {      
        if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #     
            return true;
        }         
        return false;
    }
    public function validateDate($date)
    {
        $d = DateTime::createFromFormat('m/d/Y', $date);
        return $d && $d->format('m/d/Y') === $date;
    }
}
?>
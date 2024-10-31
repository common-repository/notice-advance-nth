
<script>
function openTab(evt, tabName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the link that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}
</script>
<div class="wrap">
	<h2>Notice Advance NTH Setting page</h2>
    <?php settings_errors( $this->_menuSlug, false, false );?>
    <hr />
    <div id="NoticeBarNTH">
    <input type="hidden" id="url" name="url" value="<?php echo plugins_url(); ?>"/>
    <ul class="tab">
      <li><a href="#" class="tablinks active" onclick="openTab(event, 'notice')">Notice Bar settings</a></li>
      <li><a href="#" class="tablinks" onclick="openTab(event, 'popup')">Popup settings</a></li>
    
    </ul>
    
    <div id="notice" class="tabcontent" style="display: block;">
    <form method="post" action="options.php" id="nth_setting_form" enctype="multipart/form-data" > 
    <p class="submit" style="float:right">
    	       <input type="submit" name="submit" class="button button-primary" value="Save Changes" />
	</p>  
        <?php settings_fields('nth_setting_options'); ?>
        <?php do_settings_sections($this->_menuSlug); ?>
        <p class="submit"  style="float:right">
    	       <input type="submit" name="submit" class="button button-primary" value="Save Changes" />
	    </p>
   	</form>
    </div>
    
    <div id="popup" class="tabcontent">
      <p>Currently unavailable for this version</p> 
    </div>
</div>
	
</div>

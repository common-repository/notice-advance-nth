(function( $ ) {
 
    // Add Color Picker to all inputs that have 'color-field' class
    $(function() {
        $('.color-field').wpColorPicker();
         $('.datepicker-fields').datepicker({
            showOn: "both",
            buttonImage: $('#url').val() + "/notice-advance-nth/images/calendar.png",
            buttonImageOnly: true,
            buttonText: "Open calendar",
                     
         });
        
    });
     
})( jQuery );

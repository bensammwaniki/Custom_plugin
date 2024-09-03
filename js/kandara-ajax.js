jQuery(document).ready(function($) {
    $('.kandara-form').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        var formData = $(this).serialize(); 
        formData += '&action=kandara_process_form'; // Add the action for AJAX handling

        $.ajax({
            type: 'POST',
            url: kandara_ajax_object.ajax_url, // Get the AJAX URL from the localized script
            data: formData,
            success: function(response) {
                if (response.success) {
                    console.log(response.data); // Log the success message
                    $('.alert-danger').hide();
                    $('.alert-success').removeAttr('hidden').show();
                    $('.kandara-form')[0].reset();
                } else {
                    console.log('Server Error:', response.data); // Log the error message
                    $('.alert-success').hide();
                    $('.alert-danger').removeAttr('hidden').show().text(response.data.message);
                }
            },
            error: function(errorThrown) {
                console.log('AJAX Error:', errorThrown, formData);
                $('.alert-success').hide();
                $('.alert-danger').removeAttr('hidden').show().text('An error occurred while processing your request.');
            }
        });
    });
});

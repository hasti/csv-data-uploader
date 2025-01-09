
jQuery(document).ready(function() {
    // Your code here
    jQuery("#frm_csv_data_uploader").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        jQuery.ajax({
            url: csv_data_uploader.ajax_url,
            method: "POST",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
                if (response.success == 1) {
                    jQuery("#show_upload_message").text(response.data.message).css("color", "green");
                }else{
                    jQuery("#show_upload_message").text(response.data.message).css("color", "red");
                }   
            }
        });
    });
});
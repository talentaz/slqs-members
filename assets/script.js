!function() {
    "use strict";
    window.addEventListener("load", function() {
        var t = document.getElementsByClassName("needs-validation");
        Array.prototype.filter.call(t, function(e) {
            e.addEventListener("submit", function(t) {
                !1 === e.checkValidity() && (t.preventDefault(),
                t.stopPropagation()),
                e.classList.add("was-validated")
            }, !1)
        })
    }, !1)
}(),
jQuery(document).ready(function($) {
    $(document).ready(function() {
        $(".custom-validation").parsley()
        $(".input-mask").inputmask()
        
        
        // register form
        $('#slqs-registration-form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            $('#registerButton').hide(); // Hide the original button
            $('#loadingButton').show(); // Show the loading button
            // Serialize form data
            var formData = $(this).serialize();
            // AJAX request
            $.ajax({
                type: 'POST',
                url: myAjax.ajaxurl, // Use the localized AJAX URL
                data: formData + '&action=slqs_register', // Append action to the data
                success: function(response) {
                    if (response.success) {
                        toastr["success"](response.data.message)
                        setTimeout(function() {
                            window.location.href = response.data.redirect;
                        }, 2000);
                    } else {
                        toastr["error"](response.data)
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + error);
                },
                complete: function() {
                    // Re-enable the original button and hide the loading button
                    $('#registerButton').show(); // Show the original button
                    $('#loadingButton').hide(); // Hide the loading button
                }
            });
        });

        // list page
        $('#memberList').on('click', '.page-item', function() {
            $('#spinner-container').show();
            // Check if the clicked item is a dot
            if ($(this).hasClass('dots')) {
                // If it's a dot, load the first page
                var page = 1; // Set page to 1 for dots
                console.log('Requested page:', page); // Log the requested page number
    
                // Disable all pagination buttons
                $('#pagination .page-item').addClass('disabled');
    
                // AJAX request to load members
                $.ajax({
                    type: 'POST',
                    url: myAjax.ajaxurl, // Use the localized AJAX URL
                    data: {
                        action: 'load_members',
                        page: page
                    },
                    success: function(response) {
                        $('#memberList').html(response); // Update the member list
                        $('#pagination .page-item').removeClass('disabled'); // Re-enable pagination buttons
                    },
                    error: function() {
                        alert('An error occurred while loading members.');
                        $('#pagination .page-item').removeClass('disabled'); // Re-enable pagination buttons
                    }
                });
            } else {
                // Handle clicks on other pagination items
                var page = $(this).data('page');
                if (page !== undefined) {
                    console.log('Requested page:', page); // Log the requested page number
    
                    // Disable all pagination buttons
                    $('#pagination .page-item').addClass('disabled');
    
                    // AJAX request to load members
                    $.ajax({
                        type: 'POST',
                        url: myAjax.ajaxurl, // Use the localized AJAX URL
                        data: {
                            action: 'load_members',
                            page: page
                        },
                        success: function(response) {
                            $('#memberList').html(response); // Update the member list
                            $('#pagination .page-item').removeClass('disabled'); // Re-enable pagination buttons
                        },
                        error: function() {
                            alert('An error occurred while loading members.');
                            $('#pagination .page-item').removeClass('disabled'); // Re-enable pagination buttons
                        }
                    });
                }
            }
        });

    });
});

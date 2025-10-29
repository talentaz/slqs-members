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
                        // setTimeout(function() {
                        //     window.location.href = response.data.redirect;
                        // }, 2000);
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

        // member edit
        $('#slqs-member-edit-form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            $('.registerButton').hide(); // Hide the original button
            $('.loadingButton').show(); // Show the loading button
        
            // Create a FormData object to hold the form data
            var formData = new FormData(this); // 'this' refers to the form element
        
            // Add the action to the FormData
            formData.append('action', 'slqs_edit');
        
            // AJAX request
            $.ajax({
                type: 'POST',
                url: myAjax.ajaxurl, // Use the localized AJAX URL
                data: formData,
                processData: false, // Prevent jQuery from automatically transforming the data into a query string
                contentType: false, // Set content type to false to let jQuery set it correctly
                success: function(response) {
                    if (response.success) {
                        toastr["success"](response.data.message);
                        // Uncomment the following lines if you want to redirect after success
                        setTimeout(function() {
                            window.location.reload(); 
                        }, 2000);
                    } else {
                        toastr["error"](response.data);
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + error);
                },
                complete: function() {
                    // Re-enable the original button and hide the loading button
                    $('.registerButton').show(); // Show the original button
                    $('.loadingButton').hide(); // Hide the loading button
                }
            });
        });

         // member edit
         $('#slqs-member-cpd-edit-form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            $('.registerButton').hide(); // Hide the original button
            $('.loadingButton').show(); // Show the loading button
            // Serialize form data
            var formData = $(this).serialize();
            
            // AJAX request
            $.ajax({
                type: 'POST',
                url: myAjax.ajaxurl, // Use the localized AJAX URL
                data: formData + '&action=slqs_cpd_edit',
                success: function(response) {
                    if (response.success) {
                        toastr["success"](response.data.message)
                        setTimeout(function() {
                            window.location.reload(); 
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
                    $('.registerButton').show(); // Show the original button
                    $('.loadingButton').hide(); // Hide the loading button
                }
            });
        });

        $('#slqs-member-ric-edit-form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            $('.registerButton').hide(); // Hide the original button
            $('.loadingButton').show(); // Show the loading button
            // Serialize form data
            var formData = $(this).serialize();
            
            // AJAX request
            $.ajax({
                type: 'POST',
                url: myAjax.ajaxurl, // Use the localized AJAX URL
                data: formData + '&action=slqs_ric_edit',
                success: function(response) {
                    if (response.success) {
                        toastr["success"](response.data.message)
                        setTimeout(function() {
                            window.location.reload(); 
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
                    $('.registerButton').show(); // Show the original button
                    $('.loadingButton').hide(); // Hide the loading button
                }
            });
        });

        $('#slqs-member-password-edit-form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            $('.registerButton').hide(); // Hide the original button
            $('.loadingButton').show(); // Show the loading button
            // Serialize form data
            var formData = $(this).serialize();
            
            // AJAX request
            $.ajax({
                type: 'POST',
                url: myAjax.ajaxurl, // Use the localized AJAX URL
                data: formData + '&action=slqs_password_edit',
                success: function(response) {
                    if (response.success) {
                        toastr["success"](response.data.message)
                        setTimeout(function() {
                            window.location.reload(); 
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
                    $('.registerButton').show(); // Show the original button
                    $('.loadingButton').hide(); // Hide the loading button
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

        $('#searchInput').on('keyup change', function() {
            var searchValue = $('#searchInput').val();
            $('#spinner-container').show();
            $.ajax({
                type: 'POST',
                url: myAjax.ajaxurl, // Use the localized AJAX URL
                data: {
                    action: 'load_members', // Use the same action for loading members
                    keyword: searchValue,
                    page: 1 // Reset to the first page on new search
                },
                success: function(response) {
                    $('#memberList').html(response); // Update the member list
                },
                error: function() {
                    alert('An error occurred while searching members.');
                }
            });
        });
        $("#wizard-picture").change(function(){
            readURL(this);
        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
        
                reader.onload = function (e) {
                    $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    });
});


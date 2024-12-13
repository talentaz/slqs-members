jQuery(document).ready(function($) {
    $('#example').DataTable({
        ajax: {
            url: ajaxurl, // WordPress AJAX URL
            type: 'POST',
            data: {
                action: 'fetch_members' // Action to fetch members
            },
            dataSrc: function(json) {
                return json.data;
            }
        },
        columns: [
            {
                data: null,
                render: function(data, type, row) {
                    return '<img src="' + row.profile_photo + '" width="50px">';
                }
            },
            { data: 'full_name' },
            { data: 'member_id' },
            { data: 'email' },
            { data: 'type_name' },
            {
                data: null,
                render: function(data, type, row) {
                    console.log(row)
                    if(row.status == "approved"){
                        console.log('row.status', row.status)
                        return '<p>' + row.status + '</p>';
                    } else {
                        return '<a href="' + row.approve_link + '">Approve</a>';
                    }
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    // Include Edit and Delete buttons
                    let editButton = '<a href="' + '<?php echo admin_url( "admin.php?page=slqs-edit-member&member_id=" ); ?>' + row.id + '">Edit</a>';
                    let deleteButton = '<a href="' + '<?php echo admin_url( "admin-post.php?action=delete_member&member_id=" ); ?>' + row.id + '" onclick="return confirm(\'Are you sure you want to delete this member?\');">Delete</a>';
                    return editButton + ' | ' + deleteButton;
                }
            }
        ]
    });
});
jQuery(document).ready(function($) {
    // Initialize DataTable
    var table = $('#example').DataTable({
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
                    if(row.status == "REVIEW"){
                        return '<a href="' + row.approve_link + '"> Approve </a>';
                    } else {
                        return '<p>' + row.status + '</p>';
                    }
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    let editButton = '<a href="' + 'admin.php?page=slqs-edit-member&member_id=' + row.id + '">Edit</a>';
                    let deleteButton = '<a href="' + 'admin-post.php?action=delete_member&member_id=' + row.id + '" onclick="return confirm(\'Are you sure you want to delete this member?\');">Delete</a>';
                    return editButton + ' | ' + deleteButton;
                }
            }
        ]
    });

    // Add Export to Excel button
    $('#exportExcel').on('click', function() {
        // Fetch data from the DataTable
        var data = table.rows().data().toArray();
        var csvContent = "data:text/csv;charset=utf-8," 
            + "Full Name,Member ID,Email,Type Name,Status\n" // Header row

        data.forEach(function(row) {
            var rowData = [
                row.full_name,
                row.member_id,
                row.email,
                row.type_name,
                row.status
            ].join(","); // Join row data with commas
            csvContent += rowData + "\n"; // Add row to CSV content
        });

        // Create a link to download the CSV
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "members_data.csv");
        document.body.appendChild(link); // Required for Firefox

        link.click(); // Trigger the download
        document.body.removeChild(link); // Clean up
    });
});
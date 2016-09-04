$(document).ready(function() {

	$('#newContact').click(function() {
	    $('.hidden').show();
    });

    $( "#lookup" ).on("keyup", function() {
        if($( "#lookup" ).val().length > 2) {
            $.ajax({
                type: "POST",
                url: "/ajax.php",
                data: {action: 'search', lookup: $( "#lookup" ).val()},
                success: function(output) {
                    $( "#contactList" ).html(output);
                    $('.add-to-fav').click(function() {
                        var contact_data = JSON.stringify($(this).data('contact'));
                        $.ajax({
                            type: "POST",
                            url: "/ajax.php",
                            data: {action: 'add_to_favourites', contact: contact_data},
                            success: function(output) {
                                $( "#myContacts" ).html(output);
                            }
                        });
                    });
                }
            });
        }
        else {
            $.ajax({
                type: "POST",
                url: "/ajax.php",
                data: {action: 'contact_list'},
                success: function(output) {
                    $( "#contactList" ).html(output);
                    $('.add-to-fav').click(function() {
                        var contact_data = JSON.stringify($(this).data('contact'));
                        $.ajax({
                            type: "POST",
                            url: "/ajax.php",
                            data: {action: 'add_to_favourites', contact: contact_data},
                            success: function(output) {
                                $( "#myContacts" ).html(output);
                            }
                        });
                    });
                }
            });
        }
    });

    /*$('#add_contact').click(function(event) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "/ajax.php",
            data: $("#add_contact_form").serialize(),
            success: function(output) {
                $( "#contactList" ).html(output);
                $('.add-to-fav').click(function() {
                    var contact_data = JSON.stringify($(this).data('contact'));
                    $.ajax({
                        type: "POST",
                        url: "/ajax.php",
                        data: {action: 'add_to_favourites', contact: contact_data},
                        success: function(output) {
                            $( "#myContacts" ).html(output);
                        }
                    });
                });
            }
        });
    });*/

    $('.add-to-fav').click(function() {
        var contact_data = JSON.stringify($(this).data('contact'));
        $.ajax({
            type: "POST",
            url: "/ajax.php",
            data: {action: 'add_to_favourites', contact: contact_data},
            success: function(output) {
                $( "#myContacts" ).html(output);
            }
        });
    });

});
jQuery(function($) {
    $('body').on('click', '.subscribe', function(e) {
        e.preventDefault();
        email = $('#email').val();
        if (isEmail(email)) {
            var data = {
                'action': 'subscribe_user',
                'email': email,
                'security': aw.security
            };

            $.post(aw.ajaxurl, data, function(response) {
                if (response == 200) {
                    alert('You have subscribed successfully.');
                } else {
                    alert(response);
                }
            });
        } else {
            alert('This is not a valid email');
        }
    });
});

function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}
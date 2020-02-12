jQuery(function($) {

    $('#allSelect').change(function() {
        if ($(this).is(':checked')) {
            $('.my-sqr-check-btn input').attr('checked', true);
        } else {
            $('.my-sqr-check-btn input').attr('checked', false);
        }
    });
    $('.unchecked').on('click', function() {
        $('#allSelect').attr('checked', false);
    });

    /** on select form from drop down reload **/
    $(document).on('change', '#lpf-g-form-list', function() {
        let form_id = $(this).val();

        let url = new URL(window.location.href);

        url.searchParams.set('g_form_id', form_id);
        window.location.replace(url.href);
    });

    /** on select form from drop down reload **/
    $(document).on('change', '#lpf-template-list', function() {
        let template_id = $(this).val();

        let url = new URL(window.location.href);

        url.searchParams.set('template_id', template_id);
        window.location.replace(url.href);
    });
});
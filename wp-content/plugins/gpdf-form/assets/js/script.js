jQuery(function($) {



    $('.delete_source_pdf').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data("pdf_id");
        $.ajax({
                type: 'post',
                url: frontend_form_object.ajaxurl,
                data: {
                    action: 'delete_source_pdf_forms',
                    id: id,
                },
            })
            .done(function() {
                location.reload();
            })
    })

    $('.editPdfButton').click(function(event) {
        var id = $(this).data("pdf_id");
        $('.pdf_id_on_modal').val(id);
    });
})
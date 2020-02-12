jQuery(function($) {

    $('.dateFilter').datepicker({
        dateFormat: "yy-mm-dd"
    });



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
            .done(function(response) {
                console.log(response);
                location.reload();
            })
    })

    $('.editPdfButton').click(function(event) {
        var id = $(this).data("pdf_id");
        $('.pdf_id_on_modal').val(id);
    });


    //############


    // $('.edit-employee-modal').on('click', function() {
    //     let id = $(this).data('id');
    //     $('#editEmployeeModal').find("[name='id']").val(id);

    //     $.ajax({
    //             url: frontend_form_object.ajaxurl,
    //             type: 'POST',
    //             data: {
    //                 action: 'get_edit_employee',
    //                 id: $(this).data('id'),
    //             },
    //         })
    //         .done(function(response) {
    //             if (response.success) {

    //             }
    //         })
    //         .fail(function() {
    //             console.log("error");
    //         })


    // })

    //################################################
    //show val
    $('.update-ajax').on('click', function() {
        var id = $(this).data('id');
        console.log(id);
        $("#myModal").find("[name = 'id']").val(id);

        $.ajax({
                url: frontend_form_object.ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_update_ajax',
                    get_ajax_id: id,
                }
            })
            .done(function(response) {
                if (response.success) {
                    $("[name=id]").val(response.data.update_ajax.id);
                    $("[name=firstName]").val(response.data.update_ajax.first_name);
                    $("[name=lastName]").val(response.data.update_ajax.last_name);
                    $("[name=email]").val(response.data.update_ajax.email);
                    $("[name=country]").val(response.data.update_ajax.country);
                    $("[name=home_address]").val(response.data.update_ajax.home_address);
                    $("[name=mobile_phone]").val(response.data.update_ajax.mobile_phone);
                    $("[name=note_update]").val(response.data.update_ajax.notes);
                }
            })
            .fail(function() {
                console.log('Error');
            })
    });

    //update val
    // $("#save").click(function() {
    //     var id = $('[name=id]').val();
    //     console.log(id);

    //     var firstName = $('[name=firstName]').val();
    //     var lastName = $('[name=lastName]').val();
    //     var email = $('[name=email]').val();
    //     var home_address = $('[name=home_address]').val();
    //     var mobile_phone = $('[name=mobile_phone]').val();
    //     var note_update = $('[name=note_update]').val();

    //     $.ajax({
    //             url: frontend_form_object.ajaxurl,
    //             type: "POST",
    //             data: {
    //                 action: 'update_ajax',
    //                 update_ajax_id: id,
    //                 firstName: firstName,
    //                 lastName: lastName,
    //                 email: email,
    //                 home_address: home_address,
    //                 mobile_phone: mobile_phone,
    //                 note_update: note_update,
    //             }
    //         })
    //         .done(function(response) {
    //             if (response.success) {
    //                 window.location.replace(url);
    //                 // window.location.href = data.url;

    //             }
    //         })
    //         .fail(function() {
    //             console.log('Error');

    //         })

    // })

    //#############################################


    $('.edit-hotel-name').on('click', function() {
        let id = $(this).data('id');
        console.log(id);
        $('#addHotelModal').find("[name='id']").val(id);

        $.ajax({
                url: frontend_form_object.ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_edit_employee',
                    emp_id: id,
                },
            })
            .done(function(response) {
                if (response.success) {
                    // $("[name=fname]").val(response.data.edit_employee.id);
                    $("[name=fname]").val(response.data.edit_employee.first_name);
                    $("[name=lname]").val(response.data.edit_employee.last_name);
                    $("[name=email]").val(response.data.edit_employee.email);
                    $("[name=haddress]").val(response.data.edit_employee.home_address);
                    $("[name=message]").val(response.data.edit_employee.messages);
                    $("[name=phone]").val(response.data.edit_employee.mobile_phone);
                    $("[name=date]").val(response.data.edit_employee.date);
                    $("[name=note_update]").val(response.data.edit_employee.notes);
                }
            })
            .fail(function() {
                console.log("error");
            })

    })


    $('.show-flight-notes').on('click', function() {
        let id = $(this).data('id');
        console.log(id);
        $('#viewFlightNoteModal').find("[name='id']").val(id);

        $.ajax({
                url: frontend_form_object.ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_flight_note',
                    id: id,
                },
            })
            .done(function(response) {
                if (response.success) {
                    $("[name=message]").val(response.data.flight_note.notes);
                    $("#viewFlightNoteModal .single-notes").text(response.data.flight_note.notes);
                }
            })
            .fail(function() {
                console.log("error");
            })

    })


    $('.add-note').on('click', function() {
        let id = $(this).data('id');
        console.log(id);
        $('#addNote').find("[name='emp_id']").val(id);

        $.ajax({
                url: frontend_form_object.ajaxurl,
                type: 'POST',
                data: {
                    action: 'add_note',
                    id: id,
                },
            })
            .done(function(response) {
                if (response.success) {
                    $("[name=message]").val(response.data.flight_note.notes);
                    $("#viewFlightNoteModal .single-notes").text(response.data.flight_note.notes);
                }
            })
            .fail(function() {
                console.log("error");
            })
    })

    //delete schedule
    $('.delete-schedule').click(function(e) {
        e.preventDefault();
        id = $(this).data('id');
        console.log(id);
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                        type: "POST",
                        url: frontend_form_object.ajaxurl,
                        data: {
                            action: 'delete_event_schedule',
                            id: id
                        }
                    })
                    .done(function(response) {
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                        location.reload();
                    })
            }
        })
    });




    //Delete Emplyoee
    $('.delete-hotel-name').click(function(e) {
        e.preventDefault();
        //  ref_id = $(this).data('ref_id');
        id = $(this).data('id');
        console.log(id);

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                        type: 'post',
                        url: frontend_form_object.ajaxurl,
                        data: {
                            action: 'delete_employee',
                            id: id,
                        },
                    })
                    .done(function(response) {
                        if (response.success) {
                            // swal('Great!', 'Successfully deleted.', 'success');
                            // _parent.remove();
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )

                            location.reload();
                        } else {
                            swal(response.data.message, 'error');
                        }
                    });
            }
        })
    })

    /*members search */
    // $('.member-search').click(function(e) {
    //     e.preventDefault();
    //     let search_col = $('[name="search_col"]').val();
    //     let search_term = $('[name="search_term"]').val();

    //     let urlParams = new URLSearchParams(window.location.search);
    //     urlParams.set("search_col", search_col);
    //     urlParams.set("search_term", search_term);
    //     console.log(urlParams.toString());

    //     window.location.href = window.location.origin + window.location.pathname + '?' + urlParams.toString();
    // })




})
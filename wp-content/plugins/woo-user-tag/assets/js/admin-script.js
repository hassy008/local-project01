jQuery(function($) {
	/** EXPORT */
	/** FILTER ORDERS */
	$('.wut_filter_order_preview').click(function () {
		wut_filter_orders();
	});

	$(document).on('change', '[name="orderTable_length"]', function () {
		wut_filter_orders();
	})


	function wut_filter_orders() {
		let order_status = $('.wut_filter_order_by_order_status').val();
		let prod_name = $('.wut_filter_order_by_prod_name').val();
		let cat_name = $('.wut_filter_order_by_cat_name').val();
		let order_id = $('.wut_filter_order_by_order_id').val();
		let start = $('.wut_filter_order_by_start_date').val();
		let end = $('.wut_filter_order_by_end_date').val();
		$.ajax({
			url: frontend_form_object.ajaxurl,
			type: 'POST',
			data: {
				action: 'wut_filter_orders',
				order_status: order_status,
				prod_name: prod_name,
				cat_name: cat_name,
				order_id: order_id,
				start: start,
				end: end,
				ajax_nonce: frontend_form_object.ajax_nonce,

			}
		})
			.done(function (response) {
				$('#orderTable').find('thead').html(response.thead);
				$('#orderTable').find('tbody').html(response.tbody);
				console.log(response);


			});
	}


	/** FILTER USERS */
	$('.wut_filter_user_preview').click(function(){
		wut_filter_users();
	});

	$(document).on('change', '[name="userTable_length"]', function () {
		wut_filter_users();
	})

	function wut_filter_users() {
		let usernames = $('.wut_filter_by_username').val();
		let user_roles = $('.wut_filter_by_role').val();
		let paged = $('.wut_filter_by_paged').val();
		let limit = $('.wut_filter_by_limit').val();
		let start = $('.wut_filter_by_start').val();
		let end = $('.wut_filter_by_end').val();
		let meta_field = $('.wut_filter_by_meta').val();
		let meta_value = $('.wut_filter_by_meta_value').val();

		$.ajax({
				url: frontend_form_object.ajaxurl,
				type: 'POST',
				data: {
					action: 'wut_filter_users',
					usernames: usernames,
					user_roles: user_roles,
					paged: paged,
					limit: limit,
					start: start,
					end: end,
					meta_field: meta_field,
					meta_value: meta_value,
					wut_filter_by_practice_area: $('.wut_filter_by_practice_area').val(),
					ajax_nonce: frontend_form_object.ajax_nonce,
				}
			})
			.done(function (response) {
				$('#userTable').find('thead').html(response.thead);
				$('#userTable').find('tbody').html(response.tbody);
				console.log(response);
				// document.getElementById("userTable").innerHTML = response.map(function (item, index) {

				// });

			});
	}

	/** FILTER USERS ENDS */
	$('.product-tag-select-multiple, .product-tag-deselect-multiple, .addCategory, .editCategory, .select2-style, .filter-by').select2({
		tags: true
	});
	$('.select2-style').select2({
		 placeholder: "Apply tag"
	});

	 $('.wut_filter_by_meta').select2();

	// $('#userTable, #orderTable').DataTable();

	$('.manage-field-btn').on('click', function(){
		$('.manage-field-box').slideToggle('slow');
	});

	$('.export-preview').on('click', function(){
		$('.data-table-section').slideDown('slow');
	});

	$('#startDate, #EndDate').datepicker();

	/** WUT EMAIL CONFIG SAVE */
	$(document).on('click', '.wut_email_save', function () {
		let _parent   = $(this).parents(".campain-email");
		let from_name = _parent.find(".wut_email_from_name").val();
		let reply_name = _parent.find(".wut_email_reply_name").val();
		let from_email = _parent.find(".wut_email_from_email").val();
		let reply_email = _parent.find(".wut_email_reply_email").val();
		$.ajax({
			url: frontend_form_object.ajaxurl,
			type: 'POST',
			data: {
				action: 'wut_email_config_save',
				from_name: from_name,
				reply_name: reply_name,
				from_email: from_email,
				reply_email: reply_email,
				ajax_nonce: frontend_form_object.ajax_nonce,
			}
		})
			.done(function (response) {
				if (response.success == false) {
					swal("Sorry!", response.data.message, "error");
				} else {
					swal("Great!", response.data.message, "success");
					location.reload();
				}
			});		
	});

	/** on click edit category **/
	$(document).on('click', '.edit-category-btn', function() {
		var id = $(this).parents('tr').data('id');
		var cat_name = $(this).parents('tr').find('.cat_name').text();
		$(this).parents('.bootstrap-wrapper').find('.edit_category_id').val(id);
		$(this).parents('.bootstrap-wrapper').find('.edit_cat_name').val(cat_name);
	})

	/** on click delete category **/
	$('.category-delete').on('click', function(e) {
		e.preventDefault();
		var id = $(this).parents('tr').data('id');

		swal({
			title: "Are you sure?",
			text: "You will not be able to recover this!",
			type: "error",
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((willDelete) => {
			if (willDelete) {
				$.ajax({
						url: frontend_form_object.ajaxurl,
						type: 'POST',
						data: {
							action: 'wut_delete_category',
							id: id,
							ajax_nonce: frontend_form_object.ajax_nonce,
						}
					})

					.done(function(response) {
						if (response.success == false) {
							swal("Sorry!", response.data.message, "error");
						} else {
							swal("Great!", response.data.message, "success");
							location.reload();
						}
					});

				// console.log(willDelete);
			} else {
				return false;
			}
		}).catch(swal.noop)
	});

	/** on click edit tag **/
	$(document).on('click', '.edit-tag-btn', function() {
		var id = $(this).parents('tr').data('id');
		var tag_name = $(this).parents('tr').find('.tag_name').text();
		var cat_id = $(this).parents('tr').data('category_id');
		$(this).parents('.bootstrap-wrapper').find('.edit_tag_id').val(id);
		$(this).parents('.bootstrap-wrapper').find('.edit_tag_name').val(tag_name);
		$(this).parents('.bootstrap-wrapper').find('.edit-tag-cat-select').val(null).trigger('change');
		$(this).parents('.bootstrap-wrapper').find('.edit-tag-cat-select').val(cat_id);
		$(this).parents('.bootstrap-wrapper').find('.edit-tag-cat-select').trigger('change');
	})

	/** on click delete tag **/
	$('.tag-delete').on('click', function(e) {
		e.preventDefault();
		var id = $(this).parents('tr').data('id');

		swal({
			title: "Are you sure?",
			text: "You will not be able to recover this!",
			type: "error",
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((willDelete) => {
			console.log(willDelete);
			if (willDelete) {
				$.ajax({
						url: frontend_form_object.ajaxurl,
						type: 'POST',
						data: {
							action: 'wut_delete_tag',
							id: id,
							ajax_nonce: frontend_form_object.ajax_nonce,
						}
					})

					.done(function(response) {
						if (response.success == false) {
							swal("Sorry!", response.data.message, "error");
						} else {
							swal("Great!", response.data.message, "success");
							location.reload();
						}
					});
			} else {
				return false;
			}
		}).catch(swal.noop)
	});

	/** on click delete campaign **/
	$('.delete-campaign').on('click', function (e) {
		e.preventDefault();
		var id = $(this).parents('tr').data('id');

		swal({
			title: "Are you sure?",
			text: "You will not be able to recover this!",
			type: "error",
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((willDelete) => {
			console.log(willDelete);
			if (willDelete) {
				$.ajax({
					url: frontend_form_object.ajaxurl,
					type: 'POST',
					data: {
						action: 'wut_delete_campaign',
						id: id,
						ajax_nonce: frontend_form_object.ajax_nonce,
					}
				}).done(function (response) {
						if (response.success == false) {
							swal("Sorry!", response.data.message, "error");
						} else {
							swal("Great!", response.data.message, "success");
							location.reload();
						}
					});
			} else {
				return false;
			}
		}).catch(swal.noop)
	});


	$('.product-tag-select-multiple').on('select2:select', function(e) {
		console.log(e.params.data.id);
		var tag_id = e.params.data.id;
		var type = 'insert';
		var action = 'select';
		var product_id = $(this).parents('.bootstrap-wrapper').find("[name='wut_product_id']").val();
		save_wut_tag_rules(tag_id, action, type, product_id)
	})

	$('.product-tag-select-multiple').on('select2:unselect', function(e) {
		var tag_id = e.params.data.id;
		var type = 'delete';
		var action = 'select';
		var product_id = $(this).parents('.bootstrap-wrapper').find("[name='wut_product_id']").val();
		save_wut_tag_rules(tag_id, action, type, product_id)
	})

	$('.product-tag-deselect-multiple').on('select2:select', function(e) {
		var tag_id = e.params.data.id;
		var action = 'deselect';
		var type = 'insert';
		var product_id = $(this).parents('.bootstrap-wrapper').find("[name='wut_product_id']").val();
		save_wut_tag_rules(tag_id, action, type, product_id)
	})

	$('.product-tag-deselect-multiple').on('select2:unselect', function(e) {
		var tag_id = e.params.data.id;
		var action = 'deselect';
		var type = 'delete';
		var product_id = $(this).parents('.bootstrap-wrapper').find("[name='wut_product_id']").val();
		save_wut_tag_rules(tag_id, action, type, product_id)
	})

	function save_wut_tag_rules(tag_id, action, type, product_id, variation_id) {
		let action_url = 'save_wut_tag_rules';
		if (variation_id) {
			action_url = 'save_variation_wut_tag_rules';
		}

		$.ajax({
				type: 'POST',
				url: frontend_form_object.ajaxurl,
				data: {
					action: action_url,
					tag_id: tag_id,
					type: type,
					wut_action: action,
					product_id: product_id,
					variation_id: variation_id,
					ajax_nonce: frontend_form_object.ajax_nonce,
				},
			})
			.done(function() {
				// location.reload();
			})
	}

	$(document).on('select2:select', '.variable-product-tag-select-multiple', function (e) {
		console.log(e.params.data.id);
		var tag_id = e.params.data.id;
		var type = 'insert';
		var action = 'select';
		var product_id = $(this).parents('.variable_custom_field').find("[name='wut_product_id']").val();
		var variation_id = $(this).parents('.variable_custom_field').find("[name='wut_variation_id']").val();
		save_wut_tag_rules(tag_id, action, type, product_id, variation_id)
	})

	$(document).on('select2:unselect', '.variable-product-tag-select-multiple', function (e) {
		console.log(e.params.data.id);
		var tag_id = e.params.data.id;
		var type = 'delete';
		var action = 'select';
		var product_id = $(this).parents('.variable_custom_field').find("[name='wut_product_id']").val();
		var variation_id = $(this).parents('.variable_custom_field').find("[name='wut_variation_id']").val();
		save_wut_tag_rules(tag_id, action, type, product_id, variation_id)
	})

	$(document).on('select2:select', '.variable-product-tag-deselect-multiple', function (e) {
		console.log(e.params.data.id);
		var tag_id = e.params.data.id;
		var type = 'insert';
		var action = 'deselect';
		var product_id = $(this).parents('.variable_custom_field').find("[name='wut_product_id']").val();
		var variation_id = $(this).parents('.variable_custom_field').find("[name='wut_variation_id']").val();
		save_wut_tag_rules(tag_id, action, type, product_id, variation_id)
	})

	$(document).on('select2:unselect', '.variable-product-tag-deselect-multiple', function (e) {
		console.log(e.params.data.id);
		var tag_id = e.params.data.id;
		var type = 'delete';
		var action = 'deselect';
		var product_id = $(this).parents('.variable_custom_field').find("[name='wut_product_id']").val();
		var variation_id = $(this).parents('.variable_custom_field').find("[name='wut_variation_id']").val();
		save_wut_tag_rules(tag_id, action, type, product_id, variation_id)
	})

})
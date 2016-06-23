(function ($) {
    var Queue = {
        init: function () {
            //Events
            this.attachEvents();
        },
        add: function () {
            var _this = this;
            $.post({
                url: ajaxurl,
                data: {
                    action: 'add_queue'
                    // nonce_field: custom_ajax_vars.nonce
                }
            }).done(function (response) {
                if (200 == response.status) {
                    $('#queues').append('<option value="' + response.queue.id + '">Queue #' + response.queue.id + '</option>');
                    _this.notice(response.message, 'notice-success');
                } else {
                    _this.notice(response.message, 'notice-warning');
                }
            }).fail(function () {
                _this.notice('Something went wrong', 'notice-error');
            });
        },
        delete: function (queue_id) {
            var _this = this;
            $.post({
                url: ajaxurl,
                data: {
                    action: 'delete_queue',
                    data: {
                        queue_id: queue_id
                    }
                    // nonce_field: custom_ajax_vars.nonce
                }
            }).done(function (response) {
                if (200 == response.status) {
                    $("#queues option[value='" + response.queue.id + "']").remove();
                    _this.notice(response.message, 'notice-success');
                } else {
                    _this.notice(response.message, 'notice-warning');
                }
            }).fail(function (e) {
                _this.notice('Something went wrong', 'notice-error');
            });
        },
        disable: function (queue_id) {
            var _this = this;
            $.post({
                url: ajaxurl,
                data: {
                    action: 'disable_queue',
                    data: {
                        queue_id: queue_id
                    }
                    // nonce_field: custom_ajax_vars.nonce
                }
            }).done(function (response) {
                if (200 == response.status) {
                    $("#queues option[value='" + response.queue.id + "']").remove();
                    _this.notice(response.message, 'notice-success');
                } else {
                    _this.notice(response.message, 'notice-warning');
                }
            }).fail(function (e) {
                _this.notice('Something went wrong', 'notice-error');
            });
        },
        is_empty: function () {
            var _this = this;
            $.post({
                url: ajaxurl,
                data: {
                    action: 'is_empty_queue',
                    data: {
                        queue_id: queue_id
                    }
                    // nonce_field: custom_ajax_vars.nonce
                }
            }).done(function (response) {
                if (200 == response.status) {
                    _this.notice(response.message, 'notice-success');
                } else {
                    _this.notice(response.message, 'notice-warning');
                }
            }).fail(function (e) {
                _this.notice('Something went wrong', 'notice-error');
            });
        },
        get_all_elements: function (queue_id) {
            var _this = this;
            $.post({
                url: ajaxurl,
                data: {
                    action: 'get_all_elements',
                    data: {
                        queue_id: queue_id
                    }
                    // nonce_field: custom_ajax_vars.nonce
                }
            }).done(function (response) {
                if (200 == response.status) {
                    _this.notice(response.message, 'notice-success');
                    var elements = JSON.parse(response.queue.elements),
                        $queue_elements = $('#queue_elements');
                    $queue_elements.empty();
                    for (var i = 0; i < elements.length; i++) {
                        var element_id = elements[i].id,
                            element_name = elements[i].name;
                        $queue_elements.append('<option value="' + element_id + '" data-serialized="' + encodeURI(JSON.stringify(elements[i])) + '">#' + element_id + ' - ' + element_name + '</option>');
                    }
                } else {
                    _this.notice(response.message, 'notice-warning');
                }
            }).fail(function (e) {
                _this.notice('Something went wrong', 'notice-error');
            });
        },
        insert_element: function (args) {
            var _this = this;
            $.post({
                url: ajaxurl,
                data: {
                    action: 'insert_element',
                    data: {
                        name: args.name,
                        queue_id: args.queue_id,
                        data: args.data,
                        type: args.type,
                        priority: args.priority
                    }
                    // nonce_field: custom_ajax_vars.nonce
                }
            }).done(function (response) {
                if (200 == response.status) {
                    _this.notice(response.message, 'notice-success');
                } else {
                    _this.notice(response.message, 'notice-warning');
                }
            }).fail(function (e) {
                _this.notice('Something went wrong', 'notice-error');
            });
        },
        edit_element: function (args) {
            var _this = this;
            $.post({
                url: ajaxurl,
                data: {
                    action: 'edit_element',
                    data: {
                        id: args.id,
                        name: args.name,
                        queue_id: args.queue_id,
                        data: args.data,
                        type: args.type,
                        priority: args.priority
                    }
                    // nonce_field: custom_ajax_vars.nonce
                }
            }).done(function (response) {
                if (200 == response.status) {
                    _this.notice(response.message, 'notice-success');
                } else {
                    _this.notice(response.message, 'notice-warning');
                }
            }).fail(function (e) {
                _this.notice('Something went wrong', 'notice-error');
            });
        },
        delete_element: function (args) {
            var _this = this;
            $.post({
                url: ajaxurl,
                data: {
                    action: 'delete_element',
                    data: {
                        element_id: args.id,
                    }
                    // nonce_field: custom_ajax_vars.nonce
                }
            }).done(function (response) {
                if (200 == response.status) {
                    _this.notice(response.message, 'notice-success');
                } else {
                    _this.notice(response.message, 'notice-warning');
                }
            }).fail(function (e) {
                _this.notice('Something went wrong', 'notice-error');
            });
        },
        attachEvents: function () {
            var _this = this;

            $(document.body).on('click', '#add-queue', function (e) {
                e.preventDefault();
                _this.add();
            });

            $(document.body).on('click', '#delete-queue', function (e) {
                e.preventDefault();
                var id_to_remove = $("#queues option:selected").val();
                if ('0' !== id_to_remove) {
                    _this.delete(id_to_remove);
                } else {
                    _this.notice('Select a queue from the list first.', 'notice-warning');
                }
            });

            $(document.body).on('click', '#disable-queue', function (e) {
                e.preventDefault();
                var id_to_remove = $("#queues option:selected").val();
                if ('0' !== id_to_remove) {
                    _this.disable(id_to_remove);
                } else {
                    _this.notice('Select a queue from the list first.', 'notice-warning');
                }
            });

            $(document.body).on('click', '#is-empty-queue', function (e) {
                e.preventDefault();
                var id_to_check = $("#queues option:selected").val();
                if ('0' !== id_to_check) {
                    _this.is_empty(id_to_check);
                } else {
                    _this.notice('Select a queue from the list first.', 'notice-warning');
                }
            });

            $(document.body).on('click', '#queue_elements', function (e) {
                e.preventDefault();
                //Populate edit fields
                $('#edit-element').prop('disabled', false);
                $('#delete-element').prop('disabled', false);
                var data = JSON.parse( decodeURI( this.selectedOptions[0].dataset.serialized));
                $('#element_id').val(data.id);
                $('#element_name').val(data.name);
                $('#element_priority').val(data.priority);
                $('textarea[name="element_data[' + data.type + ']"]').val(data.data);
                $('#element_types > option').each(function () {
                    if($(this).text().toLowerCase() == data.type){
                        $(this).attr('selected', 'selected');
                        $(this).parent().trigger('change');
                    }
                });
            });

            $(document.body).on('change', '#queues', function (e) {
                e.preventDefault();
                var queue_id = $("#queues option:selected").val();
                if ('0' !== queue_id) {
                    _this.get_all_elements(queue_id);
                } else {
                    $('#queue_elements').empty();
                }
            });

            $(document.body).on('change', '#element_types', function (e) {
                e.preventDefault();
                switch (this.value) {
                    case '1' : //SCRIPT
                        $('textarea[name="element_data[script]"]').removeClass('hidden').addClass('current-element');
                        break;
                    default:
                        $('.current-element').addClass('hidden').removeClass('current-element');
                        break;
                }
            });

            $(document.body).on('click', '#insert-element', function (e) {
                e.preventDefault();
                var args = {};
                args.name = $("#element_name").val();
                args.queue_id = $("#queues option:selected").val();
                args.type = $("#element_types option:selected").text().toLowerCase();
                args.priority = $("#element_priority").val();
                args.data = $('[name="element_data[' + args.type + ']"]').val();
                if ('0' !== args.queue_id) {
                    _this.insert_element(args);
                } else {
                    _this.notice('Select a queue from the list first.', 'notice-warning');
                }

            });

            $(document.body).on('click', '#edit-element', function (e) {
                e.preventDefault();
                var args = {};
                args.id = $("#element_id").val();
                args.name = $("#element_name").val();
                args.queue_id = $("#queues option:selected").val();
                args.type = $("#element_types option:selected").text().toLowerCase();
                args.priority = $("#element_priority").val();
                args.data = $('[name="element_data[' + args.type + ']"]').val();
                if ('0' !== args.queue_id) {
                    _this.edit_element(args);
                } else {
                    _this.notice('Select a queue from the list first.', 'notice-warning');
                }

            });

            $(document.body).on('click', '#delete-element', function (e) {
                e.preventDefault();
                var args = {};
                args.id = $("#element_id").val();
                args.queue_id = $("#queues option:selected").val();
                if ('0' !== args.queue_id) {
                    _this.delete_element(args);
                } else {
                    _this.notice('Select a queue from the list first.', 'notice-warning');
                }

            });
        },
        notice: function (text, mode) {
            var $queue_notice = $('.queue-notice');
            $queue_notice.html('<p>' + text + '</p>').addClass(mode).removeClass('hidden');
            setTimeout(function () {
                $queue_notice.addClass('hidden').removeClass(mode).html('<p></p>');
            }, 4000);
        }
    };

    Queue.init();
})(jQuery);

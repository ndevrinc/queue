var Events = Events || {};
(function ($, Queue) {
    Events = {
        attach_handlers: function () {
            $(document.body).on('click', '#add-queue', function (e) {
                e.preventDefault();
                Queue.add();
            });

            $(document.body).on('click', '#delete-queue', function (e) {
                e.preventDefault();
                var id_to_remove = $("#queues option:selected").val();
                if ('0' !== id_to_remove) {
                    Queue.delete(id_to_remove);
                } else {
                    Queue.notice('Select a queue from the list first.', 'notice-warning');
                }
            });

            $(document.body).on('click', '#disable-queue', function (e) {
                e.preventDefault();
                var id_to_remove = $("#queues option:selected").val();
                if ('0' !== id_to_remove) {
                    Queue.disable(id_to_remove);
                } else {
                    Queue.notice('Select a queue from the list first.', 'notice-warning');
                }
            });

            $(document.body).on('click', '#is-empty-queue', function (e) {
                e.preventDefault();
                var id_to_check = $("#queues option:selected").val();
                if ('0' !== id_to_check) {
                    Queue.is_empty(id_to_check);
                } else {
                    Queue.notice('Select a queue from the list first.', 'notice-warning');
                }
            });

            $(document.body).on('click', '#queue_elements', function (e) {
                e.preventDefault();
                //Populate edit fields
                $('#edit-element').prop('disabled', false);
                $('#delete-element').prop('disabled', false);
                var data = JSON.parse(decodeURI(this.selectedOptions[0].dataset.serialized));
                $('#element_id').val(data.id);
                $('#element_name').val(data.name);
                $('#element_priority').val(data.priority);
                $('textarea[name="element_data[' + data.type + ']"]').val(data.data);
                $('#element_types > option').each(function () {
                    if ($(this).text().toLowerCase() == data.type) {
                        $(this).attr('selected', 'selected');
                        $(this).parent().trigger('change');
                    }
                });
            });

            $(document.body).on('change', '#queues', function (e) {
                e.preventDefault();
                var queue_id = $("#queues option:selected").val();
                if ('0' !== queue_id) {
                    Queue.get_all_elements(queue_id);
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
                    Queue.insert_element(args);
                } else {
                    Queue.notice('Select a queue from the list first.', 'notice-warning');
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
                    Queue.edit_element(args);
                } else {
                    Queue.notice('Select a queue from the list first.', 'notice-warning');
                }

            });

            $(document.body).on('click', '#delete-element', function (e) {
                e.preventDefault();
                var args = {};
                args.id = $("#element_id").val();
                args.queue_id = $("#queues option:selected").val();
                if ('0' !== args.queue_id) {
                    Queue.delete_element(args);
                } else {
                    Queue.notice('Select a queue from the list first.', 'notice-warning');
                }

            });
        }
    };
    Events.attach_handlers();
})(jQuery, Queue);


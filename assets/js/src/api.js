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
        is_empty: function(queue_id) {
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

            $(document.body).on('click', '#is-empty-queue', function (e) {
                e.preventDefault();
                var id_to_check = $("#queues option:selected").val();
                if ('0' !== id_to_check) {
                    _this.is_empty(id_to_check);
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

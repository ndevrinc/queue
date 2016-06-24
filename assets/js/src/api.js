var Queue = Queue || {};
(function ($) {
    Queue = {
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
                    $('#queues').trigger('change');
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
                    $('#queues').trigger('change');
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
                    $('#queues').trigger('change');
                } else {
                    _this.notice(response.message, 'notice-warning');
                }
            }).fail(function (e) {
                _this.notice('Something went wrong', 'notice-error');
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
})(jQuery);

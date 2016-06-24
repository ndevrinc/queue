var Queue = Queue || {};
(function ($) {
    Queue = {
        add: function () {
            return $.post({
                url: ajaxurl,
                data: {
                    action: 'add_queue'
                    // nonce_field: custom_ajax_vars.nonce
                }
            });
        },
        delete: function (queue_id) {
            return $.post({
                url: ajaxurl,
                data: {
                    action: 'delete_queue',
                    data: {
                        queue_id: queue_id
                    }
                    // nonce_field: custom_ajax_vars.nonce
                }
            });
        },
        disable: function (queue_id) {
            return $.post({
                url: ajaxurl,
                data: {
                    action: 'disable_queue',
                    data: {
                        queue_id: queue_id
                    }
                    // nonce_field: custom_ajax_vars.nonce
                }
            });
        },
        is_empty: function (queue_id) {
            return $.post({
                url: ajaxurl,
                data: {
                    action: 'is_empty_queue',
                    data: {
                        queue_id: queue_id
                    }
                    // nonce_field: custom_ajax_vars.nonce
                }
            });
        },
        get_all_elements: function (queue_id) {
            return $.post({
                url: ajaxurl,
                data: {
                    action: 'get_all_elements',
                    data: {
                        queue_id: queue_id
                    }
                    // nonce_field: custom_ajax_vars.nonce
                }
            });
        },
        insert_element: function (args) {
            return $.post({
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
            });
        },
        edit_element: function (args) {
            return $.post({
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
            });
        },
        delete_element: function (args) {
            return $.post({
                url: ajaxurl,
                data: {
                    action: 'delete_element',
                    data: {
                        element_id: args.id
                    }
                    // nonce_field: custom_ajax_vars.nonce
                }
            });
        },
        peek: function(args) {
            return $.post({
                url: ajaxurl,
                data: {
                    action: 'peek_element',
                    data: {
                        queue_id: args.queue_id
                    }
                    // nonce_field: custom_ajax_vars.nonce
                }
            });
        },
        pop: function (args) {
            return $.post({
                url: ajaxurl,
                data: {
                    action: 'pop_element',
                    data: {
                        queue_id: args.queue_id
                    }
                    // nonce_field: custom_ajax_vars.nonce
                }
            });
        }
    };
})(jQuery);

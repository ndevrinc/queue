var Events = Events || {};
(function ($, Queue) {
    Events = {
        attach_handlers: function () {
            var _this = this;

            $.ajax( {
                url: wpApiSettings.root + 'queue/v1/peek',
                method: 'POST',
                beforeSend: function ( xhr ) {
                    xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
                },
                data:{
                    'title' : 'Hello Moon'
                }
            } ).done( function ( response ) {
                console.log( response );
            } );

            /**
             * Handles the hide and show for the queue types
             */
            $(document.body).on('change', '#queue_element_type', function (e) {
                e.preventDefault();
                var element_name = $(this).val();
                //Hide all
                $('div[id$="-queue-type"]').hide();
                if ('' !== element_name) {
                    //Show relevant div
                    $('#' + element_name + '-queue-type').show();
                }
            });
        },
        notice: function (text, mode) {
            var $queue_notice = $('.queue-notice');
            $queue_notice.removeClass().addClass('queue-notice notice is-dismissible').html('<p></p>');
            $queue_notice.html('<p>' + text + '</p>').addClass(mode);
        }
    };
    Events.attach_handlers();
})(jQuery, Queue);


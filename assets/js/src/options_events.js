var Events = Events || {};
(function ($, Queue) {
    Events = {
        attach_handlers: function () {
            var _this = this;
            $(document.body).on('click', '#add-queue', function (e) {
                e.preventDefault();
                Queue.add().done(function (response) {
                    if (200 == response.status) {
                        $('#queues').append('<option value="' + response.queue.id + '">Queue #' + response.queue.id + '</option>');
                        _this.notice(response.message, 'notice-success');
                    } else {
                        _this.notice(response.message, 'notice-warning');
                    }
                }).fail(function () {
                    _this.notice('Something went wrong', 'notice-error');
                });
            });

            $(document.body).on('click', '#delete-queue', function (e) {
                e.preventDefault();
                var id_to_remove = $("#queues option:selected").val();
                if ('0' !== id_to_remove) {
                    Queue.delete(id_to_remove).done(function (response) {
                        if (200 == response.status) {
                            $("#queues option[value='" + response.queue.id + "']").remove();
                            _this.notice(response.message, 'notice-success');
                        } else {
                            _this.notice(response.message, 'notice-warning');
                        }
                    }).fail(function (e) {
                        _this.notice('Something went wrong', 'notice-error');
                    });
                } else {
                    _this.notice('Select a queue from the list first.', 'notice-warning');
                }
            });

            $(document.body).on('click', '#disable-queue', function (e) {
                e.preventDefault();
                var id_to_remove = $("#queues option:selected").val();
                if ('0' !== id_to_remove) {
                    Queue.disable(id_to_remove).done(function (response) {
                        if (200 == response.status) {
                            $("#queues option[value='" + response.queue.id + "']").remove();
                            _this.notice(response.message, 'notice-success');
                        } else {
                            _this.notice(response.message, 'notice-warning');
                        }
                    }).fail(function (e) {
                        _this.notice('Something went wrong', 'notice-error');
                    });
                } else {
                    _this.notice('Select a queue from the list first.', 'notice-warning');
                }
            });

            $(document.body).on('click', '#is-empty-queue', function (e) {
                e.preventDefault();
                var id_to_check = $("#queues option:selected").val();
                if ('0' !== id_to_check) {
                    Queue.is_empty(id_to_check).done(function (response) {
                        if (200 == response.status) {
                            _this.notice(response.message, 'notice-success');
                        } else {
                            _this.notice(response.message, 'notice-warning');
                        }
                    }).fail(function (e) {
                        _this.notice('Something went wrong', 'notice-error');
                    });
                } else {
                    _this.notice('Select a queue from the list first.', 'notice-warning');
                }
            });

            $(document.body).on('click', '#queue_elements', function (e) {
                e.preventDefault();
                //Populate edit fields
                $('#edit-element').prop('disabled', false);
                $('#delete-element').prop('disabled', false);
                var data = JSON.parse(decodeURI(this.selectedOptions[0].dataset.serialized));
                _this.populate_element(data);
            });

            $(document.body).on('change', '#queues', function (e) {
                e.preventDefault();
                var queue_id = $("#queues option:selected").val(),
                    $queue_elements = $('#queue_elements');
                if ('0' !== queue_id) {
                    Queue.get_all_elements(queue_id).done(function (response) {
                        if (200 == response.status) {
                            _this.notice(response.message, 'notice-success');
                            var elements = JSON.parse(response.queue.elements);
                            $queue_elements.empty();
                            for (var i = 0; i < elements.length; i++) {
                                var element_id = elements[i].id,
                                    element_name = elements[i].name;
                                $queue_elements.append('<option value="' + element_id + '" data-serialized="' + encodeURI(JSON.stringify(elements[i])) + '">#' + element_id + ' - ' + element_name + '</option>');
                            }
                        } else {
                            _this.notice(response.message, 'notice-warning');
                            $queue_elements.empty();
                        }
                    }).fail(function (e) {
                        _this.notice('Something went wrong', 'notice-error');
                    });
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
                    Queue.insert_element(args).done(function (response) {
                        if (200 == response.status) {
                            _this.notice(response.message, 'notice-success');
                            $('#queues').trigger('change');
                        } else {
                            _this.notice(response.message, 'notice-warning');
                        }
                    }).fail(function (e) {
                        _this.notice('Something went wrong', 'notice-error');
                    });
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
                    Queue.edit_element(args).done(function (response) {
                        if (200 == response.status) {
                            _this.notice(response.message, 'notice-success');
                            $('#queues').trigger('change');
                        } else {
                            _this.notice(response.message, 'notice-warning');
                        }
                    }).fail(function (e) {
                        _this.notice('Something went wrong', 'notice-error');
                    });
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
                    Queue.delete_element(args).done(function (response) {
                        if (200 == response.status) {
                            _this.notice(response.message, 'notice-success');
                            $('#queues').trigger('change');
                        } else {
                            _this.notice(response.message, 'notice-warning');
                        }
                    }).fail(function (e) {
                        _this.notice('Something went wrong', 'notice-error');
                    });
                } else {
                    _this.notice('Select a queue from the list first.', 'notice-warning');
                }

            });

            $(document.body).on('click', '#peek-element', function (e) {
                e.preventDefault();
                var args = {};
                args.queue_id = $("#queues option:selected").val();
                if ('0' !== args.queue_id) {
                    Queue.peek(args).done(function (response) {
                        if (200 == response.status) {
                            _this.notice(response.message, 'notice-success');
                            _this.populate_element(JSON.parse(response.queue.element));
                        } else {
                            _this.notice(response.message, 'notice-warning');
                        }
                    }).fail(function (e) {
                        _this.notice(response.message, 'notice-error');
                    });
                } else {
                    _this.notice('Select a queue from the list first.', 'notice-warning');
                }
            });

            $(document.body).on('click', '#pop-element', function (e) {
                e.preventDefault();
                var args = {};
                args.queue_id = $("#queues option:selected").val();
                if ('0' !== args.queue_id) {
                    Queue.pop(args).done(function (response) {
                        if (200 == response.status) {
                            _this.notice(response.message, 'notice-success');
                            _this.populate_element(JSON.parse(response.queue.element));
                        } else {
                            _this.notice(response.message, 'notice-warning');
                        }
                    }).fail(function (e) {
                        _this.notice(response.message, 'notice-error');
                    });
                } else {
                    _this.notice('Select a queue from the list first.', 'notice-warning');
                }

            });
        },
        populate_element: function (data) {
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
        },
        notice: function (text, mode) {
            var $queue_notice = $('.queue-notice');
            $queue_notice.removeClass().addClass('queue-notice notice is-dismissible').html('<p></p>');
            $queue_notice.html('<p>' + text + '</p>').addClass(mode);
        }
    };
    Events.attach_handlers();
})(jQuery, Queue);


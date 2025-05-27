define(function(require) {
    const BaseComponent = require('oroui/js/app/components/base/component');
    const $ = require('jquery');
    const routing = require('routing');
    const messenger = require('oroui/js/messenger');
    const __ = require('orotranslation/js/translator');

    const CheckboxCell = BaseComponent.extend({

        options: {
            title: null,
            route: null,
            routeParams: null,
            checked: false,
            disabled: false,
            flashMessage: null,
            flashErrorMessage: null
        },

        constructor: function CheckboxCell(options) {
            CheckboxCell.__super__.constructor.call(this, options);
        },

        initialize(options) {
            CheckboxCell.__super__.initialize.call(this, options);
            this.options = $.extend(true, {}, this.options, options || {});

            const $el = $(options._sourceElement);
            const $checkbox = $('<input type="checkbox">')
                .prop('checked', options.checked)
                .prop('disabled', options.disabled)
                .on('change', () => {
                    $.ajax({
                        url: routing.generate(options.route, Object.assign({}, options.routeParams, {
                            value: $checkbox.is(':checked')
                        })),
                        type: 'PATCH',
                        success: () => {
                            const message = options.flashMessage ? __(options.flashMessage) : __('Saved');
                            messenger.notificationFlashMessage('success', message);
                        },
                        error: () => {
                            $checkbox.prop('checked', !$checkbox.is(':checked'));
                            if (options.flashErrorMessage) {
                                messenger.notificationFlashMessage('error', __(options.flashErrorMessage));
                            }
                        }
                    });
                });

            $el.empty().append($checkbox);

            if (options.title) {
                const $label = $('<label class="form-check-label">')
                    .html(options.title).prepend($checkbox);
                $el.append($label);
            }
        }
    });

    return CheckboxCell;
});

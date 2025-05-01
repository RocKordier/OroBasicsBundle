define(function(require) {
    const BaseComponent = require('oroui/js/app/components/base/component');
    const $ = require('jquery');
    const routing = require('routing');

    const CheckboxCell = BaseComponent.extend({

        options: {
            title: null,
            route: null,
            routeParams: null,
            checked: false,
            disabled: false
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
                        error: () => $checkbox.prop('checked', ! $checkbox.is(':checked'))
                    });
                });

            $el.empty().append($checkbox);

            if (options.title) {
                const $label = $('<label class="form-check-label">')
                    .text(options.title).prepend($checkbox);
                $el.append($label);
            }
        }
    });

    return CheckboxCell;
});

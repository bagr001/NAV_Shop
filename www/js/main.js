/*(function ($, undefined) {

    $.nette.ext('spinner', {
        init: function (x) {
            this.spinner = this.createSpinner();
            this.spinner.appendTo('body');
        },
        start: function (jqXHR, settings) {
//            this.spinner.css({
//                left: '50%',
//                top: '30%'
//            });
            this.spinner.show(this.speed);
        },
        complete: function () {
            this.spinner.hide(this.speed);
        }
    }, {
        createSpinner: function () {
            var spinner = $('<div>', {
                id: 'ajax-spinner',
                css: {
                    display: 'none',
                }
            });
            // -- delete if you use bacground image, no ico
//            var icon = $('<i>', {
//                class: 'icon-gear icon-spin icon-4x'
//            });
//            spinner.append(icon);
            // -- delete if you use bacgroun image, no ico
            return spinner;
        },
        spinner: null,
        speed: undefined
    });

})(jQuery);
*/
$(function () {
	/* inicializace nette ajax */
	$.nette.init();

});

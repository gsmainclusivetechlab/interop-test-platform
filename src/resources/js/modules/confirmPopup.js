const { confirmPopupDefaults } = require('../constants');

module.exports = (($) => {
    const init = () => {
        const $confirmBtnSelector = $('[data-confirm-form]');

        if (!$confirmBtnSelector.length) {
            return;
        }

        $confirmBtnSelector.each((index, confirmBtn) => {
            const {
                confirmTitle: titleText,
                confirmText: text,
                confirmIcon: icon
            } = confirmBtn.dataset;

            $(confirmBtn).on('click', (e) => {
                const form = e.target.form;
                e.preventDefault();
                Swal.fire({
                    ...confirmPopupDefaults,
                    titleText,
                    text,
                    icon
                }).then((result) => {
                    if (result.value) {
                        form.submit();
                    }
                });
            });
        });
    };

    return {
        init
    };
})(jQuery);

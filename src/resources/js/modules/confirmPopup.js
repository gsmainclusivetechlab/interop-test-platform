const { confirmPopupDefaults } = require('../constants');

module.exports = (($) => {
    const init = () => {
        const $confirmBtnSelector = $('[data-confirm]');

        if (!$confirmBtnSelector.length) {
            return;
        }

        $confirmBtnSelector.each((index, confirmBtn) => {
            const {
                confirmTitle: titleText,
                confirmText: text,
                confirmIcon: icon
            } = confirmBtn.dataset;

            $(confirmBtn).on('click', () => {
                Swal.fire({
                    ...confirmPopupDefaults,
                    titleText,
                    text,
                    icon
                });
            });
        });
    };

    return {
        init
    };
})(jQuery);

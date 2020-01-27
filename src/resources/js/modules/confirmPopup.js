const CONFIRM_BUTTON_TEXT = 'Confirm';
const CONFIRM_ICON_TYPE = 'error';

const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-primary mr-4',
        cancelButton: 'btn btn-secondary'
    },
    showCancelButton: true,
    buttonsStyling: false,
    confirmButtonText: CONFIRM_BUTTON_TEXT
});

module.exports = (($) => {
    const init = () => {
        const $confirmBtnSelector = $('[data-confirm-form]');

        if (!$confirmBtnSelector.length) {
            return;
        }

        $confirmBtnSelector.each((index, confirmBtn) => {
            const {
                confirmTitle: title,
                confirmText: text,
                confirmIcon: icon = CONFIRM_ICON_TYPE
            } = confirmBtn.dataset;

            $(confirmBtn).on('click', (e) => {
                e.preventDefault();

                const form = e.target.form;

                swalWithBootstrapButtons.fire({
                    title,
                    text,
                    icon,
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

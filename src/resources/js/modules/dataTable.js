module.exports = (($) => {
    const init = () => {
        const $dataTableSelector = $('[data-datatable]');

        if (!$dataTableSelector.length) {
            return;
        }

        $dataTableSelector.DataTable();
    };

    return {
        init
    };
})(jQuery);

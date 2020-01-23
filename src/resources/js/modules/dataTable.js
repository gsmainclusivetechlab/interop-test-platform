module.exports = (($) => {
    const init = () => {
        $.fn.dataTable.ext.errMode = 'none';
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

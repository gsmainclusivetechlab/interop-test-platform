module.exports = (($) => {
    const init = () => {
        $('[data-datatable]').DataTable();
    };

    return {
        init
    };
})(jQuery);

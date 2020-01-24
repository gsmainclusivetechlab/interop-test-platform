module.exports = (($) => {
    const init = () => {
        const $flowChart = $('[data-flow-chart]');

        if (!$flowChart.length) {
            return;
        }

        const IS_ACTIVE = 'is-active';
        const IS_INACTIVE = 'is-inactive';
        const GET = 'get';
        const POST = 'POST';

        const FLOW_ITEM_SELECTOR = 'flow-item';
        const FLOW_BUTTON_SELECTOR = 'flow-btn';

        $flowChart.on('click', (e) => {
            const $target = $(e.target);

            if ($target.is(`.${FLOW_BUTTON_SELECTOR}`)) {
                return;
            }

            $target.siblings().removeClass(IS_ACTIVE);
            $target.prevAll().addClass(IS_INACTIVE);
            $target.addClass(IS_ACTIVE);
        });
    };

    return {
        init
    };
})(jQuery);

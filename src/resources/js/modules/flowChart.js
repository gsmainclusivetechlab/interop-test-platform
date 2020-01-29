module.exports = (($) => {
    const init = () => {
        var config = {
            securityLevel: 'loose',
            flowchart: {
                curve: 'cardinal',
            },
            theme: 'neutral',
            themeCSS: `
                .clickable:hover rect { fill: #de002b !important }
                .clickable:hover .label { color: #fff !important }
                .is-active rect { fill: #de002b !important }
                .is-active .label { color: #fff !important }
            `,
        };

        mermaid.initialize(config);
    };

    return {
        init
    };
})(jQuery);

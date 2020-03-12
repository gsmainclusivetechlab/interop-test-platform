import jsonTree from '../libs/jsonTree';

export default (() => {
    const init = () => {
        const wrapper = document.querySelectorAll('.json-tree');

        wrapper.forEach((el) => {
            const code = el.querySelector('.json-tree-code');

            try {
                const data = JSON.parse(code.innerHTML);
                const tree = jsonTree.create(data, el);
            } catch (e) {
                throw e;
            }
        });
    };

    return {
        init,
    };
})();

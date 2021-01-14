function isSelectable(option, list) {
    if (Array.isArray(list)) {
        return !list
            .map((item) => JSON.stringify(item))
            .includes(JSON.stringify(option));
    }
    return !(JSON.stringify(list) === JSON.stringify(option));
}

export { isSelectable };

function strToRegexp(str) {
    return new RegExp(str.replace(/[|\\{}()[\]^$+*?.]/g, '\\$&'));
}

export {
    strToRegexp,
};

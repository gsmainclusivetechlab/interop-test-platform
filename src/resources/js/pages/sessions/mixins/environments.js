/**
 * @typedef Var
 * @property {"text" | "file"} type
 * @property {string} key
 * @property {string | undefined} value
 * @property {string} file_name
 */

export default {
    methods: {
        /**
         * Return a list of environment objects constructed from the existing list joined with an incoming list
         * @param {Var[]} current
         * @param {Var[] | undefined} incoming
         */
        mergeStandard(current, incoming = []) {
            return (
                current
                    // first update any existing variables which have a blank value but exist in the incoming object
                    .map((current) => {
                        // don't touch entries which already have a value
                        if (current.value) return current;

                        const match = incoming.find(
                            (i) => i.key === current.key
                        );
                        if (!match) return current;

                        // we found a match and the existing is null, so output the match
                        return match;
                    })
                    // now append any incoming variables which do not exist in the current env
                    .concat(
                        incoming.filter(
                            (incoming) =>
                                !current.some(
                                    (current) => current.key === incoming.key
                                )
                        )
                    )
            );
        },

        /**
         *
         * @param {{variables?: Record<string, string>, files?: Var[]}[]} groupsEnvs
         * @param {Var[]} vars
         * @returns
         */
        mergeGroupsEnvs(groupsEnvs, vars) {
            if (!groupsEnvs?.length) return;

            let newEnv = vars;
            for (let group of groupsEnvs) {
                newEnv = this.mergeStandard(
                    newEnv,
                    Object.entries(group?.variables).map(([key, value]) => ({
                        key,
                        value,
                        type: 'text',
                    }))
                );
                newEnv = this.mergeStandard(
                    newEnv,
                    group?.files.map((file) => ({
                        type: 'file',
                        key: file.name,
                        value: file.id,
                        file_name: file.file_name,
                    }))
                );
            }
            return newEnv;
        },

        /**
         *
         * @param {{env: string[], file_env: string[]}} incomingKeys
         * @param {Var[]} current
         * @returns
         */
        mergeTestCasesEnvs(incomingKeys, current) {
            let newEnv = current;
            newEnv = this.mergeStandard(
                newEnv,
                incomingKeys?.env.map((key) => ({
                    key,
                    type: 'text',
                    value: null,
                }))
            );
            newEnv = this.mergeStandard(
                newEnv,
                incomingKeys?.file_env.map((key) => ({
                    key,
                    type: 'file',
                    value: null,
                    file_name: null,
                }))
            );
            return newEnv;
        },
        loadGroupsEnvsList(query = '') {
            return axios.get(
                route('sessions.register.group-environment-candidates'),
                {
                    params: { q: query },
                }
            );
        },
        loadTestCasesEnvs(testCasesIds) {
            return axios.post(
                route('admin.test-cases.environment-candidates'),
                {
                    testCasesIds: testCasesIds,
                }
            );
        },

        /**
         *
         * @param {Record<string, string | null>} texts
         * @param {Array<{file_name: string, file_path: string, id: string, name: string}>} files
         * @returns {Var[]}
         */
        combineEnv(texts = {}, files = []) {
            return Object.entries(texts || {})
                ?.map(([key, value]) => ({
                    key: key,
                    value: value,
                    type: 'text',
                }))
                .concat(
                    files.map((el) => ({
                        key: el.name,
                        value: el.id,
                        file_name: el.file_name,
                        type: 'file',
                    }))
                );
        },

        /**
         *
         * @param {Var[]} combined
         * @returns {{variables: Record<string, string>, files: Record<string, string>}}
         */
        separateEnv(combined) {
            return {
                variables: combined
                    ?.filter(({ type, key }) => type === 'text' && key)
                    .reduce(
                        (obj, { key, value }) => ((obj[key] = value), obj),
                        {}
                    ),
                files: combined
                    ?.filter(({ type, key }) => type === 'file' && key)
                    .reduce(
                        (obj, { key, value }) => ((obj[key] = value), obj),
                        {}
                    ),
            };
        },
    },
};

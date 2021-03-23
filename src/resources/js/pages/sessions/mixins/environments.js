export default {
    methods: {
        mergeEnvs(incomingEnvs, currentEnvs, envsType) {
            switch (envsType) {
                case 'text': {
                    Object.keys(currentEnvs).forEach(function (key) {
                        if (
                            (currentEnvs[key].value === null ||
                                currentEnvs[key].value === '') &&
                            incomingEnvs[currentEnvs[key].key]
                        ) {
                            currentEnvs[key].value =
                                incomingEnvs[currentEnvs[key].key];
                        }
                    });
                    Object.entries(incomingEnvs)
                        .filter(
                            ([key, value]) =>
                                !currentEnvs.some((el) => el.key === key)
                        )
                        .forEach(([key, value]) =>
                            currentEnvs.push({
                                key: key,
                                value: value,
                            })
                        );
                    break;
                }
                case 'file': {
                    currentEnvs.forEach(function (current) {
                        if (!current.file_name || !current.value) {
                            const incoming = incomingEnvs.find(
                                (incoming) => incoming.name === current.key
                            );
                            if (incoming) {
                                current.value = incoming.id;
                                current.file_name = incoming.file_name;
                            }
                        }
                    });
                    incomingEnvs
                        .filter(
                            (incoming) =>
                                !currentEnvs.some(
                                    (current) => current.key === incoming.name
                                )
                        )
                        .forEach((incoming) =>
                            currentEnvs.push({
                                key: incoming.name,
                                value: incoming.id,
                                file_name: incoming.file_name,
                            })
                        );
                    break;
                }
                default:
                    break;
            }
        },
        mergeGroupsEnvs(groupsEnvs, envs, fileEnvs) {
            if (!groupsEnvs?.length) return;

            groupsEnvs.forEach((env) => {
                this.mergeEnvs(env.variables, envs, 'text');
                this.mergeEnvs(env.files, fileEnvs, 'file');
            });
        },
        mergeTestCasesEnvs(incomingList, envs, fileEnvs) {
            if (incomingList?.env?.length) {
                const incomingEnvs = Object.fromEntries(
                    incomingList.env.map((key) => [key, null])
                );

                this.mergeEnvs(incomingEnvs, envs, 'text');
            }
            if (incomingList?.file_env?.length) {
                const incomingFileEnvs = incomingList.file_env.map((key) => ({
                    name: key,
                    value: null,
                    file_name: null,
                }));

                this.mergeEnvs(incomingFileEnvs, fileEnvs, 'file');
            }
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
    },
};

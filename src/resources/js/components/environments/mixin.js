export default {
    methods: {
        mergeEnvs(incomingEnvs, currentEnvs, envsType) {
            switch (envsType) {
                case 'text': {
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
    },
};

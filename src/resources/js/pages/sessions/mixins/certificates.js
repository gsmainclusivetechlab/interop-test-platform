export default {
    methods: {
        changeEncryption(component, use) {
            component.use_encryption = use ? 1 : 0;
            Object.keys(component.certificate).forEach(
                (crt) => (component.certificate[crt] = null)
            );
        },
        loadGroupCertificateList(sessionId, query = '') {
            return axios.get(
                route('sessions.register.group-certificate-candidates'),
                {
                    params: {
                        q: query,
                        session: sessionId,
                    },
                }
            );
        },
    },
};

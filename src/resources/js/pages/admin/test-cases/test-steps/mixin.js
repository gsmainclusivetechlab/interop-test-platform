export default {
    metaInfo() {
        return {
            title: `${this.testCase.name} - ${this.$t('card.title')}`,
        };
    },
    props: {
        testCase: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            component: {
                list: this.$page.props.components.map((el) => el.name),
                connections: new Map(
                    this.$page.props.components.map((el) => [
                        el.name,
                        el.connections.data.map(
                            (connection) => connection.name
                        ),
                    ])
                ),
            },
            method: {
                list: collect(this.$page.props.methods).toArray(),
            },
            apiSpec: {
                list: this.$page.props.apiSpecs.map((el) => el.name),
            },
            example: {
                response: {
                    status: {
                        list: collect(this.$page.props.statuses).toArray(),
                    },
                },
            },
        };
    },
    methods: {
        getForm() {
            return {
                api_spec_id:
                    this.$page.props.apiSpecs.filter(
                        (el) => el.name === this.apiSpec.selected
                    )?.[0]?.id ?? null,
                method: collect(this.$page.props.methods)
                    .flip()
                    .get(this.method.selected),
                path: this.path,
                mtls: this.mtls,
                pattern: this.pattern ?? null,
                source_id: this.$page.props.components.filter(
                    (el) => el.name === this.source
                )?.[0]?.id,
                target_id: this.$page.props.components.filter(
                    (el) => el.name === this.target
                )?.[0]?.id,
                trigger: this.trigger,
                request: this.example.request,
                response: {
                    delay: this.example.response.delay,
                    status: collect(this.$page.props.statuses)
                        .flip()
                        .get(this.example.response.status.selected),
                    headers: this.example.response.headers,
                    body: this.example.response.body,
                },
                test: {
                    scripts: {
                        request: this.test.scripts.request.list,
                        response: this.test.scripts.response.list,
                    },
                },
            };
        },
        addFormItem(formsList, formPattern) {
            formsList.push(formPattern);
        },
        deleteFormItem(formList, i) {
            formList.splice(i, 1);
        },
        toggleEmptyBody(e) {
            if (e.target.checked) {
                return 'empty_body';
            } else {
                return null;
            }
        },
    },
    computed: {
        sourceList() {
            const list = this.component.connections.get(this.target);

            if (list) return list;

            return this.component.list;
        },
        targetList() {
            const list = this.component.connections.get(this.source);

            if (list) return list;

            return this.component.list;
        },
    },
};

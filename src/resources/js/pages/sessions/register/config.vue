<template>
    <layout :components="components" :session="session">
        <form @submit.prevent="submit" class="col-8 m-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Configure components</h3>
                    <div class="card-options">
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="getTestCaseEnv"
                        >
                            Get Test Cases Env
                        </button>
                    </div>
                </div>
                <div class="card-body" v-if="groupsDefaultList.length">
                    <label class="form-label">Group Default Sessions</label>
                    <p>
                        You can choose to make this session the default session
                        for your group to allow your components to continue
                        using a static URL. By making this the default session,
                        your components will stop sending traffic to any
                        previously selected sessions.
                    </p>
                    <v-select
                        v-model="form.groupsDefault"
                        :options="groupsDefaultList"
                        multiple
                        label="name"
                        placeholder="Groups"
                        :selectable="
                            (option) =>
                                isSelectable(option, form.groupsDefault) &&
                                isSelectable(
                                    option,
                                    groupsDefaultList.filter(
                                        (group) => !group.isAdmin
                                    )
                                )
                        "
                        class="form-control d-flex p-0 mb-3"
                    />
                </div>

                <div class="card-body" v-if="suts.data && suts.data.length">
                    <template
                        v-if="form.groupsDefault && form.groupsDefault.length"
                    >
                        <template v-for="(group, k) in form.groupsDefault">
                            <h3 class="text-secondary mb-3" :key="`name-${k}`">
                                {{ group.name }} URLs:
                            </h3>
                            <div
                                class="mb-3"
                                v-for="(sut, i) in suts.data"
                                :key="`sut-${i}-${k}`"
                            >
                                <h3>{{ sut.name }}</h3>

                                <template
                                    v-for="(connection, j) in sut.connections"
                                >
                                    <div
                                        class="mb-3"
                                        :key="`connection-${j}`"
                                        v-if="
                                            Array.from(
                                                new Set(
                                                    testSteps.data.map(
                                                        (el) => el.target.id
                                                    )
                                                )
                                            ).includes(connection.id)
                                        "
                                    >
                                        <label class="form-label">
                                            {{ connection.name }}
                                        </label>
                                        <div class="input-group">
                                            <input
                                                :id="`testing-${connection.id}`"
                                                type="text"
                                                :value="
                                                    getRoute(
                                                        session.sut[sut.id]
                                                            .use_encryption ===
                                                            '1',
                                                        [
                                                            sut.slug,
                                                            connection.slug,
                                                            group.id,
                                                        ],
                                                        true
                                                    )
                                                "
                                                class="form-control"
                                                readonly
                                            />
                                            <clipboard-copy-btn
                                                :target="`#testing-${connection.id}`"
                                                title="Copy"
                                            ></clipboard-copy-btn>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </template>
                    <template
                        v-if="!form.groupsDefault || !form.groupsDefault.length"
                    >
                        <template v-for="(sut, i) in suts.data">
                            <h3 :key="`session-sut-${i}`">{{ sut.name }}</h3>
                            <template
                                v-for="(connection, j) in sut.connections"
                            >
                                <div
                                    class="mb-3"
                                    :key="`session-connection-${i}-${j}`"
                                    v-if="
                                        Array.from(
                                            new Set(
                                                testSteps.data.map(
                                                    (el) => el.target.id
                                                )
                                            )
                                        ).includes(connection.id)
                                    "
                                >
                                    <label class="form-label">
                                        {{ connection.name }}
                                    </label>
                                    <div class="input-group">
                                        <input
                                            :id="`testing-${connection.id}`"
                                            type="text"
                                            :value="
                                                getRoute(
                                                    session.sut[sut.id]
                                                        .use_encryption === '1',
                                                    [
                                                        sut.slug,
                                                        connection.slug,
                                                        session.info.uuid,
                                                    ]
                                                )
                                            "
                                            class="form-control"
                                            readonly
                                        />
                                        <clipboard-copy-btn
                                            :target="`#testing-${connection.id}`"
                                            title="Copy"
                                        ></clipboard-copy-btn>
                                    </div>
                                </div>
                            </template>
                        </template>
                    </template>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Environments</label>
                        <v-select
                            v-if="hasGroupEnvironments"
                            v-model="groupEnvironment"
                            :options="groupEnvironmentsList"
                            label="name"
                            placeholder="Group environment..."
                            :selectable="
                                (option) =>
                                    isSelectable(option, groupEnvironment)
                            "
                            class="form-control d-flex p-0 mb-3"
                        />
                        <environments
                            v-model="form.environments"
                            ref="environments"
                        />
                        <div
                            class="text-danger small mt-2"
                            v-if="$page.props.errors.environments"
                        >
                            <strong>{{
                                $page.props.errors.environments
                            }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"> File Environments </label>
                        <file-environments
                            v-model="form.fileEnvironments"
                            ref="fileEnvironments"
                        />
                        <div
                            class="text-danger small mt-2"
                            v-if="$page.props.errors.fileEnvironments"
                        >
                            <strong>{{
                                $page.props.errors.fileEnvironments
                            }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <inertia-link
                    :href="route('sessions.register.sut')"
                    class="btn btn-outline-primary"
                >
                    Back
                </inertia-link>
                <button type="submit" class="btn btn-primary">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    Confirm
                </button>
            </div>
        </form>
    </layout>
</template>

<script>
import { serialize } from '@/utilities/object-to-formdata';
import Layout from '@/layouts/sessions/register';
import Environments from '@/components/environments/environments';
import FileEnvironments from '@/components/environments/file-environments';
import mixinVSelect from '@/components/v-select/mixin';

export default {
    components: {
        Layout,
        Environments,
        FileEnvironments,
    },
    props: {
        session: {
            type: Object,
            required: true,
        },
        suts: {
            type: Object,
            required: true,
        },
        components: {
            type: Object,
            required: true,
        },
        hasGroupEnvironments: {
            type: Boolean,
            required: true,
        },
        testSteps: {
            type: Object,
            required: true,
        },
    },
    mixins: [mixinVSelect],
    data() {
        const ziggyConf = { ...this.route().ziggy };
        ziggyConf.baseUrl = this.$page.props.app.http_base_url;

        return {
            ziggyConf,
            sending: false,
            testCaseEnv: {
                env: [],
                fileEnv: [],
            },
            groupEnvironment: null,
            groupEnvironmentsList: [],
            groupsDefaultList: this.$page.props.auth.user.groups ?? [],
            form: {
                group_environment_id: null,
                environments: [],
                groupsDefault: null,
                fileEnvironments: null,
            },
        };
    },
    watch: {
        groupEnvironment: {
            immediate: true,
            handler(value) {
                this.form.group_environment_id = value?.id ?? null;
                if (value !== null) {
                    this.form.environments = value.variables;
                    this.$refs.environments.syncEnvironments(
                        this.form.environments
                    );

                    this.form.fileEnvironments = value.files;
                    this.$refs.fileEnvironments.syncEnvironments(
                        this.form.fileEnvironments
                    );
                }
            },
        },
    },
    mounted() {
        this.loadGroupEnvironmentList();
    },
    methods: {
        log(val) {
            console.log(val);
        },
        getTestCaseEnv() {
            axios
                .post(route('admin.test-cases.environment-candidates'), {
                    testCasesIds: this.$page.props.session.info.test_cases,
                })
                .then((data) => {
                    if (data.data?.env?.length) {
                        console.log(
                            typeof data.data.env,
                            Array.isArray(data.data.env)
                        );
                        data.data.env
                            .filter(
                                (param) =>
                                    !this.form.environments.some(
                                        (el) => el.key === param
                                    )
                            )
                            .forEach((param) =>
                                this.form.environments.push({
                                    key: param,
                                    value: null,
                                })
                            );
                    }
                    if (data.data?.file_env?.length) {
                        this.testCaseEnv.fileEnv = data.data.file_env;
                        this.form.fileEnvironments = Object.assign(
                            {},
                            this.testCaseEnv.fileEnv.map((param) => ({
                                name: param,
                                id: null,
                                value: null,
                            })),
                            this.form.fileEnvironments
                        );
                        this.$refs.fileEnvironments.syncEnvironments(
                            this.form.fileEnvironments
                        );
                    }
                });
        },
        submit() {
            this.sending = true;
            this.$inertia.post(
                route('sessions.register.config.store'),
                serialize(this.form, {
                    indices: true,
                }),
                {
                    onFinish: () => {
                        this.sending = false;
                    },
                }
            );
        },
        loadGroupEnvironmentList(query = '') {
            axios
                .get(route('sessions.register.group-environment-candidates'), {
                    params: { q: query },
                })
                .then((result) => {
                    this.groupEnvironmentsList = result.data.data;
                });
        },
        getRoute(useEncryption, data, isForGroup = false) {
            const group = isForGroup ? '-group' : '';

            if (useEncryption) {
                return this.route('testing' + group + '.sut', data);
            }

            return this.route(
                'testing-insecure' + group + '.sut',
                data,
                undefined,
                this.ziggyConf
            );
        },
    },
};
</script>

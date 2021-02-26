<template>
    <layout :components="components" :session="session">
        <form @submit.prevent="submit" class="col-8 m-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Configure components</h3>
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

                <div v-if="suts.data && suts.data.length" class="card-body">
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
                    <template v-else>
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
                <div v-if="hasGroupEnvironments" class="card-body">
                    <label class="form-label">Groups environments</label>
                    <v-select
                        v-model="groupsEnvs"
                        multiple
                        :options="groupsEnvsList"
                        label="name"
                        placeholder="Select environments"
                        :selectable="
                            (option) => isSelectable(option, groupsEnvs)
                        "
                        class="form-control d-flex p-0 mb-1"
                    />
                    <span class="d-block text-muted small"
                        >Chose groups environments and merge with current</span
                    >
                    <div class="text-right">
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="
                                mergeGroupsEnvs(
                                    groupsEnvs,
                                    form.environments,
                                    form.fileEnvironments
                                )
                            "
                        >
                            Merge
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <label class="form-label">Test cases environments</label>
                    <span class="d-block text-muted small"
                        >Load test cases environments and merge with
                        current</span
                    >
                    <div class="text-right">
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="
                                loadTestCasesEnvs(
                                    $page.props.session.info.test_cases
                                ).then((data) => {
                                    mergeTestCasesEnvs(
                                        data.data,
                                        form.environments,
                                        form.fileEnvironments
                                    );
                                })
                            "
                        >
                            Merge
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Environments</label>
                        <environments v-model="form.environments" />
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
                        <environments
                            v-model="form.fileEnvironments"
                            envs-type="file"
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
import Environments from '@/components/environments';
import mixinVSelect from '@/components/v-select/mixin';
import mixinEnvs from '@/pages/sessions/mixins/environments';

export default {
    components: {
        Layout,
        Environments,
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
    mixins: [mixinVSelect, mixinEnvs],
    data() {
        const ziggyConf = this.route().ziggy;

        return {
            ziggyConf: {
                http: {
                    ...ziggyConf,
                    baseUrl: this.$page.props.app.testing_url_http,
                },
                https: {
                    ...ziggyConf,
                    baseUrl: this.$page.props.app.testing_url_https,
                },
            },
            sending: false,
            groupsEnvs: [],
            groupsEnvsList: [],
            groupsDefaultList: this.$page.props.auth.user.groups ?? [],
            form: {
                environments: [],
                fileEnvironments: [],
                groupsDefault: [],
            },
        };
    },
    mounted() {
        this.loadGroupsEnvsList().then((result) => {
            this.groupsEnvsList = result.data.data;
        });
        this.loadTestCasesEnvs(this.$page.props.session.info.test_cases).then(
            (data) => {
                this.mergeTestCasesEnvs(
                    data.data,
                    this.form.environments,
                    this.form.fileEnvironments
                );
            }
        );
    },
    methods: {
        submit() {
            const form = {
                environments: Object.fromEntries(
                    this.form.environments?.map((el) => [el.key, el.value]) ??
                        []
                ),
                fileEnvironments: Object.fromEntries(
                    this.form.fileEnvironments?.map((el) => [
                        el.key,
                        el.value,
                    ]) ?? []
                ),
                groupsDefault: this.form.groupsDefault,
            };

            this.sending = true;
            this.$inertia.post(
                route('sessions.register.config.store'),
                serialize(form, {
                    indices: true,
                }),
                {
                    onFinish: () => {
                        this.sending = false;
                    },
                }
            );
        },
        getRoute(useEncryption, data, isForGroup = false) {
            const group = isForGroup ? '-group' : '';

            return useEncryption
                ? this.route(
                      `testing${group}.sut`,
                      data,
                      undefined,
                      this.ziggyConf.https
                  )
                : this.route(
                      `testing-insecure${group}.sut`,
                      data,
                      undefined,
                      this.ziggyConf.http
                  );
        },
    },
};
</script>

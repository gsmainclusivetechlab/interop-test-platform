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
                        <div v-for="group in sutUrls.groups" :key="group.title">
                            <h3>
                                {{ `${group.title}` }}
                            </h3>
                            <div
                                class="mb-3"
                                v-for="(component, i) in group.items"
                                :key="component.title"
                            >
                                <h4>
                                    {{ component.title }}
                                </h4>
                                <div
                                    class="mb-3"
                                    v-for="(connection, j) in component.items"
                                    :key="connection.label"
                                >
                                    <label>
                                        <i>{{ connection.label }}</i>
                                    </label>
                                    <div class="input-group">
                                        <input
                                            :id="`#testing-${group.id}-${i}-${j}`"
                                            type="text"
                                            :value="connection.url"
                                            class="form-control"
                                            readonly
                                        />
                                        <clipboard-copy-btn
                                            :target="`#testing-${i}-${j}`"
                                            title="Copy"
                                        ></clipboard-copy-btn>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div
                            v-for="(component, i) in sutUrls.session"
                            :key="component.title"
                        >
                            <h4>
                                {{ component.title }}
                            </h4>
                            <div
                                class="mb-3"
                                v-for="(connection, j) in component.items"
                                :key="connection.label"
                            >
                                <label>
                                    <i>{{ connection.label }}</i>
                                </label>
                                <div class="input-group">
                                    <input
                                        :id="`#testing-${i}-${j}`"
                                        type="text"
                                        :value="connection.url"
                                        class="form-control"
                                        readonly
                                    />
                                    <clipboard-copy-btn
                                        :target="`#testing-${i}-${j}`"
                                        title="Copy"
                                    ></clipboard-copy-btn>
                                </div>
                            </div>
                        </div>
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
                                    form.combinedEnv,
                                    form.combinedEnv
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
                                        form.combinedEnv,
                                        form.combinedEnv
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
                        <environments
                            ref="textEnv"
                            v-model="form.combinedEnv"
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
                    <button
                        type="button"
                        class="btn btn-block btn-secondary"
                        @click="$refs.textEnv.addEnvironment('text')"
                    >
                        <icon name="plus" />
                        <span>Add New</span>
                    </button>
                    <button
                        type="button"
                        class="btn btn-block btn-secondary"
                        @click="$refs.textEnv.addEnvironment('file')"
                    >
                        <icon name="plus" />
                        <span>Add File</span>
                    </button>
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
        sutUrls: {
            type: Object,
            required: false,
        },
    },
    mixins: [mixinVSelect, mixinEnvs],
    data() {
        return {
            sending: false,
            groupsEnvs: [],
            groupsEnvsList: [],
            groupsDefaultList: this.$page.props.auth.user.groups ?? [],
            form: {
                environments: [],
                combinedEnv: [],
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
                    this.form.combinedEnv,
                    this.form.combinedEnv
                );
            }
        );
    },
    methods: {
        submit() {
            const form = {
                environments: this.form.combinedEnv
                    ?.filter(({ type }) => type === 'text')
                    .reduce(
                        (obj, item) => ((obj[item.key] = item.value), obj),
                        {}
                    ),
                combinedEnv: this.form.environments
                    .map((x) => {
                        return { ...x, type: 'text' };
                    })
                    .concat(
                        this.form.fileEnvironments.map((x) => {
                            return { ...x, type: 'file' };
                        })
                    ),
                fileEnvironments: this.form.combinedEnv
                    ?.filter(({ type }) => type === 'file')
                    .reduce(
                        (obj, item) => ((obj[item.key] = item.value), obj),
                        {}
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
    },
};
</script>

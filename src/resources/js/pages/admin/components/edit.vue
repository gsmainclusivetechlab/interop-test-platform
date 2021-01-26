<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>Update component</b>
                </h1>
            </div>
            <div class="container">
                <div class="col-8 m-auto">
                    <form class="card" @submit.prevent="submit">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label"> Name </label>
                                <input
                                    name="name"
                                    type="text"
                                    class="form-control"
                                    v-model="form.name"
                                    :class="{
                                        'is-invalid': $page.props.errors.name,
                                    }"
                                />
                                <span
                                    v-if="$page.props.errors.name"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.props.errors.name }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"> Slug </label>
                                <input
                                    name="name"
                                    type="text"
                                    class="form-control"
                                    :value="component.slug"
                                    readonly
                                />
                            </div>
                            <div class="mb-3">
                                <label class="form-label"> UUID </label>
                                <input
                                    name="name"
                                    type="text"
                                    class="form-control"
                                    :value="component.uuid"
                                    readonly
                                />
                            </div>
                            <div class="mb-3">
                                <label class="form-label"> Base URL </label>
                                <input
                                    name="name"
                                    type="text"
                                    class="form-control"
                                    v-model="form.base_url"
                                    :class="{
                                        'is-invalid':
                                            $page.props.errors.base_url,
                                    }"
                                />
                                <span
                                    v-if="$page.props.errors.base_url"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.props.errors.base_url }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"> Connections </label>
                                <v-select
                                    v-model="connections.selected"
                                    multiple
                                    placeholder="Select connections..."
                                    label="name"
                                    :options="connections.list"
                                    :selectable="
                                        (option) =>
                                            isSelectable(
                                                option,
                                                connections.selected
                                            )
                                    "
                                    class="form-control d-flex p-0"
                                    :class="{
                                        'is-invalid':
                                            $page.props.errors.connections_id,
                                    }"
                                    @input="setConnections"
                                >
                                    <template #option="option">
                                        <div>{{ option.name }}</div>
                                        <div class="text-muted small">
                                            {{ option.base_url }}
                                        </div>
                                    </template>
                                </v-select>
                                <span
                                    v-if="$page.props.errors.groups_id"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{
                                            collect(
                                                $page.props.errors.groups_id
                                            ).implode(' ')
                                        }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"> Description </label>
                                <textarea
                                    name="description"
                                    class="form-control"
                                    rows="5"
                                    v-model="form.description"
                                    :class="{
                                        'is-invalid':
                                            $page.props.errors.description,
                                    }"
                                ></textarea>
                                <span
                                    v-if="$page.props.errors.description"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.props.errors.description }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-check form-switch">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        v-model="form.sutable"
                                    />
                                    <span class="form-check-label"
                                        >Can be SUT</span
                                    >
                                </label>
                                <span
                                    v-if="$page.props.errors.sutable"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.props.errors.sutable }}
                                    </strong>
                                </span>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <inertia-link
                                :href="route('admin.components.index')"
                                class="btn btn-link"
                            >
                                Cancel
                            </inertia-link>
                            <button type="submit" class="btn btn-primary">
                                <span
                                    v-if="sending"
                                    class="spinner-border spinner-border-sm mr-2"
                                ></span>
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';
import mixinVSelect from '@/components/v-select/mixin';

export default {
    metaInfo: {
        title: 'Update component',
    },
    components: {
        Layout,
    },
    props: {
        component: {
            type: Object,
            required: true,
        },
    },
    mixins: [mixinVSelect],
    data() {
        return {
            sending: false,
            connections: {
                list: [],
                selected: this.component?.connections?.data ?? [],
            },
            form: {
                name: this.component?.name,
                base_url: this.component?.base_url ?? null,
                description: this.component?.description ?? null,
                sutable: this.component?.sutable ?? false,
                connections_id: this.component?.connections?.data?.map(
                    (item) => item.id ?? null
                ),
            },
        };
    },
    mounted() {
        this.loadConnectionsList();
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia.put(
                route('admin.components.update', this.component.id),
                this.form,
                {
                    onFinish: () => {
                        this.sending = false;
                    },
                }
            );
        },
        loadConnectionsList(query = '') {
            axios
                .get(route('admin.components.connection-candidates'), {
                    params: { q: query, component_id: this.component.id },
                })
                .then((result) => {
                    this.connections.list = result.data.data;
                });
        },
        setConnections() {
            this.form.connections_id = this.connections.selected.map(
                (item) => item.id
            );
        },
    },
};
</script>

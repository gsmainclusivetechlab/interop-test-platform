<template>
    <layout :test-case="testCase">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Groups</h2>
            </div>
            <div class="card-body">
                <label class="form-label">Add groups</label>
                <form class="input-group" @submit.prevent="addGroups">
                    <selectize
                        v-model="newList"
                        multiple
                        class="form-select"
                        :class="{
                            'is-invalid': $page.errors.groups_id,
                        }"
                        placeholder="Select groups..."
                        label="name"
                        :keys="['name']"
                        :options="searchList"
                        :createItem="false"
                        :searchFn="searchGroups"
                    >
                        <template #option="{ option }">
                            <div>{{ option.name }}</div>
                            <div class="text-muted small">
                                {{ option.domain }}
                            </div>
                        </template>
                    </selectize>
                    <button type="submit" class="btn btn-primary">
                        <span
                            v-if="sending"
                            class="spinner-border spinner-border-sm mr-2"
                        ></span>
                        Add
                    </button>
                </form>
                <span v-if="$page.errors.groups_id" class="invalid-feedback">
                    <strong>
                        {{ collect($page.errors.groups_id).implode(' ') }}
                    </strong>
                </span>
            </div>
            <div class="card-body table-responsive p-0 mb-0">
                <table class="table table-striped card-table">
                    <thead>
                        <tr>
                            <th class="text-nowrap">Name</th>
                            <th class="text-nowrap">Email filter</th>
                            <th class="w-50 text-nowrap">Description</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(group, i) in groups.data" :key="i">
                            <td class="text-break">
                                <inertia-link
                                    :href="route('groups.show', group.id)"
                                >
                                    {{ group.name }}
                                </inertia-link>
                            </td>
                            <td class="text-break">
                                {{ group.domain }}
                            </td>
                            <td class="text-break">
                                {{ group.description }}
                            </td>
                            <td class="text-center">
                                <b-dropdown
                                    no-caret
                                    right
                                    toggle-class="align-items-center text-muted"
                                    variant="link"
                                    boundary="window"
                                >
                                    <template #button-content>
                                        <icon name="dots-vertical"></icon>
                                    </template>
                                    <li>
                                        <button
                                            class="dropdown-item"
                                            type="button"
                                            @click="deleteGroup(group.id)"
                                        >
                                            Delete
                                        </button>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="groups.data.length === 0">
                            <td class="text-center" colspan="6">No Results</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="groups.meta"
                :links="groups.links"
                class="card-footer"
            />
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/test-cases/main';

export default {
    metaInfo: {
        title: 'Test Case Groups',
    },
    components: {
        Layout,
    },
    props: {
        testCase: {
            type: Object,
            required: true,
        },
        groups: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            newList: [],
            searchList: [],
        };
    },
    watch: {
        currentList: {
            immediate: true,
            handler() {
                this.loadSearchList();
            },
        },
    },
    methods: {
        addGroups() {
            this.sending = true;

            this.$inertia
                .post(
                    route('admin.test-cases.groups.store', this.testCase.id),
                    this.form
                )
                .then(() => {
                    this.sending = false;
                    this.newList = [];
                });
        },
        deleteGroup(groupId) {
            this.$inertia
                .delete(
                    route('admin.test-cases.groups.destroy', [
                        this.testCase.id,
                        groupId,
                    ])
                )
                .then(() => {});
        },
        loadSearchList(query = '') {
            axios
                .get(route('admin.test-cases.group-candidates'), {
                    params: { q: query },
                })
                .then((result) => {
                    this.searchList = collect(result.data.data)
                        .whereNotIn('id', this.form.groups_id)
                        .whereNotIn('id', this.currentList)
                        .all();
                });
        },
        searchGroups(query, callback) {
            this.loadSearchList(query);
            callback();
        },
    },
    computed: {
        currentList() {
            return this.groups.data.map((el) => el.id);
        },
        form() {
            return {
                groups_id: this.newList.map((el) => el.id),
            };
        },
    },
};
</script>

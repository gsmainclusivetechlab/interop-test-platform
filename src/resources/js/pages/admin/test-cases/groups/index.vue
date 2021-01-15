<template>
    <layout :test-case="testCase">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">{{ $t('card.title') }}</h2>
            </div>
            <div class="card-body">
                <label class="form-label">{{
                    $t('inputs.add-groups.label')
                }}</label>
                <form class="input-group" @submit.prevent="addGroups">
                    <v-select
                        v-model="newList"
                        :options="searchList"
                        multiple
                        :selectable="(option) => isSelectable(option, newList)"
                        label="name"
                        :placeholder="$t('inputs.add-groups.placeholder')"
                        class="form-control d-flex p-0"
                        :class="{
                            'is-invalid': $page.props.errors.groups_id,
                        }"
                    >
                        <template #option="option">
                            <div>{{ option.name }}</div>
                            <div class="text-muted small">
                                {{ option.domain }}
                            </div>
                        </template>
                    </v-select>
                    <button
                        type="submit"
                        class="btn btn-primary"
                        :disabled="newList.length === 0"
                    >
                        <span
                            v-if="sending"
                            class="spinner-border spinner-border-sm mr-2"
                        ></span>
                        {{ $t('buttons.add') }}
                    </button>
                </form>
                <span
                    v-if="$page.props.errors.groups_id"
                    class="invalid-feedback"
                >
                    <strong>
                        {{ collect($page.props.errors.groups_id).implode(' ') }}
                    </strong>
                </span>
            </div>
            <div class="card-body table-responsive p-0 mb-0">
                <table class="table table-striped card-table">
                    <thead>
                        <tr>
                            <th class="text-nowrap">
                                {{ $t('table.header.name') }}
                            </th>
                            <th class="text-nowrap">
                                {{ $t('table.header.email-filter') }}
                            </th>
                            <th class="w-50 text-nowrap">
                                {{ $t('table.header.description') }}
                            </th>
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
                                        <confirm-link
                                            :href="
                                                route(
                                                    'admin.test-cases.groups.destroy',
                                                    [testCase.id, group.id]
                                                )
                                            "
                                            method="delete"
                                            :confirm-title="
                                                $t(
                                                    'table.menu.delete.modal.title'
                                                )
                                            "
                                            :confirm-text="`${$t(
                                                'table.menu.delete.modal.text'
                                            )} '${group.name}'?`"
                                            class="dropdown-item"
                                        >
                                            {{ $t('table.menu.delete.title') }}
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="groups.data.length === 0">
                            <td class="text-center" colspan="6">
                                {{ $t('table.content.no-results') }}
                            </td>
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
import { isSelectable } from '@/components/v-select/extending';

export default {
    metaInfo() {
        return {
            title: `${this.testCase.name} - ${this.$t('card.title')}`,
        };
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
        isSelectable,
        addGroups() {
            this.sending = true;

            this.$inertia.post(
                route('admin.test-cases.groups.store', this.testCase.id),
                this.form,
                {
                    onFinish: () => {
                        this.sending = false;
                        this.newList = [];
                    },
                }
            );
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
<i18n src="@locales/pages/admin/test-cases/groups/index.json"></i18n>

<template>
    <layout :title="title" :status="status">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="page-pretitle">
                        {{ $t('special-locales.administration') }}
                    </div>
                    <h2 class="page-title">
                        <b>{{ $t('page.title') }}</b>
                    </h2>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <form @submit.prevent="search">
                    <input-search v-model="form.q" />
                </form>
            </div>
            <div class="table-responsive mb-0">
                <table
                    class="table table-striped table-vcenter table-hover card-table"
                >
                    <thead>
                        <tr>
                            <th class="text-nowrap w-25">
                                {{ $t('table.header.name') }}
                            </th>
                            <th class="w-auto">
                                {{ $t('table.header.owner') }}
                            </th>
                            <th class="text-center w-auto">
                                {{ $t('table.header.use-cases') }}
                            </th>
                            <th class="text-center w-auto">
                                {{ $t('table.header.test-cases') }}
                            </th>
                            <th class="text-center text-nowrap w-25">
                                {{ $t('table.header.statuses') }}
                            </th>
                            <th class="text-center text-nowrap w-auto">
                                {{ $t('table.header.last-run') }}
                            </th>
                            <th class="text-center text-nowrap w-auto">
                                {{ $t('table.header.session-status') }}
                            </th>
                            <th class="text-center text-nowrap w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(session, i) in sessions.data"
                            :key="`session-${i}`"
                        >
                            <td class="text-break">
                                <inertia-link
                                    :href="route('sessions.show', session.id)"
                                >
                                    {{ session.name }}
                                </inertia-link>
                            </td>
                            <td class="text-break">
                                {{ session.owner.name }}
                            </td>
                            <td class="text-center">
                                {{
                                    session.testCases
                                        ? collect(session.testCases)
                                              .map(function (value) {
                                                  return value.useCase.id;
                                              })
                                              .unique()
                                              .count()
                                        : 0
                                }}
                            </td>
                            <td class="text-center">
                                {{
                                    session.testCases
                                        ? session.testCases.length
                                        : 0
                                }}
                            </td>
                            <td>
                                <session-progress :session="session" />
                            </td>
                            <td class="text-center">
                                {{
                                    session.lastTestRun
                                        ? session.lastTestRun.created_at
                                        : ''
                                }}
                            </td>
                            <td class="text-center">
                                {{ session.statusName }}
                            </td>
                            <td class="text-center text-break">
                                <b-dropdown
                                    v-if="session.can.delete"
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
                                        <inertia-link
                                            :href="
                                                route(
                                                    'sessions.message-log.index',
                                                    session.id
                                                )
                                            "
                                            class="dropdown-item"
                                        >
                                            {{ $t('table.menu.log') }}
                                        </inertia-link>
                                    </li>
                                    <li v-if="session.can.update">
                                        <inertia-link
                                            :href="
                                                route(
                                                    'sessions.edit',
                                                    session.id
                                                )
                                            "
                                            class="dropdown-item"
                                        >
                                            {{ $t('table.menu.edit') }}
                                        </inertia-link>
                                    </li>
                                    <li v-if="session.can.delete">
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'sessions.destroy',
                                                    session.id
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
                                            )} ${session.name}?`"
                                        >
                                            {{ $t('table.menu.delete.title') }}
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!sessions.data.length">
                            <td class="text-center" colspan="7">
                                {{ $t('table.no-results') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="sessions.meta"
                :links="sessions.links"
                class="card-footer"
            />
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/compliance-sessions/main';
import SessionProgress from '@/components/sessions/progress';

export default {
    metaInfo() {
        return {
            title: this.$t('page.title'),
        };
    },
    components: {
        Layout,
        SessionProgress,
    },
    props: {
        sessions: {
            type: Object,
            required: true,
        },
        filter: {
            type: Object,
            required: true,
        },
        statusName: {
            type: String,
            required: false,
        },
    },
    data() {
        const status = route().params.status;

        return {
            title: status ? this.statusName : 'All',
            status: status,
            form: {
                q: this.filter.q,
            },
        };
    },
    methods: {
        search() {
            this.$inertia.replace(
                route('admin.compliance-sessions.index', {
                    status: this.status,
                }),
                {
                    data: this.form,
                }
            );
        },
    },
};
</script>
<i18n src="@locales/special-locales.json"></i18n>
<i18n src="@locales/pages/admin/compliance-sessions/index.json"></i18n>

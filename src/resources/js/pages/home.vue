<template>
    <layout>
        <div
            class="alert alert-danger text-center"
            role="alert"
            v-if="$page.app.available_session_modes_count === 0"
        >
            No test modes are enabled in the environment
        </div>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h2 class="page-title">
                        <b>Latest sessions</b>
                    </h2>
                </div>
            </div>
        </div>
        <div class="row row-cards row-deck">
            <div class="col-12" v-if="!sessions.data.length">
                <div class="card">
                    <div class="empty h-auto">
                        <div class="row">
                            <div class="col-10 mx-auto">
                                <p class="empty-title h3 mb-3">
                                    You have no sessions
                                </p>
                                <p
                                    class="empty-subtitle text-muted mb-0"
                                    v-if="
                                        $page.app
                                            .available_session_modes_count > 0
                                    "
                                >
                                    Click the button below to create your first
                                    session.
                                </p>
                                <div class="empty-action">
                                    <inertia-link
                                        :href="route('sessions.register.type')"
                                        v-if="
                                            $page.app
                                                .available_session_modes_count >
                                            0
                                        "
                                        class="btn btn-primary"
                                    >
                                        <icon name="plus" />
                                        New Session
                                    </inertia-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="col-xl-3 col-md-4"
                v-for="(session, i) in sessions.data"
                :key="i"
            >
                <div class="card">
                    <div
                        class="card-header flex-column align-items-start h-100 border-bottom py-4"
                    >
                        <div class="d-flex align-items-center w-100 mb-2">
                            <session-progress :testCases="session.testCases" />
                        </div>
                        <h2 class="card-title w-100 text-truncate">
                            <inertia-link
                                :href="route('sessions.show', session.id)"
                                class="text-decoration-none"
                            >
                                <b>{{ session.name }}</b>
                            </inertia-link>
                        </h2>
                        <p class="mb-0 text-clamp">
                            {{ session.description }}
                        </p>
                    </div>
                    <div class="card-body flex-shrink-0 py-4">
                        <ul class="list-unstyled">
                            <li>
                                <icon
                                    class="icon_dashboard-session mr-1"
                                    name="briefcase"
                                    v-b-tooltip.hover
                                    title="Use Case"
                                />
                                <span>
                                    {{
                                        session.testCases
                                            ? collect(session.testCases)
                                                  .map(
                                                      (value) =>
                                                          value.useCase.id
                                                  )
                                                  .unique()
                                                  .count()
                                            : 0
                                    }}
                                </span>
                            </li>
                            <li>
                                <icon
                                    class="icon_dashboard-session mr-1"
                                    name="file-text"
                                    v-b-tooltip.hover
                                    title="Test Case"
                                />
                                <span>{{
                                    session.testCases
                                        ? session.testCases.length
                                        : 0
                                }}</span>
                            </li>
                            <li v-if="session.lastTestRun">
                                <icon
                                    class="icon_dashboard-session mr-1"
                                    name="clock"
                                    v-b-tooltip.hover
                                    title="Last Run"
                                />
                                <span>{{
                                    session.lastTestRun.created_at
                                }}</span>
                            </li>
                            <li>
                                <icon
                                    class="icon_dashboard-session mr-1"
                                    name="user"
                                    v-b-tooltip.hover
                                    title="Owner"
                                />
                                <span>{{ session.owner.name }}</span>
                            </li>
                            <li>
                                <icon
                                    class="icon_dashboard-session mr-1"
                                    name="chart-candle"
                                    v-b-tooltip.hover
                                    title="Type"
                                />
                                <span>{{ session.typeName }}</span>
                            </li>
                            <li v-if="session.status">
                                <icon
                                    class="icon_dashboard-session mr-1"
                                    name="list-check"
                                    v-b-tooltip.hover
                                    title="Status"
                                />
                                <span>{{ session.statusName }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';
import SessionProgress from '@/components/sessions/progress';

export default {
    metaInfo: {
        title: 'Dashboard',
    },
    components: {
        Layout,
        SessionProgress,
    },
    props: {
        sessions: {
            type: Object,
            required: false,
        },
    },
};
</script>

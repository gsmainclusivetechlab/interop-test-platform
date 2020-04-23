<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h2 class="page-title">
                        Latest sessions
                    </h2>
                </div>
            </div>
        </div>
        <div class="row row-cards row-deck">
            <div class="col-xl-3 col-md-4" v-for="session in sessions.data">
                <div class="card">
                    <div class="card-header flex-column align-items-start h-100 border-bottom py-4">
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
                        <p class="mb-0">
                            {{ session.description ? string(session.description).truncate(100) : '' }}
                        </p>
                    </div>
                    <div class="card-body flex-shrink-0 py-4">
                        <ul class="list-unstyled">
                            <li>
                                <icon name="briefcase" />
                                {{ session.testCases ? collect(session.testCases).unique('use_case_id').count() : 0 }}
                            </li>
                            <li>
                                <icon name="file-text" />
                                {{ session.testCases ? session.testCases.length : 0 }}
                            </li>
                            <li v-if="session.lastTestRun">
                                <icon name="clock" />
                                {{ session.lastTestRun.created_at }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-12" v-if="!sessions.data.length">
                <div class="card card-body">
                    No Results
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
        title: 'Dashboard'
    },
    components: {
        Layout,
        SessionProgress
    },
    props: {
        sessions: {
            type: Object,
            required: false
        },
    },
};
</script>

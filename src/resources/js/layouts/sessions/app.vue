<template>
    <layout>
        <div class="row">
            <div class="col-md-12">
                <div class="row border-bottom pb-4 align-items-stretch">
                    <div class="col-6 d-flex flex-column">
                        <div class="page-pretitle font-weight-normal">
                            <breadcrumb
                                class="breadcrumb-bullets"
                                :items="breadcrumbs"
                            ></breadcrumb>
                        </div>
                        <h1
                            class="page-title mw-100 mr-2 text-break d-flex align-items-center"
                        >
                            <b>{{ session.name }}</b>
                            <div class="btn-group ml-1">
                                <inertia-link
                                    :href="route('sessions.edit', session.id)"
                                    class="btn btn-link btn-icon"
                                    v-if="session.can.update"
                                >
                                    <icon name="pencil"></icon>
                                </inertia-link>
                            </div>
                        </h1>
                    </div>
                    <div
                        class="col-4 d-flex align-items-center justify-content-end"
                    >
                        <confirm-link
                            method="post"
                            class="btn btn-outline-primary"
                            v-if="session.can.update && session.completable"
                            :href="route('sessions.complete', session.id)"
                            :confirm-title="'Confirm compliting'"
                            :confirm-text="`This is a non-reversible action, and you will need to create a new
                                        session if you wish to execute any more test cases.`"
                        >
                            <icon name="checks"></icon>
                            Completed
                        </confirm-link>
                        <change-status
                            class="ml-1"
                            v-if="canApprove"
                            :href="
                                route(
                                    'admin.compliance-sessions.update',
                                    session.id
                                )
                            "
                            :confirm-title="'Approve session'"
                            :status="'approved'"
                        >
                            <icon name="check"></icon>
                            Approve
                        </change-status>
                        <change-status
                            class="ml-1"
                            v-if="canApprove"
                            :href="
                                route(
                                    'admin.compliance-sessions.update',
                                    session.id
                                )
                            "
                            :status="'declined'"
                            :confirm-title="'Decline session'"
                        >
                            <icon name="x"></icon>
                            Decline
                        </change-status>
                        <inertia-link
                            :href="
                                route('sessions.message-log.index', session.id)
                            "
                            class="btn btn-secondary ml-1"
                        >
                            <icon name="list"></icon>
                            Log
                        </inertia-link>
                    </div>
                    <div class="col-2 d-flex justify-content-end">
                        <div class="w-100">
                            <div>
                                Execution:
                                <icon
                                    name="briefcase"
                                    v-b-tooltip.hover
                                    title="Use Case"
                                />
                                <small>
                                    {{
                                        session.testCases
                                            ? collect(session.testCases.data)
                                                  .map(function (value) {
                                                      return value.useCase.id;
                                                  })
                                                  .unique()
                                                  .count()
                                            : 0
                                    }}
                                </small>
                                <icon
                                    name="file-text"
                                    v-b-tooltip.hover
                                    title="Test Case"
                                />
                                <small>
                                    {{
                                        session.testCases
                                            ? session.testCases.data.length
                                            : 0
                                    }}
                                </small>
                            </div>
                            <session-progress
                                :testCases="session.testCases.data"
                            />
                        </div>
                    </div>
                </div>
                <div class="row align-items-start">
                    <slot />
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';
import SessionProgress from '@/components/sessions/progress';
import ChangeStatus from '@/components/sessions/change-status';

export default {
    components: {
        Layout,
        SessionProgress,
        ChangeStatus,
    },
    metaInfo() {
        return {
            title: this.session.name,
        };
    },
    props: {
        session: {
            type: Object,
            required: true,
        },
        breadcrumbs: {
            type: Array,
            required: false,
            default: function () {
                return [
                    { name: 'Sessions', url: route('sessions.index') },
                    { name: this.session.name },
                ];
            },
        },
    },
    data() {
        return {
            canApprove:
                this.$page.auth.user.is_admin &&
                this.session.status === 'in_verification',
        };
    },
};
</script>

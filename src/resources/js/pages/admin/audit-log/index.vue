<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="page-pretitle">Administration</div>
                    <h2 class="page-title">
                        <b>Audit Log</b>
                    </h2>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <b>Audit Log</b>
                </h2>
            </div>
            <div class="table-responsive mb-0">
                <table class="table table-striped table-hover card-table">
                    <thead>
                    <tr>
                        <th class="text-nowrap w-20">User</th>
                        <th class="text-nowrap w-auto">Action</th>
                        <th class="text-nowrap w-15">Subject</th>
                        <th class="text-nowrap w-15">Meta</th>
                        <th class="text-nowrap w-15">Action Performed</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="message in logItems.data"
                    >
                    <td>
                        {{ message.fullname.name }}
                    </td>
                    <td>
                        {{ message.action }}
                    </td>
                    <td class="text-break">
                        <inertia-link
                            :href="
                                route(message.url, message.subject)
                            "
                        >
                            {{ message.subject }}
                        </inertia-link>
                    </td>
                    <td>
                        <button
                            class="btn btn-secondary"
                            v-if="message.meta"
                            v-b-modal="
                            `modal-request-${message.name}`
                            "
                        >
                            Request
                        </button>
                    </td>
                    <td>
                        {{ message.created_at }}
                    </td>
                    <tr v-if="!logItems.data.length">
                        <td class="text-center" colspan="5">No Results</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <pagination
                :meta="logItems.meta"
                :links="logItems.links"
                class="card-footer"
            />
        </div>
    </layout>
</template>

<script>
    import Layout from '@/layouts/main';

    export default {
        components: {
            Layout,
        },
        props: {
            logItems: {
                type: Object,
                required: true,
            },
        },
    };
</script>

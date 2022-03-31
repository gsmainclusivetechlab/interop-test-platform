<template>
    <layout>
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <strong>{{ title }}</strong>
                </h2>
            </div>
            <div class="table-responsive mb-0">
                <table class="table table-striped table-hover card-table">
                    <thead>
                        <tr>
                            <th class="text-nowrap w-15">Date and time</th>
                            <th class="text-nowrap w-auto">Level</th>
                            <th class="text-nowrap w-15">Context</th>
                            <th class="text-nowrap w-15">Stack Trace</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(message, i) in logItems.data"
                            :key="`message-${i}`"
                        >
                            <td>
                                {{ message.date }}
                            </td>
                            <td>
                                <div
                                    class="badge badge-outline text-xl"
                                    v-bind:class="msgColor(message.level)"
                                >
                                    {{ message.level.toUpperCase() }}
                                </div>
                            </td>
                            <td>
                                <vue-json-pretty
                                    :deep="0"
                                    :virtual="true"
                                    :data="message.context"
                                />
                            </td>
                            <td>
                                <vue-json-pretty
                                    :deep="0"
                                    :deepCollapseChildren="true"
                                    :virtual="true"
                                    :virtualLines="
                                        message.stack_traces.length * 3
                                    "
                                    :data="message.stack_traces"
                                />
                            </td>
                        </tr>
                        <tr v-if="!Object.keys(logItems.data).length">
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
    import Layout from '@/layouts/logs';
    import VueJsonPretty from 'vue-json-pretty';
    import 'vue-json-pretty/lib/styles.css';

    export default {
        components: {
            Layout,
            VueJsonPretty,
        },
        props: {
            title: {
                type: string,
                required: true,
            },
            logItems: {
                type: Object,
                required: true,
            },
        },
        methods: {
            msgColor(level) {
                switch (level) {
                    case 'error':
                        return 'text-danger';
                    case 'warning':
                        return 'text-warning';
                    default:
                        return 'text-info';
                }
            },
        },
    };
</script>

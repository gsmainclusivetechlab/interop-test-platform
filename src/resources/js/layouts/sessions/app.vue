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
                        <h1 class="page-title d-flex align-items-center">
                            <b class="mw-100 text-break">{{ session.name }}</b>
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
                        <inertia-link
                            :href="
                                route('sessions.message-log.index', session.id)
                            "
                            class="btn btn-secondary ml-3"
                        >
                            <icon name="list"></icon>
                            Log
                        </inertia-link>
                    </div>
                    <div class="col-2 d-flex align-items-center">
                        <div class="w-100">
                            <div>
                                Execution:
                                <icon
                                    name="briefcase"
                                    v-b-tooltip.hover.top
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
                                    v-b-tooltip.hover.top
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

export default {
    components: {
        Layout,
        SessionProgress,
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
};
</script>

<template>
    <layout>
        <div class="row">
            <div class="col-md-12">
                <div class="row border-bottom pb-4 align-items-center">
                    <div class="col-6 d-flex flex-column">
                        <div class="page-pretitle font-weight-normal">
                            <breadcrumb
                                class="breadcrumb-bullets"
                                :items="breadcrumbs"
                            ></breadcrumb>
                        </div>
                        <h1 class="page-title mw-100 mr-2 text-break">
                            <b>{{ group.name }}</b>
                        </h1>
                    </div>
                    <div class="ml-auto col-2 d-flex justify-content-end">
                        <inertia-link :href="route('groups.members.create', group.id)" class="btn btn-primary">
                            <icon name="user-plus" />
                            Invite Member
                        </inertia-link>
                    </div>
                </div>
                <div class="row align-items-start">
                    <div class="col-12">
                        <div class="row">
                            <div class="col mt-3">
                                <slot />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    components: {
        Layout,
    },
    metaInfo() {
        return {
            title: this.group.name,
        };
    },
    props: {
        group: {
            type: Object,
            required: true,
        },
        breadcrumbs: {
            type: Array,
            required: false,
            default: function () {
                return [
                    { name: 'Groups', url: route('groups.index') },
                    { name: this.group.name },
                ];
            },
        },
    },
};
</script>

<template>
    <layout>
        <form class="card" @submit.prevent="submit">
            <div class="card-header">
                <h2 class="card-title">Create</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label">Name</label>
                        <input
                            name="name"
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid': $page.props.errors.name,
                            }"
                            v-model="name"
                        />
                        <span
                            v-if="$page.props.errors.name"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.name }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Slug</label>
                        <input
                            name="slug"
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid': $page.props.errors.slug,
                            }"
                            v-model="slug"
                        />
                        <span
                            v-if="$page.props.errors.slug"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.slug }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Behavior</label>
                        <selectize
                            class="form-select"
                            :class="{
                                'is-invalid': $page.props.errors.behavior,
                            }"
                            v-model="behavior"
                            placeholder="Select behavior"
                            :options="
                                collect(
                                    $page.props.enums.test_case_behaviors
                                ).toArray()
                            "
                            :disableSearch="false"
                            :createItem="false"
                        />
                        <span
                            v-if="$page.props.errors.behavior"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.behavior }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Use Case</label>
                        <selectize
                            class="form-select"
                            :class="{
                                'is-invalid': $page.props.errors.use_case_id,
                            }"
                            v-model="useCase"
                            label="name"
                            placeholder="Select use case"
                            :options="$page.useCases"
                            :disableSearch="false"
                            :createItem="false"
                        />
                        <span
                            v-if="$page.props.errors.use_case_id"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.use_case_id }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Precondition</label>
                        <text-editor
                            :class="{
                                'is-invalid': $page.props.errors.precondition,
                            }"
                            :menu-items="[
                                'bold',
                                'italic',
                                'strike',
                                'underline',
                                'ordered_list',
                                'bullet_list',
                                'code',
                                'hard_break',
                            ]"
                            :output-format="['html']"
                            :content="{ html: precondition }"
                            @output-html="(content) => (precondition = content)"
                        />
                        <span
                            v-if="$page.props.errors.precondition"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.precondition }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Description</label>
                        <text-editor
                            :class="{
                                'is-invalid': $page.props.errors.description,
                            }"
                            :menu-items="[
                                'bold',
                                'italic',
                                'strike',
                                'underline',
                                'ordered_list',
                                'bullet_list',
                                'code',
                                'hard_break',
                            ]"
                            :output-format="['html']"
                            :content="{ html: description }"
                            @output-html="(content) => (description = content)"
                        />
                        <span
                            v-if="$page.props.errors.description"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.description }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Components</label>
                        <selectize
                            v-model="components"
                            multiple
                            class="form-select"
                            placeholder="Select components"
                            :class="{
                                'is-invalid': $page.props.errors.components_id,
                            }"
                            label="name"
                            :options="$page.components"
                            :createItem="false"
                            :disableSearch="true"
                        />
                        <span
                            v-if="$page.props.errors.components_id"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{
                                    collect(
                                        $page.props.errors.components_id
                                    ).implode(' ')
                                }}
                            </strong>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <inertia-link
                    :href="route('admin.test-cases.index')"
                    class="btn btn-link"
                >
                    Cancel
                </inertia-link>
                <button type="submit" class="btn btn-primary">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    Create
                </button>
            </div>
        </form>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    metaInfo: {
        title: 'Test Case Create',
    },
    components: {
        Layout,
    },
    data() {
        return {
            sending: false,
            name: '',
            slug: '',
            behavior: '',
            useCase: {},
            description: '',
            precondition: '',
            components: [],
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .post(route('admin.test-cases.store'), this.form)
                .then(() => (this.sending = false));
        },
    },
    computed: {
        form() {
            const form = {
                name: this.name,
                slug: this.slug,
                behavior: collect(this.$page.props.enums.test_case_behaviors)
                    .flip()
                    .all()[this.behavior],
                use_case_id: this.useCase?.id,
                components_id: this.components?.map((el) => el.id),
                description: this.description,
                precondition: this.precondition,
            };

            return form;
        },
    },
};
</script>

<template>
    <layout :test-case="testCase">
        <form class="card" @submit.prevent="submit">
            <div class="card-header">
                <h2 class="card-title">{{ $t('card.title') }}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.method.label')
                        }}</label>
                        <v-select
                            v-model="method.selected"
                            :placeholder="$t('inputs.method.placeholder')"
                            :options="method.list"
                            :selectable="
                                (option) =>
                                    isSelectable(option, method.selected)
                            "
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid': $page.props.errors.method,
                            }"
                        />
                        <span
                            v-if="$page.props.errors.method"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.method }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.path')
                        }}</label>
                        <input
                            name="path"
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid': $page.props.errors.path,
                            }"
                            v-model="path"
                        />
                        <span
                            v-if="$page.props.errors.path"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.path }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">{{
                            $t('inputs.pattern')
                        }}</label>
                        <input
                            name="pattern"
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid': $page.props.errors.pattern,
                            }"
                            v-model="pattern"
                        />
                        <span
                            v-if="$page.props.errors.pattern"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.pattern }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.source.label')
                        }}</label>
                        <v-select
                            v-model="source"
                            :placeholder="$t('inputs.source.placeholder')"
                            :options="sourceList"
                            :selectable="
                                (option) => isSelectable(option, source)
                            "
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid': $page.props.errors.source_id,
                            }"
                        />
                        <span
                            v-if="$page.props.errors.source_id"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.source_id }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.target.label')
                        }}</label>
                        <v-select
                            v-model="target"
                            :placeholder="$t('inputs.target.placeholder')"
                            :options="targetList"
                            :selectable="
                                (option) => isSelectable(option, target)
                            "
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid': $page.props.errors.target_id,
                            }"
                        />
                        <span
                            v-if="$page.props.errors.target_id"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.target_id }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.api-spec.label')
                        }}</label>
                        <v-select
                            v-model="apiSpec.selected"
                            :placeholder="$t('inputs.api-spec.placeholder')"
                            :options="apiSpec.list"
                            :selectable="
                                (option) =>
                                    isSelectable(option, apiSpec.selected)
                            "
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid': $page.props.errors.api_spec,
                            }"
                        />
                    </div>
                    <div class="col-12 mb-3">
                        <div class="d-flex">
                            <label class="form-check form-switch">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    v-model="mtls"
                                />
                                <span class="form-check-label">mTLS</span>
                            </label>
                        </div>
                        <span
                            v-if="$page.props.errors.mtls"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.mtls }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'trigger'"
                        >
                            {{ $t('inputs.trigger') }}
                        </button>
                        <b-collapse id="trigger" class="card mb-0">
                            <json-editor-block
                                :input-json="trigger"
                                @output-json="
                                    (data) => {
                                        trigger = data;
                                    }
                                "
                                class="card-body"
                            />
                        </b-collapse>
                    </div>
                    <hr />
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'test-request-scripts'"
                        >
                            {{ $t('inputs.test-scripts.request') }}
                        </button>
                        <b-collapse id="test-request-scripts" class="card mb-0">
                            <div
                                v-for="(request, i) in test.scripts.request
                                    .list"
                                class="card-body"
                                :key="`test-request-scripts-${i}`"
                            >
                                <div class="card-header px-0 pt-0">
                                    <span class="text-break mr-2"
                                        ><b>{{ i + 1 }}.</b>
                                        {{ request.name }}</span
                                    >
                                    <div class="card-options">
                                        <button
                                            type="button"
                                            class="btn btn-link p-0"
                                            v-b-tooltip.hover.top
                                            :title="
                                                $t(
                                                    'inputs.test-scripts.form.buttons.delete'
                                                )
                                            "
                                            v-b-modal="
                                                `test-request-scripts-modal-${i}`
                                            "
                                        >
                                            <icon name="trash" />
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body px-0 pb-0">
                                    <label class="form-label">{{
                                        $t(
                                            'inputs.test-scripts.form.inputs.name'
                                        )
                                    }}</label>
                                    <input
                                        type="text"
                                        class="form-control mb-2"
                                        :class="{
                                            'is-invalid':
                                                $page.props.errors[
                                                    `test.scripts.request.${i}.name`
                                                ],
                                        }"
                                        v-model="request.name"
                                    />
                                    <span
                                        v-if="
                                            $page.props.errors[
                                                `test.scripts.request.${i}.name`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.props.errors[
                                                    `test.scripts.request.${i}.name`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                    <label class="form-label mb-3">{{
                                        $t(
                                            'inputs.test-scripts.form.inputs.rules'
                                        )
                                    }}</label>
                                    <json-editor-block
                                        :input-json="request.rules"
                                        @output-json="
                                            (data) => (request.rules = data)
                                        "
                                        :class="{
                                            'form-control is-invalid p-0':
                                                $page.props.errors[
                                                    `test.scripts.request.${i}.rules`
                                                ],
                                        }"
                                    />
                                    <span
                                        v-if="
                                            $page.props.errors[
                                                `test.scripts.request.${i}.rules`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.props.errors[
                                                    `test.scripts.request.${i}.rules`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                </div>
                                <b-modal
                                    :id="`test-request-scripts-modal-${i}`"
                                    :title="
                                        $t(
                                            'inputs.test-scripts.form.modal.title'
                                        )
                                    "
                                    @ok="
                                        deleteFormItem(
                                            test.scripts.request.list,
                                            i
                                        )
                                    "
                                    centered
                                    :key="`test-request-scripts-modal-${i}`"
                                >
                                    <p>
                                        {{
                                            $t(
                                                'inputs.test-scripts.form.modal.text'
                                            )
                                        }}
                                    </p>
                                </b-modal>
                            </div>
                            <div class="card-footer text-right">
                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    @click="
                                        addFormItem(test.scripts.request.list, {
                                            ...test.scripts.request.pattern,
                                        })
                                    "
                                >
                                    <icon name="plus" />
                                    <span>{{
                                        $t(
                                            'inputs.test-scripts.form.buttons.add-script'
                                        )
                                    }}</span>
                                </button>
                            </div>
                        </b-collapse>
                    </div>
                    <div class="col-6 mb-3">
                        <h2 class="card-title">
                            {{ $t('inputs.request.uri') }}
                        </h2>
                        <input
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid': $page.props.errors['request.uri'],
                            }"
                            v-model="example.request.uri"
                        />
                        <span
                            v-if="$page.props.errors['request.uri']"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors['request.uri'] }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <h2 class="card-title">
                            {{ $t('inputs.request.delay') }}
                        </h2>
                        <input
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid':
                                    $page.props.errors['request.delay'],
                            }"
                            v-model="example.request.delay"
                        />
                        <span
                            v-if="$page.props.errors['request.delay']"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors['request.delay'] }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'request-headers-examples'"
                        >
                            {{ $t('inputs.request.headers') }}
                        </button>
                        <b-collapse
                            id="request-headers-examples"
                            class="card mb-0"
                        >
                            <json-editor-block
                                :input-json="example.request.headers"
                                @output-json="
                                    (data) => {
                                        example.request.headers = data;
                                    }
                                "
                                class="card-body"
                            />
                        </b-collapse>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'request-body-examples'"
                        >
                            {{ $t('inputs.request.body') }}
                        </button>
                        <b-collapse
                            id="request-body-examples"
                            class="card mb-0"
                        >
                            <div class="card-header">
                                <div class="card-options">
                                    <label class="form-check form-switch">
                                        <input
                                            :checked="
                                                example.request.body ===
                                                'empty_body'
                                            "
                                            class="form-check-input"
                                            type="checkbox"
                                            @input="
                                                (e) =>
                                                    (example.request.body = toggleEmptyBody(
                                                        e
                                                    ))
                                            "
                                        />
                                        <span class="form-check-label">{{
                                            $t('inputs.empty-body')
                                        }}</span>
                                    </label>
                                </div>
                            </div>
                            <json-editor-block
                                v-if="example.request.body !== 'empty_body'"
                                :input-json="example.request.body"
                                @output-json="
                                    (data) => {
                                        example.request.body = data;
                                    }
                                "
                                class="card-body"
                            />
                        </b-collapse>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'request-jws-examples'"
                        >
                            {{ $t('inputs.request.jws') }}
                        </button>
                        <b-collapse id="request-jws-examples" class="card mb-0">
                            <json-editor-block
                                :input-json="example.request.jws"
                                @output-json="
                                    (data) => {
                                        example.request.jws = data;
                                    }
                                "
                                class="card-body"
                            />
                        </b-collapse>
                    </div>
                    <hr />
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'test-response-scripts'"
                        >
                            {{ $t('inputs.test-scripts.response') }}
                        </button>
                        <b-collapse
                            id="test-response-scripts"
                            class="card mb-0"
                        >
                            <div
                                v-for="(response, i) in test.scripts.response
                                    .list"
                                class="card-body"
                                :key="`test-response-scripts-${i}`"
                            >
                                <div class="card-header px-0 pt-0">
                                    <span class="text-muted text-break mr-2"
                                        >{{ i + 1 }}. {{ response.name }}</span
                                    >
                                    <div class="card-options">
                                        <button
                                            type="button"
                                            class="btn btn-link p-0"
                                            v-b-tooltip.hover.top
                                            :title="
                                                $t(
                                                    'inputs.test-scripts.form.buttons.delete'
                                                )
                                            "
                                            v-b-modal="
                                                `test-response-scripts-modal-${i}`
                                            "
                                        >
                                            <icon name="trash" />
                                        </button>
                                    </div>
                                </div>
                                <div
                                    class="card-body px-0 pb-0"
                                    :key="`test-response-scripts-form-${i}`"
                                >
                                    <label class="form-label">{{
                                        $t(
                                            'inputs.test-scripts.form.inputs.name'
                                        )
                                    }}</label>
                                    <input
                                        type="text"
                                        class="form-control mb-2"
                                        :class="{
                                            'is-invalid':
                                                $page.props.errors[
                                                    `test.scripts.response.${i}.name`
                                                ],
                                        }"
                                        v-model="response.name"
                                    />
                                    <span
                                        v-if="
                                            $page.props.errors[
                                                `test.scripts.response.${i}.name`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.props.errors[
                                                    `test.scripts.response.${i}.name`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                    <label class="form-label mb-3">{{
                                        $t(
                                            'inputs.test-scripts.form.inputs.rules'
                                        )
                                    }}</label>
                                    <json-editor-block
                                        :input-json="response.rules"
                                        @output-json="
                                            (data) => (response.rules = data)
                                        "
                                        :class="{
                                            'form-control is-invalid p-0':
                                                $page.props.errors[
                                                    `test.scripts.response.${i}.rules`
                                                ],
                                        }"
                                    />
                                    <span
                                        v-if="
                                            $page.props.errors[
                                                `test.scripts.response.${i}.rules`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.props.errors[
                                                    `test.scripts.response.${i}.rules`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                </div>
                                <b-modal
                                    :id="`test-response-scripts-modal-${i}`"
                                    :title="
                                        $t(
                                            'inputs.test-scripts.form.modal.title'
                                        )
                                    "
                                    @ok="
                                        deleteFormItem(
                                            test.scripts.response.list,
                                            i
                                        )
                                    "
                                    centered
                                    :key="`test-response-scripts-modal-${i}`"
                                >
                                    <p>
                                        {{
                                            $t(
                                                'inputs.test-scripts.form.modal.text'
                                            )
                                        }}
                                    </p>
                                </b-modal>
                            </div>
                            <div class="card-footer text-right">
                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    @click="
                                        addFormItem(
                                            test.scripts.response.list,
                                            {
                                                ...test.scripts.response
                                                    .pattern,
                                            }
                                        )
                                    "
                                >
                                    <icon name="plus" />
                                    <span>{{
                                        $t(
                                            'inputs.test-scripts.form.buttons.add-script'
                                        )
                                    }}</span>
                                </button>
                            </div>
                        </b-collapse>
                    </div>
                    <div class="col-6 mb-3">
                        <h2 class="card-title">
                            {{ $t('inputs.response.status.label') }}
                        </h2>
                        <v-select
                            v-model="example.response.status.selected"
                            :placeholder="
                                $t('inputs.response.status.placeholder')
                            "
                            :options="example.response.status.list"
                            :selectable="
                                (option) =>
                                    isSelectable(
                                        option,
                                        example.response.status.selected
                                    )
                            "
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid':
                                    $page.props.errors['response.status'],
                            }"
                        />
                        <span
                            v-if="$page.props.errors['response.status']"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors['response.status'] }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <h2 class="card-title">
                            {{ $t('inputs.response.delay') }}
                        </h2>
                        <input
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid':
                                    $page.props.errors['response.delay'],
                            }"
                            v-model="example.response.delay"
                        />
                        <span
                            v-if="$page.props.errors['response.delay']"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors['response.delay'] }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'response-headers-examples'"
                        >
                            {{ $t('inputs.response.headers') }}
                        </button>
                        <b-collapse
                            id="response-headers-examples"
                            class="card mb-0"
                        >
                            <json-editor-block
                                :input-json="example.response.headers"
                                @output-json="
                                    (data) => {
                                        example.response.headers = data;
                                    }
                                "
                                class="card-body"
                            />
                        </b-collapse>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'response-body-examples'"
                        >
                            {{ $t('inputs.response.body') }}
                        </button>
                        <b-collapse
                            id="response-body-examples"
                            class="card mb-0"
                        >
                            <div class="card-header">
                                <div class="card-options">
                                    <label class="form-check form-switch">
                                        <input
                                            :checked="
                                                example.response.body ===
                                                'empty_body'
                                            "
                                            class="form-check-input"
                                            type="checkbox"
                                            @input="
                                                (e) =>
                                                    (example.response.body = toggleEmptyBody(
                                                        e
                                                    ))
                                            "
                                        />
                                        <span class="form-check-label">{{
                                            $t('inputs.empty-body')
                                        }}</span>
                                    </label>
                                </div>
                            </div>
                            <json-editor-block
                                v-if="example.response.body !== 'empty_body'"
                                :input-json="example.response.body"
                                @output-json="
                                    (data) => {
                                        example.response.body = data;
                                    }
                                "
                                class="card-body"
                            />
                        </b-collapse>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'response-jws-examples'"
                        >
                            {{ $t('inputs.response.jws') }}
                        </button>
                        <b-collapse
                            id="response-jws-examples"
                            class="card mb-0"
                        >
                            <json-editor-block
                                :input-json="example.response.jws"
                                @output-json="
                                    (data) => {
                                        example.response.jws = data;
                                    }
                                "
                                class="card-body"
                            />
                        </b-collapse>
                    </div>
                    <hr />
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'repeat-test-response-scripts'"
                        >
                            {{ $t('inputs.test-scripts.repeat.response') }}
                        </button>
                        <b-collapse
                            id="repeat-test-response-scripts"
                            class="card mb-0"
                        >
                            <div
                                v-for="(response, i) in test.scripts.repeat
                                    .response.list"
                                class="card-body"
                                :key="`repeat-test-response-scripts-${i}`"
                            >
                                <div class="card-header px-0 pt-0">
                                    <span class="text-break mr-2"
                                        ><b>{{ i + 1 }}.</b>
                                        {{ response.name }}</span
                                    >
                                    <div class="card-options">
                                        <button
                                            type="button"
                                            class="btn btn-link p-0"
                                            v-b-tooltip.hover.top
                                            :title="
                                                $t(
                                                    'inputs.test-scripts.form.buttons.delete'
                                                )
                                            "
                                            v-b-modal="
                                                `repeat-test-response-scripts-modal-${i}`
                                            "
                                        >
                                            <icon name="trash" />
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body px-0 pb-0">
                                    <label class="form-label">{{
                                        $t(
                                            'inputs.test-scripts.form.inputs.name'
                                        )
                                    }}</label>
                                    <input
                                        type="text"
                                        class="form-control mb-2"
                                        :class="{
                                            'is-invalid':
                                                $page.props.errors[
                                                    `test.scripts.repeat.response.${i}.name`
                                                ],
                                        }"
                                        v-model="response.name"
                                    />
                                    <span
                                        v-if="
                                            $page.props.errors[
                                                `test.scripts.repeat.response.${i}.name`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.props.errors[
                                                    `test.scripts.repeat.response.${i}.name`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                    <label class="form-label mb-3">{{
                                        $t(
                                            'inputs.test-scripts.form.inputs.rules'
                                        )
                                    }}</label>
                                    <json-editor-block
                                        :input-json="response.rules"
                                        @output-json="
                                            (data) => (response.rules = data)
                                        "
                                        :class="{
                                            'form-control is-invalid p-0':
                                                $page.props.errors[
                                                    `test.scripts.repeat.response.${i}.rules`
                                                ],
                                        }"
                                    />
                                    <span
                                        v-if="
                                            $page.props.errors[
                                                `test.scripts.repeat.response.${i}.rules`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.props.errors[
                                                    `test.scripts.repeat.response.${i}.rules`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                </div>
                                <b-modal
                                    :id="`repeat-test-response-scripts-modal-${i}`"
                                    :title="
                                        $t(
                                            'inputs.test-scripts.form.modal.title'
                                        )
                                    "
                                    @ok="
                                        deleteFormItem(
                                            test.scripts.repeat.response.list,
                                            i
                                        )
                                    "
                                    centered
                                    :key="`repeat-test-response-scripts-modal-${i}`"
                                >
                                    <p>
                                        {{
                                            $t(
                                                'inputs.test-scripts.form.modal.text'
                                            )
                                        }}
                                    </p>
                                </b-modal>
                            </div>
                            <div class="card-footer text-right">
                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    @click="
                                        addFormItem(
                                            test.scripts.repeat.response.list,
                                            {
                                                ...test.scripts.repeat.response
                                                    .pattern,
                                            }
                                        )
                                    "
                                >
                                    <icon name="plus" />
                                    <span>{{
                                        $t(
                                            'inputs.test-scripts.form.buttons.add-script'
                                        )
                                    }}</span>
                                </button>
                            </div>
                        </b-collapse>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'repeat-condition'"
                        >
                            {{ $t('inputs.repeat.condition') }}
                        </button>
                        <b-collapse
                            id="repeat-condition"
                            class="card mb-0"
                            :class="{
                                'is-invalid':
                                    $page.props.errors['repeat.condition'],
                            }"
                        >
                            <json-editor-block
                                :input-json="example.repeat.condition"
                                @output-json="
                                    (data) => {
                                        example.repeat.condition = data;
                                    }
                                "
                                class="card-body"
                            />
                        </b-collapse>
                        <span
                            v-if="$page.props.errors['repeat.condition']"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors['repeat.condition'] }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <h2 class="card-title">
                            {{ $t('inputs.repeat.count') }}
                        </h2>
                        <input
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid':
                                    $page.props.errors['repeat.count'],
                            }"
                            v-model="example.repeat.count"
                        />
                        <span
                            v-if="$page.props.errors['repeat.count']"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors['repeat.count'] }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <h2 class="card-title">
                            {{ $t('inputs.repeat.max') }}
                        </h2>
                        <input
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid': $page.props.errors['repeat.max'],
                            }"
                            v-model="example.repeat.max"
                        />
                        <span
                            v-if="$page.props.errors['repeat.max']"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors['repeat.max'] }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <h2 class="card-title">
                            {{ $t('inputs.repeat.response.status.label') }}
                        </h2>
                        <v-select
                            v-model="example.repeat.response.status.selected"
                            :placeholder="
                                $t('inputs.response.status.placeholder')
                            "
                            :options="example.response.status.list"
                            :selectable="
                                (option) =>
                                    isSelectable(
                                        option,
                                        example.repeat.response.status.selected
                                    )
                            "
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid':
                                    $page.props.errors[
                                        'repeat.response.status'
                                    ],
                            }"
                        />
                        <span
                            v-if="$page.props.errors['repeat.response.status']"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{
                                    $page.props.errors['repeat.response.status']
                                }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'repeat-response-headers-examples'"
                        >
                            {{ $t('inputs.repeat.response.headers') }}
                        </button>
                        <b-collapse
                            id="repeat-response-headers-examples"
                            class="card mb-0"
                        >
                            <json-editor-block
                                :input-json="example.repeat.response.headers"
                                @output-json="
                                    (data) => {
                                        example.repeat.response.headers = data;
                                    }
                                "
                                class="card-body"
                            />
                        </b-collapse>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'repeat-response-body-examples'"
                        >
                            {{ $t('inputs.repeat.response.body') }}
                        </button>
                        <b-collapse
                            id="repeat-response-body-examples"
                            class="card mb-0"
                        >
                            <div class="card-header">
                                <div class="card-options">
                                    <label class="form-check form-switch">
                                        <input
                                            :checked="
                                                example.repeat.response.body ===
                                                'empty_body'
                                            "
                                            class="form-check-input"
                                            type="checkbox"
                                            @input="
                                                (e) =>
                                                    (example.repeat.response.body = toggleEmptyBody(
                                                        e
                                                    ))
                                            "
                                        />
                                        <span class="form-check-label">{{
                                            $t('inputs.empty-body')
                                        }}</span>
                                    </label>
                                </div>
                            </div>
                            <json-editor-block
                                v-if="
                                    example.repeat.response.body !==
                                    'empty_body'
                                "
                                :input-json="example.repeat.response.body"
                                @output-json="
                                    (data) => {
                                        example.repeat.response.body = data;
                                    }
                                "
                                class="card-body"
                            />
                        </b-collapse>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <inertia-link
                    :href="
                        route('admin.test-cases.test-steps.index', testCase.id)
                    "
                    class="btn btn-link"
                >
                    {{ $t('buttons.cancel') }}
                </inertia-link>
                <button type="submit" class="btn btn-primary">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    {{ $t('buttons.create') }}
                </button>
            </div>
        </form>
    </layout>
</template>

<script>
import Layout from '@/layouts/test-cases/main';
import mixin from '@/pages/admin/test-cases/test-steps/mixin';
import JsonEditorBlock from '@/components/json-editor-block';
import mixinVSelect from '@/components/v-select/mixin';

export default {
    components: {
        Layout,
        JsonEditorBlock,
    },
    mixins: [mixin, mixinVSelect],
    data() {
        return {
            method: {
                selected: null,
            },
            path: '',
            pattern: '',
            source: '',
            target: '',
            apiSpec: {
                selected: null,
            },
            trigger: null,
            mtls: false,
            test: {
                scripts: {
                    request: {
                        list: [],
                        pattern: {
                            name: '',
                            rules: {},
                        },
                    },
                    response: {
                        list: [],
                        pattern: {
                            name: '',
                            rules: {},
                        },
                    },
                    repeat: {
                        response: {
                            list: [],
                            pattern: {
                                name: '',
                                rules: {},
                            },
                        },
                    },
                },
            },
            example: {
                request: {
                    delay: null,
                    jws: null,
                    uri: '',
                    headers: {},
                    body: 'empty_body',
                },
                response: {
                    delay: null,
                    jws: null,
                    status: {
                        selected: null,
                    },
                    headers: {},
                    body: 'empty_body',
                },
                repeat: {
                    condition: null,
                    count: 0,
                    max: 0,
                    response: {
                        status: {
                            selected: null,
                        },
                        headers: {},
                        body: 'empty_body',
                    },
                },
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia.post(
                route('admin.test-cases.test-steps.store', this.testCase.id),
                this.getForm(),
                {
                    onFinish: () => {
                        this.sending = false;
                    },
                }
            );
        },
    },
};
</script>
<i18n src="@locales/pages/admin/test-cases/test-steps/create.json"></i18n>

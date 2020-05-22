<template>
    <layout :session="session" :test-case="testCase">
        <div class="card mb-0">
            <div class="card-header justify-content-between">
                <h2 class="card-title">
                    <b>{{ `Flow of ${testCase.name}` }}</b>
                </h2>

                <a
                    :href="editorLink"
                    target="_blank"
                    rel="noreferrer"
                    class="btn btn-outline-primary"
                >
                    Copy diagram to live editor
                </a>
            </div>
            <div class="pt-2 pb-4">
                <diagram ref="diagram">
                    {{ diagramCode }}
                </diagram>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/sessions/test-case';
import Diagram from '@/components/diagram';

import { Base64 } from 'js-base64';

export default {
    components: {
        Layout,
        Diagram
    },
    props: {
        session: {
            type: Object,
            required: true
        },
        testCase: {
            type: Object,
            required: true
        },
        testSteps: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            flowData: {
                code: null,
                mermaid: {
                    sequence: {
                        diagramMarginX: 30,
                        width: 185,
                        height: 60
                    },
                    theme: null
                }
            },
            editorUrl:
                'https://mermaid-js.github.io/mermaid-live-editor/#/edit/'
        };
    },
    mounted() {
        this.flowData.code = this.diagramCode;
    },
    computed: {
        editorLink() {
            const encodedDiagramData = Base64.encodeURI(
                JSON.stringify(this.flowData)
            );

            return `${this.editorUrl}${encodedDiagramData}`;
        },
        diagramCode() {
            return this.testSteps.data.reduce((template, testStep) => {
                template += `
${testStep.source.name} ->> ${testStep.target.name}: ${testStep.method} ${testStep.path};
${testStep.target.name} -->> ${testStep.source.name}: HTTP ${testStep.response.status};
`;
                return template;
            }, 'sequenceDiagram;');
        }
    }
};
</script>

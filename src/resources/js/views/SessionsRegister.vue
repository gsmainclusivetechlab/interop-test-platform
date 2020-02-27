<template>
    <div>
        <div class="page-header">
            <h1 class="page-title mx-auto">
                <b>Create new session</b>
            </h1>
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="steps steps-counter steps-primary">
                    <span class="step-item active">
                        <span class="d-inline-block mt-2">Select SUT</span>
                    </span>
                    <span class="step-item">
                        <span class="d-inline-block mt-2">Configure SUT</span>
                    </span>
                    <span class="step-item">
                        <span class="d-inline-block mt-2">Session info</span>
                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div
                    class="d-flex flex-column align-items-center justify-content-center"
                >
                    <form class="flow-chart-wrapper" action="#" method="POST">
                        <div class="mb-6">
                            <flow-chart>
                                graph LR; p(Payer); sp(Service
                                Provider):::is-active mmo-1(Mobile Money
                                Operator 1); mojaloop(Mojaloop); mmo-2(Mobily
                                Money Operator 2); p -.-> sp --> mmo-1 -->
                                mojaloop --> mmo-2 mmo-2 --> mojaloop --> mmo-1
                                --> sp -.-> p classDef node
                                fill:#fff,stroke:#fff,color:#242529 classDef
                                clickable fill:#fff,stroke:#fff,color:#242529
                            </flow-chart>
                        </div>

                        <div class="container">
                            <router-view></router-view>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import FlowChart from '../components/FlowChart';

export default {
    components: {
        FlowChart,
    },
};
</script>

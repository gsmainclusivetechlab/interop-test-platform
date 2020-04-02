<template>
    <div role="tablist" >
        <b-card no-body class="mb-0 card">
            <b-card-header id="create-session" header-tag="header" class="p-1 card-header" role="tab">
                <b-button block href="#" v-b-toggle.accordion-1 variant="info" class="btn">
                    <h5 class="mb-0 scenario-accordion">How do I create a new session?</h5>
                </b-button>
            </b-card-header>
            <b-collapse id="accordion-1" accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <div id="demo-desc">
                        To start the interactive demo, simply press the "Start interactive demo" below and follow the red circle which will indicate where to click and give you additional information.
                    </div>
                    @include('tutorials.includes.demos.demo-create-session')
                </b-card-body>
            </b-collapse>
        </b-card>

        <b-card no-body class="mb-0 card">
            <b-card-header id="service-provider" header-tag="header" class="p-1 card-header" role="tab">
                <b-button block href="#" v-b-toggle.accordion-2 variant="info" class="btn">
                    <h5 class="mb-0 scenario-accordion">How do I execute a session?</h5>
                </b-button>
            </b-card-header>
            <b-collapse id="accordion-2" accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <div id="demo-desc">
                        To start the interactive demo, simply press the "Start interactive demo" below and follow the red circle which will indicate where to click and give you additional information.
                        <br>
                        <br>
                        You can find a Postman collection ready to use by clicking <a href="#postman-collections" v-b-toggle.accordion-3 >here</a>
                    </div>
                    @include('tutorials.includes.demos.demo-service-provider')
                </b-card-body>
            </b-collapse>
        </b-card>

       <b-card no-body class="mb-0 card">
            <b-card-header id="postman-collections" header-tag="header" class="p-1 card-header" role="tab">
                <b-button block href="#" v-b-toggle.accordion-3 variant="info" class="btn">
                    <h5 class="mb-0 scenario-accordion">Postman Collections</h5>
                </b-button>
            </b-card-header>
            <b-collapse id="accordion-3" accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <div id="demo-desc">
                        Below is an updated list of Postman Collections you can use in the test platform:
                        <br><br><br>
                        <li>
                            <a href="https://documenter.getpostman.com/view/1386725/SzYUaM2p?version=latest" target="_blank" style="margin-left: 20px">Service Provider Simulator</a>
                        </li>
                    </div>
                </b-card-body>
            </b-collapse>
        </b-card>

        <b-card no-body class="mb-0 card">
            <b-card-header id="mojaloop-info" header-tag="header" class="p-1 card-header" role="tab">
                <b-button block href="#" v-b-toggle.accordion-4 variant="info" class="btn">
                    <h5 class="mb-0 scenario-accordion">Learn more about Mojaloop</h5>
                </b-button>
            </b-card-header>
            <b-collapse id="accordion-4" accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <div id="demo-desc">
                        To learn more about Mojaloop, you can follow the links below:
                        <br><br><br>
                        <li>
                            <a href="https://mojaloop.io/documentation/" target="_blank" style="margin-left: 20px">Mojaloop documentation</a>
                        </li>
                        <li>
                            <a href="https://mojaloop.io/mojaloop-specification/" target="_blank" style="margin-left: 20px">Mojaloop specification</a>
                        </li>
                    </div>
                </b-card-body>
            </b-collapse>
        </b-card>

        <b-card no-body class="mb-0 card">
            <b-card-header id="mobile-money-api-info" header-tag="header" class="p-1 card-header" role="tab">
                <b-button block href="#" v-b-toggle.accordion-5 variant="info" class="btn">
                    <h5 class="mb-0 scenario-accordion">Learn more about Mobile Money API</h5>
                </b-button>
            </b-card-header>
            <b-collapse id="accordion-5" accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <div id="demo-desc">
                        To learn more about Mojaloop, you can follow the links below:
                        <br><br><br>
                        <li>
                            <a href="https://www.gsma.com/mobilefordevelopment/mobile-money/mobile-money-api/" target="_blank" style="margin-left: 20px">GSMA Mobile Money API website</a>
                        </li>
                        <li>
                            <a href="https://developer.mobilemoneyapi.io/" target="_blank" style="margin-left: 20px">GSMA Mobile Money API developer portal</a>
                        </li>
                    </div>
                </b-card-body>
            </b-collapse>
        </b-card>

<!--        <b-card no-body class="mb-0 card">
            <b-card-header header-tag="header" class="p-1 card-header" role="tab">
                <b-button block href="#" v-b-toggle.accordion-6 variant="info" class="btn btn-link">
                    <h5 class="mb-0">Tutorial #6</h5>
                </b-button>
            </b-card-header>
            <b-collapse id="accordion-6" accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <b-card-text>Text here...</b-card-text>
                </b-card-body>
            </b-collapse>
        </b-card> -->
    </div>
</template>

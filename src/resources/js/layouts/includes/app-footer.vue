<template>
    <footer class="footer footer-transparent">
        <div class="container-fluid">
            <div class="row text-center align-items-center flex-row-reverse">
                <div class="col-lg-auto ml-lg-auto">
                    <ul class="list-inline list-inline-dots mb-0">
                        <li class="list-inline-item">
                            <a href="https://www.gsma.com/aboutus/legal" class="link-secondary" target="_blank">
                                Legal
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="https://www.gsma.com/aboutus/legal/cookie-policy" class="link-secondary" target="_blank">
                                Cookies
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                    {{ `Copyright © ${new Date().getFullYear()}` }}
                    <a href="https://www.gsma.com/" class="link-secondary" target="_blank">
                        GSMA
                    </a>.
                    All rights reserved.
                </div>
            </div>
        </div>
    </footer>
</template>

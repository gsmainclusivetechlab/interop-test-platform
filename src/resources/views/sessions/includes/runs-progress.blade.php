<b-progress class="w-100 h-3 rounded-0 progress">
    <b-progress-bar :value={{ $session->runs_count ? $session->pass_runs_count / $session->runs_count * 100 : 0 }} variant="success" v-b-tooltip.hover title="{{ __(':n Pass', ['n' => $session->pass_runs_count]) }}"></b-progress-bar>
    <b-progress-bar :value={{ $session->runs_count ? $session->fail_runs_count / $session->runs_count * 100 : 0 }} variant="danger" v-b-tooltip.hover title="{{ __(':n Fail', ['n' => $session->fail_runs_count]) }}"></b-progress-bar>
</b-progress>

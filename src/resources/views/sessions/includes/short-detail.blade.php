<div class="card">
    <div class="card-header flex-column align-items-start border-bottom py-4">
        <div class="d-flex align-items-center w-100 mb-2">
            @include('sessions.includes.runs-progress', $session)
            <b-dropdown class="item-action" no-caret menu-class="dropdown-menu-arrow" toggle-class="icon text-align-right text-decoration-none p-0" variant="link" boundary="window">
                <template v-slot:button-content>
                    <i class="fe fe-more-horizontal ml-auto"></i>
                </template>
                <b-dropdown-item href="{{ route('sessions.show', $session) }}">
                    {{ __('View') }}
                </b-dropdown-item>
            </b-dropdown>
        </div>
        <h2 class="card-title">
            <b>{{ $session->name }}</b>
        </h2>
        <p class="mb-0">
            {{ \Illuminate\Support\Str::limit($session->description) }}
        </p>
    </div>
    <div class="card-body py-4">
        <ul class="list-unstyled">
            <li>
                <i class="fe fe-briefcase"></i>
                {{ $session->suites_count }}
            </li>
            <li>
                <i class="fe fe-file-text"></i>
                {{ $session->cases_count }}
            </li>
        </ul>
    </div>
</div>

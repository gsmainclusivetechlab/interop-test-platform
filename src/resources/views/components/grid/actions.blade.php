<b-nav>
    <b-nav-item-dropdown class="item-action" no-caret right menu-class="dropdown-menu-arrow" toggle-class="icon">
        <template v-slot:button-content>
            <i class="fe fe-more-vertical"></i>
        </template>
        {{ $slot }}
    </b-nav-item-dropdown>
</b-nav>

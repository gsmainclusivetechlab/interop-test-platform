@if ($paginator->hasPages())
    <div class="row">
        <div class="col-md-6">
            {{ __('Showing :from to :to of :total entries', [
                'from' => (($paginator->currentPage() - 1) * $paginator->perPage()) + 1,
                'to' => (($paginator->currentPage() - 1) * $paginator->perPage()) + $paginator->count(),
                'total' => $paginator->total(),
            ]) }}
        </div>
        <div class="col-md-6">
            <div class="justify-content-end d-flex">
                {{ $paginator->links() }}
            </div>
        </div>
    </div>
@endif

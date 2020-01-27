@if ($paginator->count())
    <div class="row align-items-center">
        <div class="col-md-6">
            {{ __('Showing :from to :to of :total entries', [
                'from' => (($paginator->currentPage() - 1) * $paginator->perPage()) + 1,
                'to' => (($paginator->currentPage() - 1) * $paginator->perPage()) + $paginator->count(),
                'total' => $paginator->total(),
            ]) }}
        </div>
        <div class="col-md-6">
            <div class="justify-content-end d-flex">
                {{ $paginator->appends(request()->all())->links() }}
            </div>
        </div>
    </div>
@endif

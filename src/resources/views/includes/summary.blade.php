{{ __('Showing :from to :to of :total entries', [
    'from' => (($paginator->currentPage() - 1) * $paginator->perPage()) + 1,
    'to' => (($paginator->currentPage() - 1) * $paginator->perPage()) + $paginator->count(),
    'total' => $paginator->total(),
]) }}

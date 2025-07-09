<div class="card">
    <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
                <span class="badge bg-label-{{ $color }} p-2">
                    <i class="bx {{ $icon }} text-{{ $color }}"></i>
                </span>
            </div>
        </div>

        <span class="fw-semibold d-block mb-1">
            {{ $label }} {{ $daily ? '*' : '' }}
        </span>

        <h3 class="card-title mb-2">{{ $value }}</h3>

        @if($percentage > 0)
            <small class="text-success fw-semibold">
                <i class="bx bx-up-arrow-alt"></i> {{ $percentage }}%
            </small>
        @elseif($percentage < 0)
            <small class="text-danger fw-semibold">
                <i class="bx bx-down-arrow-alt"></i> {{ $percentage }}%
            </small>
        @endif
    </div>
</div>

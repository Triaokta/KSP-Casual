<div class="card h-100">
    <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
        <div class="mb-3">
            <span class="badge bg-label-{{ $color }} p-2">
                <i class="bx {{ $icon }} text-{{ $color }}" style="font-size: 1.5rem;"></i>
            </span>
        </div>

        <span class="fw-semibold d-block mb-2">
            {{ $label }} {{ $daily ? '*' : '' }}
        </span>

        <h3 class="mb-2">{{ $value }}</h3>

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

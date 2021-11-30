
<div class="agents-grid">

    <div class="agents-grid-wrap">

        <div class="fr-grid-thumb">
            @if ($agency->id)
                <a href="{{ route('public.agency', $agency->id) }}">
                    <img src="{{ $agency->avatar_url }}" class="img-fluid mx-auto" alt="{{ $agency->name }}">
                </a>
            @else
                <img src="{{ $agency->avatar_url }}" class="img-fluid mx-auto" alt="{{ $agency->name }}">
            @endif
        </div>

        <div class="fr-grid-detail">
            <div class="fr-grid-detail-flex">
                <h5 class="fr-can-name">
                    @if ($agency->id)
                        <a href="{{ route('public.agency', $agency->id) }}">{{ $agency->title }}</a>
                    @else
                        {{ $agency->name }}
                    @endif
                </h5>
            </div>
            <div class="fr-grid-detail-flex-right">
                <div class="agent-email"><a href="mailto:{{ $agency->email }}"><i class="fa fa-envelope"></i></a></div>
            </div>
        </div>

    </div>

    <div class="fr-grid-info">
        <ul>
            <li><strong>{{ __('Phone') }}:</strong> {{ $agency->phone }}</li>
            <li><strong>{{ __('fax') }}:</strong> {{ $agency->fax }}</li>
            <li><strong>{{ __('Email') }}:</strong> {{ $agency->email }}</li>
        </ul>
    </div>

    <div class="fr-grid-footer">
        <div class="fr-grid-footer-flex">
            <span class="fr-position"><i class="fa fa-home"></i>{{ __(':count properties', ['count' => $agency->properties_count]) }}</span>
        </div>
        @if ($agency->id)
            <div class="fr-grid-footer-flex-right">
                <a href="{{ route('public.agency', $agency->id) }}" class="prt-view" tabindex="0">{{ __('View') }}</a>
            </div>
        @endif
    </div>

</div>



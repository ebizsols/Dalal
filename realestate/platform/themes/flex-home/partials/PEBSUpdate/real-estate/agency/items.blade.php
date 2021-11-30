<div class="agents-grid">

    

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

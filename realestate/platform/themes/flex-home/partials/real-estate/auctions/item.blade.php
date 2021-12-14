
<div class="agents-grid">

    <div class="agents-grid-wrap">

        <div class="fr-grid-thumb">
            @if ($auction->title)
                <a href="{{ route('public.auctions', $auction->title) }}">
                    <img src="{{ $auction->avatar_url }}" class="img-fluid mx-auto" alt="{{ $auction->title }}">
                </a>
            @else
                <img src="{{ $auction->avatar_url }}" class="img-fluid mx-auto" alt="{{ $auction->title }}">
            @endif
        </div>

        <div class="fr-grid-detail">
            <div class="fr-grid-detail-flex">
                <h5 class="fr-can-name">
                    @if ($auction->title)
                        <a href="{{ route('public.auctions', $auction->title) }}">{{ $auction->title }}</a>
                    @else
                        {{ $auction->title}}
                    @endif
                </h5>
            </div>
            <div class="fr-grid-detail-flex-right">
                <div class="agent-email"><a href="mailto:{{ $auction->email }}"><i class="fa fa-envelope"></i></a></div>
            </div>
        </div>

    </div>

    <div class="fr-grid-info">
        <ul>
            <li><strong>{{ __('minimum_selling_price') }}:</strong> {{ $auction->minimum_selling_price}}</li>
            <li><strong>{{ __('start_date') }}:</strong> {{ $auction->start_date }}</li>
            <li><strong>{{ __('end_date') }}:</strong> {{ $auction->end_date }}</li>
        </ul>
    </div>

    <div class="fr-grid-footer">
{{--        <div class="fr-grid-footer-flex">--}}
{{--            <span class="fr-position"><i class="fa fa-home"></i>{{ __(':count properties', ['count' => $auction->properties_count]) }}</span>--}}
{{--        </div>--}}
        @if ($auction->title)
            <div class="fr-grid-footer-flex-right">
                <a href="{{ route('public.auction', $auction->id) }}" class="prt-view" tabindex="0">{{ __('View') }}</a>
            </div>
        @endif
    </div>

</div>

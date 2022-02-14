
<div  class="row">
    
<div class="agents-grid">

    <div class="agents-grid-wrap">

        <div class="fr-grid-thumb">
            @if ($auction->title)
                <a style="text-align: center" href="{{ route('public.auctions', $auction->title) }}">
                    <img src="{{ $auction->avatar_url }}" class="img-fluid mx-auto" alt="{{ $auction->title }}" width="250"; height="150">
                </a>
            @else
                <img src="{{ $auction->avatar_url }}" class="img-fluid mx-auto" alt="{{ $auction->title }}" width="250"; height="150">
            @endif
        </div>

        <div class="fr-grid-detail">
            <div class="fr-grid-detail-flex">
                <h5 class="fr-can-name" style="text-align: center;font-weight:bold; padding-top:10px;">
                    @if ($auction->title)
                        <a style="text-decoration: none;"  href="{{ route('public.auctions', $auction->title) }}">{{ $auction->title }}</a>
                    @else
                        {{ $auction->title}}
                    @endif
                </h5>
            </div>
            {{-- <div class="fr-grid-detail-flex-right">
                <div class="agent-email"><a href="mailto:{{ $auction->email }}"><i class="fa fa-envelope"></i></a></div>
            </div> --}}
        </div>

    </div>

    <div class="fr-grid-info">
        <ul style="list-style: none;">
            <li style="text-align:center;font-size:12px;"><strong style="margin-left:-40px;font-size:15px;">{{ __('minimum selling price') }}:</strong> {{ $auction->minimum_selling_price}}</li>
            <li style="text-align:center;font-size:12px;"><strong style="margin-left:-40px;font-size:15px;">{{ __('start date') }}:</strong> {{ $auction->start_date }}</li>
            <li style="text-align:center;font-size:12px;"><strong style="margin-left:-40px;font-size:15px;">{{ __('end date') }}:</strong> {{ $auction->end_date }}</li>
        </ul>
    </div>

   <div class="fr-grid-footer">
     <div class="fr-grid-footer-flex" style="text-align: center; padding-bottom:20px;">
{{-- <span class="fr-position"><i class="fa fa-home"></i>{{ __(':count properties', ['count' => $auction->properties_count]) }}</span> --}} 
     </div>
         @if ($auction->title)
            <div class="fr-grid-footer-flex-right">
                <a href="{{ route('public.auction', $auction->id) }}" class="prt-view btn btn-primary" style="margin-left:10px; margin-bottom:50px;" tabindex="0">{{ __('View') }}</a>
                {{-- <div class="fr-grid-detail-flex-right">
                    <div class="agent-email" --}}
    {{-- <a href="mailto:{{ $auction->email }}"><i style="margin-left:80px; margin-bottom:0px!important"; class="fa fa-envelope"></i></a> --}}
                {{-- </div>
                </div> --}}
            </div>
        @endif
         
    </div>
   

</div>

</div> 
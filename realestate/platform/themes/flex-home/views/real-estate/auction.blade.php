
<?php
echo"<pre>" ; print_r( $auction); exit();


?>

<section class="main-homes">
    <div class="bgheadproject hidden-xs" style="background: url('{{ theme_option('breadcrumb_background') ? RvMedia::url(theme_option('breadcrumb_background')) : Theme::asset()->url('images/banner-du-an.jpg') }}')">
        <div class="description">
            <div class="container-fluid w90">
                <h1 class="text-center">{{ $auction->title }}</h1>
                <p class="text-center">{{ $auction->description }}</p>
                {!! Theme::partial('breadcrumb') !!}
            </div>
        </div>
    </div>
    <div class="container-fluid w90 padtop30">
{{--        <div class="rowm10">--}}
{{--            <h5 class="headifhouse">{{ __('Agent info') }}</h5>--}}
{{--            <div>--}}
                <div style="float: left; padding-right: 15px;">
                    @if ($auction->title)
                        <a href="{{ route('public.auction', $auction->title) }}">
                            @if ($auction>avatar->url)
                                <img src="{{ RvMedia::getImageUrl($auction->avatar->url, 'thumb') }}" alt="{{ $auction->title}}" class="img-thumbnail">
                            @else
                                <img src="{{ $auction->avatar_url }}" alt="{{ $auction->title}}" class="img-thumbnail">
                            @endif
                        </a>
                    @else
                        @if ($auction->avatar->url)
                            <img src="{{ RvMedia::getImageUrl($auction->avatar->url, 'thumb') }}" alt="{{ $auction->title}}" class="img-thumbnail">
                        @else
                            <img src="{{ $auction->avatar_url }}" alt="{{ $auction->title}}" class="img-thumbnail">
                        @endif
                    @endif
                </div>
                <div style="float: left">
                    <h4>{{ $auction->title}}</h4>
                    <p><strong>{{ __('minimum_selling_price') }}</strong>: {{ $auction->minimum_selling_price}}</p>
                    <p><strong>{{ __('start_date') }}</strong>: {{ $auction->start_date }}</p>
                    <p><strong>{{ __('end_date') }}</strong>: {{ $auction->end_date}}</p>

                    <p><strong>{{ __('Joined on') }}</strong>: {{ $account->created_at->toDateString() }}</p>
                </div>
                <div class="clearfix"></div>
            </div>

            <br>

            @if ($properties->count())
                <h5 class="headifhouse">{{ __('Properties by this agent') }}</h5>
                <div class="projecthome px-2">
                    <div class="row">
                        @foreach ($properties as $property)
                            <div class="col-6 col-sm-6 col-md-3 colm10">
                                {!! Theme::partial('real-estate.properties.item', ['property' => $property]) !!}
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="item">{{ __('0 results') }}</p>
            @endif
        </div>
    </div>
</section>
<br>
<div class="col-sm-12">
    <nav class="d-flex justify-content-center pt-3" aria-label="Page navigation example">
        {!! $properties->withQueryString()->links() !!}
    </nav>
</div>
<br>
<br>

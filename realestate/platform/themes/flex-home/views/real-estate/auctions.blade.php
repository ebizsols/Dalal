
     <section class="main-homes">
    <div class="bgheadproject hidden-xs" style="background: url('{{ theme_option('breadcrumb_background') ? RvMedia::url(theme_option('breadcrumb_background')) : Theme::asset()->url('images/banner-du-an.jpg') }}')">
        <div class="description">
            <div class="container-fluid w90">
                <h1 class="text-center">{{ __('Auctions') }}</h1>
                {!! Theme::partial('breadcrumb') !!}
            </div>
        </div>
    </div>
   
    <div class="container w90 padtop30" style="margin-top: 80px; margin-left:150px;">
        <div class="row">
            

            @if ($auctions->count())
                <div class="container-fluid">
                    <div class="row row-10 list-auctions">
                        @foreach($auctions as $auction)
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                {!! Theme::partial('real-estate.auctions.item', compact('auction')) !!}
                            </div>
                        @endforeach
                        <div class="col-10 col-sm-12">
                            <nav class="d-flex justify-content-center pt-3">
                                {!! $auctions->withQueryString()->links() !!}
                            </nav>
                        </div>
                    </div>
                </div>
            @else
                <p class="item">{{ __('0 results') }}</p>
            @endif
        </div>
   
    </div>



  
</section>
<br>
<br>





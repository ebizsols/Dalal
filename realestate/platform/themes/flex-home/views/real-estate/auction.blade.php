<style>
    .nav-tabs>.active>a {
        background-color: white;
        color: green;
    }

</style>
{{-- @php  echo "here---"; exit();     @endphp --}}

<section class="main-homes">
    <div class="bgheadproject hidden-xs"
        style="background: url('{{ theme_option('breadcrumb_background')? RvMedia::url(theme_option('breadcrumb_background')): Theme::asset()->url('images/banner-du-an.jpg') }}')">
        <div class="description">
            <div class="container-fluid w90">
                <h1 class="text-center">{{ $auction->title }}</h1>
                {{-- <p class="text-center">{{ $auction->description }}</p> --}}
                {!! Theme::partial('breadcrumb') !!}
            </div>
        </div>
    </div>


    <div class="container-fluid w90 padtop30">


        </form>
        @if (Session::has('bidInsertSuccess'))
            <div class="alert alert-success">
                <h6 class="text-center">{{ Session::get('bidInsertSuccess') }}</h6>
            </div>
        @endif

        @if (Session::has('bidNotInsert'))
            <div class="alert alert-danger">
                <h6 class="text-center">{{ Session::get('bidNotInsert') }}</h6>
            </div>
        @endif
        <div class="row-10">
            <div class="row">
                <div class="col-md-5">

                    <img src="{{ RvMedia::getImageUrl($auction->avatar->url, 'thumb') }}" alt="{{ $auction->name }}"
                        width="250" class="img-thumbnail" style="margin-left:100px; margin-top:60px; height:200px;">

                    <div class="row">
                        <div class="col-md-2">
                            <img src="{{ RvMedia::getImageUrl($auction->avatar->url, 'thumb') }}"
                                alt="{{ $auction->name }}" width="70" class="img-thumbnail"
                                style="margin-left:100px; margin-top:10px; height:50px;">
                        </div>
                        <div class="col-md-2">
                            <img src="{{ RvMedia::getImageUrl($auction->avatar->url, 'thumb') }}"
                                alt="{{ $auction->name }}" width="70" class="img-thumbnail"
                                style="margin-left:100px; margin-top:10px; height:50px;">
                        </div>
                        <div class="col-md-2">
                            <img src="{{ RvMedia::getImageUrl($auction->avatar->url, 'thumb') }}"
                                alt="{{ $auction->name }}" width="70" class="img-thumbnail"
                                style="margin-left:100px; margin-top:10px; height:50px;">
                        </div>
                        <p></p>

                    </div> <!-- row -->
                </div><!--  col-md-5 -->

                <div class="col-md-7 mt-5 ">
                    <div class="">

                        <h4 class="font-weight-bold"><a style="margin-left:80px;"
                                href="{{ route('public.auction', $auction->title) }}">{{ $auction->title }}</a>
                        </h4>
                        {{-- <P class="text-muted" style="font-family:Serif;">{{ $auction->description }}</P> --}}
                        <p class="font-weight-bold ml-5"> Current bid: {{ $max_bid }}</p>
                        <div style="margin-left: 50px;">
                            <p style="font-family:Serif;"><strong>Minimum Selling Price:</strong>
                                {{ $auction->minimum_selling_price }}</p>
                            <p style="font-family:Serif;"><strong>Start Date:&nbsp;
                                    &nbsp;</strong>{{ $auction->start_date }}</p>
                            <p style="font-family:Serif;"><strong>End Date:&nbsp;
                                    &nbsp;</strong>{{ $auction->end_date }}</p>

                            

                            @if ($isAuctionExpire==false)
                                <form method="post" action="{{ route('public.bid') }}">
                                    @csrf
                                    <input type="Number" class="form-control" name="bid_amount" style="width:100px;"
                                        value="{{ $max_bid }}"><br>
                                    <input type="hidden" name="auction_id" value="{{ $auction->id }}">

                                    @if (auth('account')->check())
                                        <button class="btn btn-primary" style="margin-right:-370px;" id="bid"><i
                                                style="font-size:small;" class="fa fa-envelope">&nbsp;Bid</i></button>
                                        <input type="hidden" name="user_id" value="{{ auth('account')->id() }}">
                                        <span>
                                            <a class="btn btn-primary bg-white" type="submit"
                                                style=" font-family:serif;width: 170px; border-radius: 50px; margin-left:400px; border: 1px solid #00802b; color:#00802b;">BUY
                                                NOW</a>
                                        </span>

                                    @else
                                        <span>
                                            <a href="{{ route('public.account.login') }}"
                                                class="btn btn-primary bg-white" type="submit"
                                                style=" font-family:serif;width: 170px; border-radius: 50px; margin-left:400px; border: 1px solid #00802b; color:#00802b;">Login</a>
                                        </span>
                                    @endif

                        </div>
                    </form>

                    @else
                        <h4 class="text-center text-success font-weight-bold" style="margin-right:330px;">Auction Has
                            Expired</h4>

                        @endif




                    </div>



                </div> <!-- content -->

            </div> <!-- col-md-7 -->









            <div class="container" style="margin-top: 150px;" id="tabs">
                <ul class="nav nav-tabs nav-justified">
                    <li class="bg-primary"
                        style=" display:flex; justify-content: center; align-items: center; padding: 10px; height:50px; flex: auto; border-right: 1px solid white; text-align: center; font-size:13px;font-weight: bold;">
                        <a data-toggle="tab" style="text-decoration: none; " class="text-white"
                            href="#desc">DESCRIPTION</a>
                    </li>
                    <li class="bg-primary "
                        style="display:flex; justify-content: center; align-items: center; padding: 10px; height:50px; flex: auto; border-right: 1px solid white; text-align: center; font-size:13px;font-weight: bold; ">
                        <a data-toggle="tab" class="text-white" style="text-decoration: none;"
                            href="#history">AUCTION HISTORY</a>
                    </li>
                    <li class="bg-primary"
                        style="display:flex; justify-content: center; align-items: center; padding: 10px;height:50px; flex:auto; border-right: 1px solid white; text-align: center; font-size:13px;font-weight: bold;">
                        <a data-toggle="tab" class="text-white" style="text-decoration: none; " href="#offers">MORE
                            OFFERS</a>
                    </li>
                    <li class="bg-primary"
                        style="display:flex; justify-content: center; align-items: center; width:150px!important;height:50px; padding: 10px; flex:auto; border-right: 1px solid white; text-align: center; font-size:13px;font-weight: bold;">
                        <a data-toggle="tab" style="text-decoration: none; " class="text-white" href="#store">STORE
                            PILICIES</a>
                    </li>
                    <li class="bg-primary"
                        style="display:flex; justify-content: center; align-items: center; width:150px!important;height:50px; padding: 10px; flex:auto; border-right: 1px solid white; text-align: center; font-size:13px;font-weight: bold;">
                        <a data-toggle="tab" style="text-decoration: none; " class="text-white"
                            href="#inquries">INQUIRIES</a>
                    </li>



                </ul>

                <div class="tab-content container mt-5">
                    <div class="tab-pane" id="desc">
                        <h3 class="font-weight-bold font-family-firacode">Description</h3>
                        <p>the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                            galley of type and scrambled it to make a type specimen book. It has survived not only five
                            centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
                            It was popularised in the 1960s with the</p>
                    </div>

                    <div class="tab-pane" id="history">
                        <h3 class="font-weight-bold" style="font-family: serif;">Auction History</h3>
                        <p style="font-family: serif;">the industry's standard dummy text ever since the 1500s, when an
                            unknown printer took a galley of type and scrambled it to make a type specimen book. It has
                            survived not only five centuries, but also the leap into electronic typesetting, remaining
                            essentially unchanged. It was popularised in the 1960s with the</p>
                    </div>

                    <div class="tab-pane" id="offers">
                        <h3 class="font-weight-bold" style="font-family: serif;">More Offers</h3>
                        <p style="font-family: serif;">the industry's standard dummy text ever since the 1500s, when an
                            unknown printer took a galley of type and scrambled it to make a type specimen book. It has
                            survived not only five centuries, but also the leap into electronic typesetting, remaining
                            essentially unchanged. It was popularised in the 1960s with the</p>
                    </div>
                    <div class="tab-pane" id="store">
                        <h3 style="font-family: serif;" class="font-weight-bold">Store Plicies</h3>
                        <p style="font-family: serif;">the industry's standard dummy text ever since the 1500s, when an
                            unknown printer took a galley of type and scrambled it to make a type specimen book. It has
                            survived not only five centuries, but also the leap into electronic typesetting, remaining
                            essentially unchanged. It was popularised in the 1960s with the</p>
                    </div>
                    <div class="tab-pane" id="inquries">
                        <h3 style="font-family: serif;" class="font-weight-bold">Inquiries</h3>
                        <p style="font-family: serif;">the industry's standard dummy text ever since the 1500s, when an
                            unknown printer took a galley of type and scrambled it to make a type specimen book. It has
                            survived not only five centuries, but also the leap into electronic typesetting, remaining
                            essentially unchanged. It was popularised in the 1960s with the</p>
                    </div>


                </div><!--  container -->


                <table class="table table-stripped table-borderless mt-5"
                    style="border-left: 1px solid lightgrey; border-right:1px solid lightgrey; border-bottom: 1px solid lightgrey;">
                    <thead>
                        <tr>
                            <th
                                style="font-size:10px;height:10px;  justify-items: center; border: 1px solid lightgrey; ">
                                Date</th>
                            <th style="font-size:10px;justify-items:center;border: 1px solid lightgrey;">Bid</th>
                            <th style="font-size:10px; justify-items:center;border: 1px solid lightgrey;">User</th>
                            <th style="font-size:10px; justify-items:center;border: 1px solid lightgrey;">Auto</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-size:9px;" class="text-muted ">December 3,2021 5:52pm</td>
                            <td style="font-size:9px;" class="text-muted ">$178.00</td>
                            <td style="font-size:9px;" class="text-muted ">Auction Module</td>


                        </tr>

                        <tr>
                            <td style="font-size: 9px;" class="text-muted">December 3,2021 5:52pm</td>
                            <td style="font-size: 9px;" class="text-muted ">$178.00</td>
                            <td style="font-size: 9px;" class="text-muted ">Auction Module</td>


                        </tr>

                        <tr>
                            <td style="font-size:9px;" class="text-muted ">December 3,2021 5:52pm</td>
                            <td style="font-size:9px;" class="text-muted ">$178.00</td>
                            <td style="font-size:9px;" class="text-muted ">Auction Module</td>


                        </tr>

                        <tr>
                            <td style="font-size:9px;" class="text-muted">December 3,2021 5:52pm</td>
                            <td style="font-size:9px;" class="text-muted ">$178.00</td>
                            <td style="font-size:9px;" class="text-muted ">Auction Module</td>


                        </tr>

                        <tr>
                            <td style="font-size:9px;" class="text-muted ">December 3,2021 5:52pm</td>
                            <td style="font-size:9px;" class="text-muted ">$178.00</td>
                            <td style="font-size:9px;" class="text-muted">Auction Module</td>


                        </tr>

                        <tr>
                            <td style="font-size:9px;" class="text-muted">December 3,2021 5:52pm</td>
                            <td style="font-size:9px;" class="text-muted">$178.00</td>
                            <td style="font-size:9px;" class="text-muted">Auction Module</td>


                        </tr>

                        <tr>
                            <td style="font-size:9px;" class="text-muted ">December 3,2021 5:52pm</td>
                            <td style="font-size:9px;" class="text-muted ">$178.00</td>
                            <td style="font-size:9px;" class="text-muted ">Auction Module</td>


                        </tr>

                        <tr>
                            <td style="font-size: 9px;" class="text-muted ">December 3,2021 5:52pm</td>
                            <td style="font-size: 9px;" class="text-muted ">$178.00</td>
                            <td style="font-size:9px;" class="text-muted ">Auction Module</td>


                        </tr>

                        <tr>
                            <td style="font-size:9px;" class="text-muted ">December 3,2021 5:52pm</td>
                            <td style="font-size:9px;" class="text-muted ">$178.00</td>
                            <td style="font-size:9px;" class="text-muted ">Auction Module</td>


                        </tr>
                        <tr>
                            <td style="font-size:9px;" class="text-muted ">December 3,2021 5:52pm</td>
                            <td style="font-size:9px;" class="text-muted ">$178.00</td>
                            <td style="font-size:9px;" class="text-muted ">Auction Module</td>


                        </tr>
                        <tr>
                            <td style="font-size:9px;" class="text-muted ">December 3,2021 5:52pm</td>
                            <td style="font-size:9px;" class="text-muted">$178.00</td>
                            <td style="font-size:9px;" class="text-muted">Auction Module</td>


                        </tr>
                    </tbody>
                </table>

            </div><!--  row -->




            <script src="{{ asset('public\js\countdown.js') }}"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

            <script>
                type = "text/javascript" >
                    $('#timer').countdown('2022/01/31', function(event) {
                        $(this).html(event.strftime('% m months %w weeks %d days %H Hours %M Minutes %S Sec.'));
                    });
            </script>

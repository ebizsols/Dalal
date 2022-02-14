<div class="container-fluid w90 padtop30">


</form>


@if (Session::has('bidInsertSuccess'))

<div class="alert alert-success session">
        <h6 class="text-center">{{ Session::get('bidInsertSuccess') }}</h6>
    </div>
    <script>


        $(document).ready(function(){
            $("html,body").scrollTop(900);
        });
        </script>
    
  
@endif

@if (Session::has('bidNotInsert'))
    <div class="alert alert-danger danger">
        <h6 class="text-center">{{ Session::get('bidNotInsert') }}</h6>
    </div>
    <script>


        $(document).ready(function(){
            $("html,body").scrollTop(900);
        });
        </script>


@endif
<div class="row-10">
    {{-- <div class="row"> --}}
        {{-- <div class="col-md-5">
            {{-- @php  echo "<pre>test----"; print_r($auction); exit(); @endphp --}}

            {{-- <img src="{{ RvMedia::getImageUrl($auction->avatar->url, 'thumb') }}" alt="{{ $auction->name }}"
                width="250" class="img-thumbnail" style="margin-left:100px; margin-top:60px; height:200px;"> --}}

            {{-- <div class="row">
                <div class="col-md-2">
                    <img src="{{ RvMedia::getImageUrl($auction->avatar->url, 'thumb') }}"
                        alt="{{ $auction->name }}" width="70" class="img-thumbnail"
                        style="margin-left:100px; margin-top:10px; height:50px;"> --}}
                {{-- </div>
                <div class="col-md-2">
                    <img src="{{ RvMedia::getImageUrl($auction->avatar->url, 'thumb') }}"
                        alt="{{ $auction->name }}" width="70" class="img-thumbnail"
                        style="margin-left:100px; margin-top:10px; height:50px;"> } --}}
                {{-- </div>
                <div class="col-md-2">
                    <img src="{{ RvMedia::getImageUrl($auction->avatar->url, 'thumb') }}"
                        alt="{{ $auction->name }}" width="70" class="img-thumbnail"
                        style="margin-left:100px; margin-top:10px; height:50px;">
                </div>
                <p></p>

            </div> <!-- row -->
        </div><!--  col-md-5 --> --}}

  

        <div class="col-md- mt-3 ">
            <div class="">

                <h4 class="font-weight-bold text-center mb-5"><a style="color: #469938; "
                        href="{{ route('public.auction', $auction->title) }}">{{ $auction->title }}</a></h4>
                <P class="text-mutedo9i" style="font-family:Serif;">{{ $auction->description }}</P>
                <p style="margin-left:30px;"> <strong>Current bid:</strong> {{ $maxBid }}</p>
                <div style="margin-left: 30px;">
                    <p style="font-family:Serif;"><strong>Minimum Selling Price:</strong>
                        {{ $auction->minimum_selling_price }}</p>
                    <p style="font-family:Serif;"><strong>Start Date:&nbsp;
                            &nbsp;</strong>{{ $auction->start_date }}</p>
                    <p style="font-family:Serif;"><strong>End Date:&nbsp;
                            &nbsp;</strong>{{ $auction->end_date }}</p>
                            {{-- @php  $currentDataTime = date('Y-m-d H:i:s');
                             
                                $dateInMills = $currentDataTime->timestamp;
                                $Date =$dateInMills->milli;
                                echo"<pre>"; print_r($Data);
                                
                               @endphp --}}

                           
                       
                    @if($isAuctionExpire==false)
                    <form method="post" action="{{ route('public.bid') }}">
                        @csrf
                        <input type="Number" class="form-control" name="bid_amount" style="width:100px;"
                            value="{{ $maxBid }}"><br>
                        <input type="hidden" name="auction_id" value="{{ $auction->id }}">
                       
                        @if (auth('account')->check())
                            <button class="btn btn-primary" style="margin-right:-200px;" id="bid"><i
                                    style="font-size:small;" class="fa fa-envelope">&nbsp;Bid</i></button>
                            <input type="hidden" name="user_id" value="{{ auth('account')->id() }}">
                            {{-- <span style="float:center;">
                                <a class="btn btn-primary bg-white" type="submit"
                                    style="font-family:serif;width: 100px; border-radius: 50px; margin-left:270px; border: 1px solid #00802b; color:#00802b;">BUY
                                    NOW</a>
                            </span> --}}

                        @else
                            <span>
                                <a href="{{ route('public.account.login') }}"
                                    class="btn btn-primary bg-white" type="submit"
                                    style=" font-family:serif;width: 100px; border-radius: 50px; margin-left:50px; border: 1px solid #00802b; color:#00802b;">Login</a>
                            </span>
                        @endif

                </div>
                

                
                
            </form>

            @else
            <p class="text-center font-weight-bold text-success ">Auction has Expired</p>

                


            </div>

            


                @endif




            



        </div> <!-- content -->

    </div> <!-- col-md-7 -->

{{-- <script>


$(document).ready(function(){
    $("html,body").scrollTop(100);
});
</script> --}}
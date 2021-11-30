<section class="main-homes">
    <div class="bgheadproject hidden-xs"
        style="background: url('{{ theme_option('breadcrumb_background') ? RvMedia::url(theme_option('breadcrumb_background')) : Theme::asset()->url('images/banner-du-an.jpg') }}')">
        <div class="description">
            <div class="container-fluid w90">
                <h1 class="text-center">{{ $agency->title }}</h1>
                <p class="text-center">{{ $agency->description }}</p>
                {!! Theme::partial('breadcrumb') !!}
            </div>
        </div>
    </div>
    <div class="container-fluid w90 padtop30">
        <div class="rowm10">
            <h5 class="headifhouse row">{{ __('Info regarding Agency') }}</h5>

            <div class="row">
                <div class="col-8">
                    <div class="" style="float: left; padding-right: 15px;">
                        @if ($agency->id)
                            <a href="{{ route('public.agency', $agency->id) }}">
                                @if ($agency->avatar->url)
                                    <img src="{{ RvMedia::getImageUrl($agency->avatar->url, 'thumb') }}"
                                        alt="{{ $agency->name }}" class="img-thumbnail">
                                @else
                                    <img src="{{ $agency->avatar_url }}" alt="{{ $agency->name }}"
                                        class="img-thumbnail">
                                @endif
                            </a>
                        @else
                            @if ($agency->avatar->url)
                                <img src="{{ RvMedia::getImageUrl($agency->avatar->url, 'thumb') }}"
                                    alt="{{ $agency->name }}" class="img-thumbnail">
                            @else
                                <img src="{{ $agency->avatar_url }}" alt="{{ $agency->name }}"
                                    class="img-thumbnail">
                            @endif
                        @endif
                    </div>
                    <div style="">
                        <h4>{{ $agency->title }}</h4>
                        <p><strong>{{ __('Email') }}</strong>: {{ $agency->email }}</p>
                        <p><strong>{{ __('Phone') }}</strong>: {{ $agency->phone }}</p>
                        <p><strong>{{ __('Joined on') }}</strong>: {{ $agency->created_at->toDateString() }}</p>
                        <br>
                    </div>
                    <br>
                    <div class="shop_single_tab_content style2 mt30">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description"
                                    role="tab" aria-controls="description" aria-selected="false">Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="agent-tab" data-toggle="tab" href="#agent" role="tab"
                                    aria-controls="agent" aria-selected="false">Agents</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="listing-tab" data-toggle="tab" href="#listing" role="tab"
                                    aria-controls="listing" aria-selected="true">Listing</a>
                            </li>

                        </ul>
                        <div class="tab-content" id="myTabContent2">
                            <div class="tab-pane fade active show" id="description" role="tabpanel"
                                aria-labelledby="description-tab">
                                <div class="col-lg-12">{{$agency->description}}</div>
                            </div>
                            <div class="tab-pane fade row " id="agent" role="tabpanel" aria-labelledby="agent-tab">
                                <div class="col-lg-12">

                                    @foreach ($accountList as $accountListIn)

                                        <div style="margin: 10px 10px 10px 0px;">
                                            <div style="float: left; padding-right: 5px; height: 20px;"><a
                                                    href="http://realestate.dalal.pk/agents/{{ $accountListIn['username'] }}"><img
                                                        src="data:image/png;base64,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"
                                                        alt="Muhammad Saad Alam" class="img-thumbnail" style="height: 140px"></a></div>
                                            <div style="float: left;">
                                                <h4>{{ $accountListIn['first_name'] }}</h4>
                                                <p><strong>Email</strong>: {{ $accountListIn['email'] }}</p>
                                                <p><strong>Phone</strong>: {{ $accountListIn['phone'] }}</p>
                                                <p><strong>Confirmed at</strong>:
                                                    {{ $accountListIn['confirmed_at'] }}</p>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    @endforeach




                                </div>
                            </div>
                            <div class="tab-pane fade row" id="listing" role="tabpanel" aria-labelledby="listing-tab">
                                <div class="feat_property list style2 hvr-bxshd bdrrn">

                                    <div class="col-lg-12">
                                        @foreach ($property as $item)


                                        <div style="margin: 10px 10px 10px 0px;">
                                            <div style="float: left; padding-right: 5px; height: 20px;"><a
                                                    href="http://realestate.dalal.pk/agents/{{ $item['location'] }}"><img data-src="http://realestate.dalal.pk/vendor/core/core/base/images/placeholder.png" src="http://realestate.dalal.pk/vendor/core/core/base/images/placeholder.png"
                                                    alt="{{$item['name']}}" class="img-thumbnail" style="height: 265px"></a></div>
                                            <div style="float: left;">
                                                <h4 style="color: crimson">{{ $item['name'] }}</h4>
                                                <p><strong style="color: firebrick">Price</strong>: {{ $item['price'] }}</p>
                                                <p><strong style="color: firebrick">Number of Bedroom</strong>: {{ $item['number_bedroom'] }}</p>
                                                <p><strong style="color: firebrick">Number of Bathroom</strong>: {{ $item['number_bathroom'] }}</p>
                                                <p><strong style="color: firebrick">Number of Floor</strong>: {{ $item['number_floor'] }}</p>
                                                <p><strong style="color: firebrick">Status</strong>: {{ $item['status'] }}</p>
                                                <p><strong style="color: firebrick">Location</strong>: {{ $item['location'] }}</p>


                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>


                </div>
                <div class="col-4">

                    <form action="" method="post" class="generic-form">
                        <div class="wrapper">
                            <h2 class="h2">HOW WE CAN HELP YOU?</h2>

                            <div class="form-group">
                                <input class="form-control" type="text" name="name" placeholder="Name *" required="">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="email" placeholder="Email *"
                                    required="">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="phone" placeholder="Phone">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="content" rows="6" minlength="10"
                                    placeholder="Message *" required=""></textarea>
                            </div>

                            <div class="alert alert-success text-success text-left" style="display: none;">
                                <span></span>
                            </div>
                            <div class="alert alert-danger text-danger text-left" style="display: none;">
                                <span></span>
                            </div>
                            <br>
                            <div class="form-actions">
                                <button class="btn-special" type="submit">Send message</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <br>
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

@extends('main')

@section('title',' | The bigger hub of networking in the world')
@section('body')


<link href="{{ URL::asset('css/swiper.min.css') }}" type="text/css" rel="stylesheet" />
<script src="{{ URL::asset('js/swiper.min.js') }}"></script>
<div class="jumbotron orange no-round welcome">
    <div class="container"> 
        <div class="row justify-content-center">
            <div class="col-sm-8">
                <div class="welcome-swiper-container">
                    <div class="swiper-container welcome-swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><h1>{{ __('welcome.slide-text-1') }}{{-- {{ __('Discover companies you like to work for') }} --}}</h1></div>
                            <div class="swiper-slide"><h1>{{ __('welcome.slide-text-2') }}{{-- {{ __('Find the best professionals in the world') }} --}}</h1></div>
                            <div class="swiper-slide"><h1>{{ __('welcome.slide-text-3') }}{{-- {{ __('Work from El Salvador to United States') }} --}}</h1></div>
                            <div class="swiper-slide"><h1>{{ __('welcome.slide-text-4') }}{{-- {{ __('The american dream in your home') }} --}}</h1></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
        jQuery(function($){
            var welcome_swiper = new Swiper('.welcome-swiper',{
                effect: 'fade',
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                }
            });
        });
        </script>

        <div class="row justify-content-center pt-5">
            <div class="col-sm-4 mt-5">
                <div class="card no-border no-round">
                    <div class="card-header f-image-header multiply pt-5" style="background-image: url('{{ URL::asset('images/pj-box.jpg')}}') ; ">
                        <h4 class="mt-5 mb-1 card-title text-white">Professional Jobe</h4>
                        <span class="md-48 md-light corner-icon icon-pj icon text-color-3"></span>
                    </div>
                    <div class="card-body">
                        <p>{{ __('welcome.pj-description') }}</p>
                        <form action="" method="get">
                            <input type="hidden" name="usertype" value="pj">
                            <button type="submit" class="btn btn-success btn-block">
                                {{ __('Join as PJ') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 offset-sm-1 mt-5 mb-5">
                <div class="card no-border no-round">
                    <div class="card-header f-image-header multiply pt-5" style="background-image: url('{{ URL::asset('images/bj-box.jpg')}}') ; ">
                        <h4 class="mt-5 mb-1 card-title text-white">Bussiness Jobe</h4>
                        <span class="md-48 md-light corner-icon icon-bj icon text-color-3"></span>
                    </div>
                    <div class="card-body">
                        <p>{{ __('welcome.bj-description') }}</p><br>
                        <form action="" method="get">
                            <input type="hidden" name="usertype" value="bj">
                            <button type="submit" class="btn btn-success btn-block">
                                {{ __('Join as BJ') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
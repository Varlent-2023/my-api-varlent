@extends('layouts.app-public')
@section('title', 'Home')
@section('content')
<div class="site-wrapper-reveal">
    <div class="hero-box-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Hero Slider Area Start -->
                    <div class="hero-area" id="product-preview"></div>
                    <!-- Hero Slider Area End -->
                </div>
            </div>
        </div>
    </div>

    <div class="about-us-area section-space--ptb_120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="about-us-content_6 text-center">
                        <h2>Rotilicius&nbsp;&nbsp;Store</h2>
                        <p>
                            <small>
                                Looking for the perfect loaf or pastry? Our bakery offers a diverse selection of freshly baked goods, from classic baguettes to unique artisanal breads. Our skilled bakers are passionate about quality, ensuring every bite is a delight. With a warm and inviting atmosphere, you can relax and enjoy your treats on-site. Become part of our community and let us bring the joy of fresh, delicious baked goods to your table. Visit us today and discover the difference our dedication to baking makes. &#10084; 
                            </small>
                        </p>
                        <p class="mt-5">Discover your gateway to flavor! Enjoy the best in baked goods, from fresh breads to delightful pastries, all crafted with passion.
                            <span class="text-color-primary">Visit us and savor every bite!</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Banner Video Area Start -->
    <div class="banner-video-area overflow-hidden">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="banner-video-box">
                        <img src="https://imgs.search.brave.com/FyR3q_HwE6-HYVZ0NdDZNNMDySDZ78TlcPoPLnUEe1k/rs:fit:500:0:0/g:ce/aHR0cHM6Ly9pbWcu/ZnJlZXBpay5jb20v/cHJlbWl1bS1waG90/by9zbGljZS1icmVh/ZC1ncmFpbnMtd2hl/YXQtZGFyay13b29k/ZW4tYmFja18xMDYx/MTUwLTg2NzQuanBn/P3NpemU9NjI2JmV4/dD1qcGc" alt="">
                        <div class="video-icon">
                            <a href="https://www.youtube.com/watch?v=_7Mz6apw9Os&pp=ygUYYnJlYWQgc3RvcmUgc3RvY2sgdmlkZW8g" class="popup-youtube">
                                <i class="linear-ic-play"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="our-brand-area section-space--pb_90">
        <div class="container">
            <div class="brand-slider-active">
                @php
                    $partner_count = 8;
                @endphp 
                @for($i = 1; $i <= $partner_count; $i++)
                    <div class="col-lg-12">
                        <div class="single-brand-item">
                            <a href="#"><img src="{{ asset('assets/images/brand/partnerb' . $i . '.jpg') }}" class="img-fluid" alt="Partner Images"></a>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <div class="our-member-area section-space--pb_120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="member--box">
                        <div class="row align-items-center">
                            <div class="col-lg-5 col-md-4">
                                <div class="section-title small-mb__40 tablet-mb__40">
                                    <h4 class="section-title">Join the community!</h4>
                                    <p>Become one of the member and get discount 50% off</p>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-8">
                                <div class="member-wrap">
                                    <form action="#" class="member--two">
                                        <input class="input-box" type="text" placeholder="Your email address">
                                        <button class="submit-btn"><i class="icon-arrow-right"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('addition-css')
@endsection
@section('addition_script')
<script src="{{ asset('pages/js/home.js') }}"></script>
@endsection
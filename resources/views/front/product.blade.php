@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                    <li class="breadcrumb-item">{{ $product->title }}</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-7 pt-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col-md-5">
                    <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner bg-light">
                            @if ($product->product_images)
                                @foreach ($product->product_images as $key => $productImage)
                                    @if (!empty($productImage->image))
                                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                            <img class="w-100 h-100"
                                                 src="{{ asset('uploads/product/large/' . $productImage->image) }}"
                                                 alt="Image">
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        @if (!empty($productImage->image))
                            <a class="carousel-control-prev" href="#product-carousel" data-bs-slide="prev">
                                <i class="fa fa-2x fa-angle-left text-dark"></i>
                            </a>
                            <a class="carousel-control-next" href="#product-carousel" data-bs-slide="next">
                                <i class="fa fa-2x fa-angle-right text-dark"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="bg-light right">
                        <h1>{{ $product->title }}</h1>
                        <div class="mb-2">Còn {{ $product->qty }} hàng</div>
                        @if ($product->compare_price > 0)
                            <h2 class="price text-secondary">
                                <del>{{ number_format($product->compare_price) }} VNĐ</del>
                            </h2>
                        @endif
                        <h2 class="price ">{{ number_format($product->price) }} VNĐ</h2>
                        {!! $product->description !!}
                        <br>
                        @if($product->track_qty == 'Yes')
                            @if($product->qty > 0)
                                <div class="product-action mt-2">
                                    <a class="btn btn-dark" href="javascript:void(0);"
                                       onclick="addToCart({{ $product->id }})">
                                        <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                                    </a>
                                </div>
                            @else
                                <div class="product-action mt-2">
                                    <a class="btn btn-dark" href="javascript:void(0);">
                                        Hết hàng
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="product-action mt-2">
                                <a class="btn btn-dark" href="javascript:void(0);">
                                    Cửa hàng chưa nhập sản phẩm này
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-5 section-8">
        <div class="container">
            <div class="section-title">
                <h2>Sản phẩm mới nhất</h2>
            </div>
            <div class="col-md-12">
                <div id="related-products" class="carousel">
                    @if ($latestProducts->isNotEmpty())
                        @foreach ($latestProducts as $product)
                            @php
                                $productImage = $product->product_images->first();
                            @endphp
                            <div class="card product-card">
                                <div class="product-image position-relative">
                                    <a href="{{ route('front.product', $product->slug) }}" class="product-img">
                                        @if (!empty($productImage->image))
                                            <img class="card-img-top"
                                                 src="{{ asset('uploads/product/small/' . $productImage->image) }}">
                                        @else
                                            <img class="card-img-top"
                                                 src="{{ asset('admin-assets/img/default-150x150.png') }}">
                                        @endif
                                    </a>
                                    <div class="product-action">
                                        <a class="btn btn-dark" href="javascript:void(0);"
                                           onclick="addToCart({{ $product->id }})">
                                            <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body text-center mt-3">
                                    <a class="h6 link" href="">{{ $product->title }}</a>
                                    <div class="price mt-2">
                                        <span
                                            class="h5"><strong>{{ number_format($product->price) }} VNĐ</strong></span>
                                        @if ($product->compare_price > 0)
                                            <span class="h6 text-underline"><del>{{ number_format($product->compare_price) }}VNĐ</del></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection


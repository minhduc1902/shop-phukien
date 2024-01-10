@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Shop</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-6 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 sidebar">
                    <div class="sub-title">
                        <h2>Thể loại</h2>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="accordion accordion-flush" id="accordionExample">
                                @if ($categories->isNotEmpty())
                                    @foreach ($categories as $key => $category)
                                        <div class="accordion-item">
                                            @if ($category->sub_category->isNotEmpty())
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseOne-{{ $key }}"
                                                        aria-expanded="false" aria-controls="collapseOne">
                                                        {{ $category->name }}
                                                    </button>
                                                </h2>
                                            @else
                                                <a href="{{ route('front.shop', $category->slug) }}"
                                                    class="nav-item nav-link {{ $categorySelected == $category->id ? 'text-primary' : '' }}">{{ $category->name }}</a>
                                            @endif
                                            @if ($category->sub_category->isNotEmpty())
                                                <div id="collapseOne-{{ $key }}"
                                                    class="accordion-collapse collapse  {{ $categorySelected == $category->id ? 'show' : '' }}"
                                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample"
                                                    style="">
                                                    <div class="accordion-body">
                                                        <div class="navbar-nav">
                                                            @foreach ($category->sub_category as $subCategory)
                                                                <a href="{{ route('front.shop', [$category->slug, $subCategory->slug]) }}"
                                                                    class="nav-item nav-link {{ $subCategorySelected == $subCategory->id ? 'text-primary' : '' }}">{{ $subCategory->name }}</a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="sub-title mt-5">
                        <h2>Xuất sứ</h2>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            @if ($origins->isNotEmpty())
                                @foreach ($origins as $origin)
                                    <div class="form-check mb-2">
                                        <input {{ (in_array($origin->id, $originsArray)) ? 'checked' : '' }}
                                            class="form-check-input origin-label" name="origin[]" type="checkbox"
                                            value="{{ $origin->id }}" id="origin-{{ $origin->id }}">
                                        <label class="form-check-label" for="origin-{{ $origin->id }}">
                                            {{ $origin->name }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row pb-3">
                        <div class="col-12 pb-1">
                            <div class="d-flex align-items-center justify-content-end mb-4">
                                <div class="ml-2">
                                    <select name="sort" id="sort" class="form-control">
                                        <option value="latest" {{ ($sort == 'latest') ? 'selected' : '' }}>Mới nhất</option>
                                        <option value="price_desc" {{ ($sort == 'price_desc') ? 'selected' : '' }}>Giá giảm dần</option>
                                        <option value="price_asc" {{ ($sort == 'price_asc') ? 'selected' : '' }}>Giá tăng dần</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        @if ($products->isNotEmpty())
                            @foreach ($products as $product)
                                @php
                                    $productImage = $product->product_images->first();
                                @endphp
                                <div class="col-md-4">
                                    <div class="card product-card">
                                        <div class="product-image position-relative">
                                            <a href="{{ route('front.product', $product->slug) }}" class="product-img">
                                                @if (!empty($productImage->image))
                                                    <img class="card-img-top"
                                                        src="{{ asset('uploads/product/small/' . $productImage->image) }}"
                                                        alt="">
                                                @else
                                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                                        alt="">
                                                @endif
                                            </a>
                                            <div class="product-action">
                                                @if($product->track_qty == 'Yes')
                                                    @if($product->qty > 0)
                                                        <a class="btn btn-dark" href="javascript:void(0);"
                                                           onclick="addToCart({{ $product->id }})">
                                                            <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                                                        </a>
                                                    @else
                                                        <a class="btn btn-dark" href="javascript:void(0);">
                                                            Hết hàng
                                                        </a>
                                                    @endif
                                                @else
                                                    <a class="btn btn-dark" href="javascript:void(0);"
                                                       onclick="addToCart({{ $product->id }})">
                                                        <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-body text-center mt-3">
                                            <a class="h6 link" href="{{ route('front.product', $product->slug) }}">{{ $product->title }}</a>
                                            <div class="price mt-2">
                                                <span class="h5"><strong>{{ number_format($product->price) }} VNĐ</strong></span>
                                                @if ($product->compare_price > 0)
                                                    <span
                                                        class="h6 text-underline"><del>{{ number_format($product->compare_price) }} VNĐ</del></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center">Không tìm thấy sản phẩm nào khớp với lựa chọn của bạn.</div>
                        @endif
                        <div class="col-md-12 pt-5">
                            {{ $products->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(".origin-label").change(function() {
            apply_filters();
        });

        $("#sort").change(function() {
            apply_filters();
        });

        function apply_filters() {
            var origins = [];

            $(".origin-label").each(function() {
                if ($(this).is(":checked") == true) {
                    origins.push($(this).val());
                }
            });

            var url = '{{ url()->current() }}?';


            var keyword = $("#search").val();
            //
            if(keyword.length > 0) {
                url += '&search=' + keyword;
            }

            //Origin filter
            if (origins.length > 0) {
                url += '&origin=' + origins.toString();
            }

            //Sorting filter
            url += '&sort=' + $("#sort").val();

            window.location.href = url;
        }
    </script>
@endsection

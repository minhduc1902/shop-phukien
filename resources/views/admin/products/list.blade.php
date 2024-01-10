@extends('admin.components.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Sản phẩm</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('products.create') }}" class="btn btn-primary">Tạo sản phẩm</a>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            @include('admin.message')
            <div class="card">
                <form action="" method="get">
                    <div class="card-header">
                        <div class="card-title">
                            <button type="button" onclick="window.location.href='{{ route('products.index') }}'"
                                class="btn btn-default btn-sm">Reset</button>
                        </div>
                        <div class="card-tools">
                            <div class="input-group input-group" style="width: 250px;">
                                <input type="text" value="{{ Request::get('keyword') }}" name="keyword"
                                    class="form-control float-right" placeholder="Tìm kiếm">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th width="80">Hình ảnh</th>
                                <th>Sản phẩm</th>
                                <th>Giá </th>
                                <th>Số lượng</th>
                                <th>Mã hàng</th>
                                <th width="100">Trạng thái</th>
                                <th width="100">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($products->isNotEmpty())
                                @foreach ($products as $product)
                                    @php
                                        $productImage = $product->product_images->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            @if (!empty($productImage->image))
                                                <img src="{{ asset('uploads/product/small/' . $productImage->image) }}"
                                                    class="img-thumbnail" width="50">
                                            @else
                                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                   s                 alt="" class="img-thumbnail" width="50">
                                            @endif
                                        </td>
                                        <td><a href="">{{ $product->title }}</a></td>
                                        <td>{{ number_format($product->price) }} VNĐ</td>
                                        <td>Còn lại: {{ $product->qty }}</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>
                                            @if ($product->status == 1)
                                                <svg class="text-success-500 h-6 w-6 text-success"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @else
                                                <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('products.edit', $product->id) }}">
                                                <svg class="filament-link-icon w-4 h-4 mr-1"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a href="#" onclick="deleteProduct({{ $product->id }})"
                                                class="text-danger w-4 h-4 mr-1">
                                                <svg wire:loading.remove.delay="" wire:target=""
                                                    class="filament-link-icon w-4 h-4 mr-1"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path ath fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>Không tìm thấy bản ghi</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        function deleteProduct(id) {
            var url = '{{ route('products.delete', 'ID') }}';
            var newUrl = url.replace("ID", id);

            if (confirm("Bạn có muốn xóa không?")) {
                $.ajax({
                    url: newUrl,
                    type: 'DELETE',
                    data: {},
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.status) {
                            window.location.href = "{{ route('products.index') }}";
                        } else {
                            alert('Xóa sản phẩm thất bại.');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert(
                        'Lỗi xảy ra trong lúc xóa sản phẩm. Vui lòng thử lại.');
                    }
                });
            }
        }
    </script>
@endsection

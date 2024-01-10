@extends('admin.components.app')

@section('content')
    <!-- Navbar -->
    <!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Chỉnh sửa danh mục</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('categories.index') }}" class="btn btn-primary">Trở về</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="{{ route('categories.update', $category->id) }}" method="post" id="categoryForm" name="categoryForm">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Tên</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ $category->name }}"
                                        placeholder="Tên">
                                </div>
                                <p></p>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" readonly name="slug" id="slug" class="form-control" value="{{ $category->slug }}"
                                        placeholder="Slug">
                                </div>
                                <p></p>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Trạng thái</label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{ ($category->status == 1) ? 'selected' : '' }} value="1">Mở</option>
                                        <option {{ ($category->status == 0) ? 'selected' : '' }} value="0">Đóng</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="showHome">Hiển thị ở trang chủ</label>
                                    <select name="showHome" id="showHome" class="form-control">
                                        <option {{ ($category->showHome == 'Yes') ? 'selected' : '' }} value="Yes">Có</option>
                                        <option {{ ($category->showHome == 'No') ? 'selected' : '' }} value="No">Không</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Hủy bỏ</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
    <!-- /.content-wrapper -->
    </div>
@endsection

@section('js')
    <script>
        $('#categoryForm').submit(function(event) {
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: '{{ route('categories.update',$category->id) }}',
                type: 'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"] == true) {

						window.location.href="{{ route('categories.index') }}"

                        $('#name').removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback').html("");
                        $('#slug').removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback').html("");
                    } else {
                        if(response['notFound'] == true) {
                            window.location.href = "{{ route('categories.index') }}";
                        }

                        var errors = response['errors'];
                        if (errors['name']) {
                            $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(errors['name']);
                        } else {
                            $('#name').removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html("");
                        }

                        if (errors['slug']) {
                            $('#slug').addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(errors['slug']);
                        } else {
                            $('#slug').removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html("");
                        }
                    }
                },
                error: function(jqXHR, exception) {
                    console.log('Bug');
                }
            });
        });

        $("#name").change(function() {
			element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route("getSlug") }}',
                type: 'get',
                data: {title: element.val()},
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
					if(response["status"] == true) {
						$("#slug").val(response["slug"]);
					}
                }
            });
        });
    </script>
@endsection

@extends('layouts.app_admin')

@section('title', $stock->name . 'の変更')
@php
$menu = 'master';
$subMenu = 'stock';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.stock.edit', $stock) }}
@endsection

@section('content')

<div class="card card-purple">
    <!-- card-body -->
    <div class="card-body">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" enctype="multipart/form-data" action="{{route('admin.stock.update', ['id' => $stock->id])}}">
            @csrf

            <div class="card card-purple">
                <!-- card-body -->
                <div class="card-body">

                    <div class="form-group">
                        <div class="control-group">
                            <label class="col-sm-2 control-label">商品名</label>
                            <div class="col-sm-8">
                                <input type="text" name="name" value="{{ old('name', $stock -> name) }}" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="control-group">
                            <label class="col-sm-2 control-label">商品説明</label>
                            <div class="col-sm-8">
                                <textarea name="detail" rows="8" cols="80">{{ old('detail', $stock -> detail) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="control-group">
                            <label class="col-sm-2 control-label">価格</label>
                            <div class="col-sm-4">
                                <input type="text" name="price" value="{{ old('price', $stock -> price) }}" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="control-group">
                            <label class="col-sm-2 control-label">在庫</label>
                            <div class="col-sm-4">
                                <input type="text" name="quantity" value="{{ old('quantity', $stock -> quantity) }}" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="control-group">
                            <label class="col-sm-2 control-label">商品画像</label>

                            <div class="col-sm-2" id="drop-zone">
                                {{--
                                <p><input type="file" name="imageFile"></p>
                                <br>
                                --}}
                                <p><input id="js-uploadImage" type="file"></p>
                                <div id="result">
                                    @if (old('imageBase64'))
                                    <img src="{{ old('imageBase64') }}" width="200px" />
                                    <input type="hidden" name="imageBase64" value="{{ old('imageBase64') }}" />
                                    <input type="hidden" name="fileName" value="{{ old('fileName') }}" />
                                    @elseif ($stock -> imgpath)
                                    <img src="{{ asset('uploads/stock/' . $stock->imgpath) }}" width="200px" id="stockImage">
                                    @endif
                                </div>
                                <p class="error error-message"></p>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix ">
                    <input class="btn btn-info" type="submit" value="登録する">
                </div>
            </div>

        </form>

    </div>
    <!-- /.card-body -->

</div>

<script src="{{ asset('/assets/admin/js/stock/edit.js') }}" defer></script>


@endsection

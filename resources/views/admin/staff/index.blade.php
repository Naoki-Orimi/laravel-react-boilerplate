@extends('layouts.app_admin')

@section('title', __('staff.Staff List'))
@php
    $menu = 'system';
    $subMenu = 'staff';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.staff') }}
@endsection

@section('content')

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body text-center">
            <form method="GET" action="{{ route('admin.staff.create') }}">
                <button type="submit" class="btn btn-primary">
                    {{ __('common.Regist') }}
                </button>
            </form>
        </div>
    </div>

    <div class="card card-purple">
        <div class="card-header">
            <h3 class="card-title">{{ __('common.Search Condition') }}</h3>
        </div>
        <form action="{{ route('admin.staff') }}" method="GET">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="form-group">
                    <div class="control-group" id="staffName">
                        <label class="col-sm-2 control-label">{{ __('staff.Name') }}</label>
                        <div class="col-sm-4">
                            <input
                                type="text"
                                name="name"
                                value="{{ $request->name }}"
                                class="form-control"
                                maxlength="{{ config('const.maxlength.admins.name') }}"
                            >
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">{{ __('staff.EMail') }}</label>
                    <div class="col-sm-8">
                        <input
                            type="email"
                            name="email"
                            value="{{ $request->email }}"
                            class="form-control"
                            maxlength="{{ config('const.maxlength.admins.email') }}"
                        >
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-secondary">{{ __('common.Search') }}</button>
            </div>
        </form>
    </div>
    <form action="{{ route('admin.staff') }}" method="GET" id="pagingForm">
        <input type="hidden" name="name" value="{{ $request->name }}" />
        <input type="hidden" name="email" value="{{ $request->email }}" />
    </form>
    <div class="row">
        <div class="col-12">
            <div class="card card-purple">
                <div class="card-header">
                    <h3 class="card-title">{{ __('common.Search Result') }}</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            @include('admin.common.sortablelink_th', ['params' => ['id', __('staff.ID')]])
                            @include('admin.common.sortablelink_th', ['params' => ['name', __('staff.Name')]])
                            @include('admin.common.sortablelink_th', ['params' => ['email', __('staff.EMail')]])
                            @include('admin.common.sortablelink_th', ['params' => ['created_at', __('common.Registration Date')]])
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($staffs as $staff)
                            <tr>
                                <th>{{ $staff->id }}</th>
                                <td>{{ $staff->name }}</td>
                                <td>{{ $staff->email }}</td>
                                <td>{{ $staff->created_at }}</td>
                                <td>
                                    <a
                                        class="btn btn-info btn-sm"
                                        href="{{ route('admin.staff.show', ['staff'=> $staff]) }}"
                                    >{{ __('common.Detail') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer  ">
                    {!! $staffs->links('admin.common.pagination') !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('admin.layouts.app')
@section('title','لوحة التحكم')
@section('content')
    <div class="content">
        <div class="container">
            <!-- Page-Title -->
          @include('admin.layouts.cards')
          @include('admin.layouts.analytics')
            <!-- end row -->
        </div> <!-- container -->
    </div> <!-- content -->
@stop

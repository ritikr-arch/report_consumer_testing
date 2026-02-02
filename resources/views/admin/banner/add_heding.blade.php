@extends('admin.layouts.app')
@section('content')

<div class="px-3">
    <!-- Start Content-->
    <div class="container-fluid">

        <form action="{{route('admin.banner.heading.seve')}}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row mt-3">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center d-flex mb-3">
                                <div class="col-xl-12">
                                    <h4 class="header-title mb-0 font-weight-bold"> Banner Heading  </h4>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-6">

                                    <label for="exampleFormControlInput1">Heading <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="exampleFormControlInput1"
                                        placeholder="Enter Heading" maxlength="250" name="heading"
                                        value="{{old('heading', @$data->title)}}">
                                    @error('heading')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>

                                <div class="col-md-6">

                                    <label for="exampleFormControlInput1">Sub Heading <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="exampleFormControlInput1"
                                        placeholder="Enter Sub Heading" maxlength="250" name="sub_heading"
                                        value="{{old('sub_heading', @$data->content)}}">
                                    @error('sub_heading')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>
                                <input type="hidden" name="id" value="{{@$data->id}}">
                            </div>

                            <button class="searc-btn mt-3">Update </button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
        <!-- container -->
    </div>
    @endsection
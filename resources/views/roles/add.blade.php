@extends('admin.app.app')
@section('title', config('app.name') . ' | '.$sub_title)
@section('content')

<style>
    [type="checkbox"]:not(:checked), [type="checkbox"]:checked {
        position: absolute;
        opacity: 1;
        pointer-events: unset;
    }
</style>
<div class="page-wrapper">
    @include('admin.app.breadcrumb',['title'=>$title,'sub_title' => $sub_title])   

    <div class="container-fluid">
        <div class="row">            
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col l6">
                            </div>
                            
                            <div class="col s12">
                                @if (Session::has('message'))
                                    <div class="alert-message-my">
                                        <h4 class="flash ml-3 mt-3"> {{ session('message') }} </h4>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <form action="{{ route('module.store') }}" method="post" enctype="multipart/form-data">
                            @method ('POST')
                            @csrf

                            <div class="tab-pane fade show active" id="tab_block_1">
                                <div class="row">
                                    <div class="col-md-12 mb-md-4 mb-3">
                                        <div class="container py-3">
                                            <div class="row">
                                                <div class="col-lg-6 mt-4">
                                                    <div class="pass1">
                                                        <div class="form-outline category_name">
                                                            <label class="form-label">Module Name <span class="required">*</span></label>
                                                            <input type="text" value="{{ old('module_name') }}" maxlength="250" class="form-control oye-f2 module_name" name="module_name" id="module_name">
                                                        </div>
                                                        @if($errors->has('module_name') )
                                                            <span class="invalid-feedback text-danger">
                                                                {{ $errors->first('module_name') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-12 text-end">
                                                <button type="submit" class="btn btn-primary quck-save-btn " id="validate_banner">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
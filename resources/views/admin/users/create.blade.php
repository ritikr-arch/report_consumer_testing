@extends('admin.layouts.app')
@section('title', config('app.name') . ' | '.$sub_title)
@section('content')

<div class="page-wrapper">
    @include('admin.app.breadcrumb',['title'=>$title,'sub_title' => $sub_title])   

    <div class="container-fluid">
        <div class="row">
            
            <div class="col s12">
                <div class="card single-user-info">
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

                        <div class="row">
                            <div class="d-flex justify-content-start l6">
                                <a href="{{route('admin.staff.index')}}" class="waves-effect red btn-round back-btn waves-light btn modal-trigger"> <i class="fa fa-arrow-circle-left"></i> back</a>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col s6">
                                <form action="{{route('admin.staff.store')}}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col s12 mb-3">
                                            <label>First Name<span class="mandatory">*</span></label>
                                            <input type="text" class="form-control" placeholder="First Name" name="fname" value="{{@old('fname')}}">
                                            @if($errors->has('fname') )
                                                <span class="invalid-feedback text-danger">
                                                    {{ $errors->first('fname') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col s12 mb-3">
                                            <label>Last Name<span class="mandatory">*</span></label>
                                            <input type="text" class="form-control" placeholder="Last Name" name="lname" value="{{@old('lname')}}">
                                            @if($errors->has('lname') )
                                                <span class="invalid-feedback text-danger">
                                                    {{ $errors->first('lname') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col s12 mb-3">
                                            <label>Email<span class="mandatory">*</span></label>
                                            <input type="text" class="form-control" placeholder="Email" name="email" value="{{@old('email')}}">
                                            @if($errors->has('email') )
                                                <span class="invalid-feedback text-danger">
                                                    {{ $errors->first('email') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col s12 mb-3">
                                            <label>Mobile<span class="mandatory">*</span></label>
                                            <input type="text" class="form-control" placeholder="Mobile" name="mobile" value="{{@old('mobile')}}">
                                            @if($errors->has('mobile') )
                                                <span class="invalid-feedback text-danger">
                                                    {{ $errors->first('mobile') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col s12 mb-3">
                                            <label>Password<span class="mandatory">*</span></label>
                                            <input type="password" class="form-control" placeholder="Password" name="password" value="{{@old('password')}}">
                                            @if($errors->has('password') )
                                                <span class="invalid-feedback text-danger">
                                                    {{ $errors->first('password') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col s12 mb-3">
                                            <label>User Location<span class="mandatory">*</span></label>
                                            {!!CountrySelectBox('country_id','country_id')!!}
                                            @if($errors->has('country_id') )
                                                <span class="invalid-feedback text-danger">
                                                    {{ $errors->first('country_id') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col s12 mb-3">
                                            <label>Role<span class="mandatory">*</span></label>
                                            <select class="form-control" name="role">
                                                <option value=''>Select Role</option>
                                                @foreach($roles as $role)
                                                    <option value='{{$role->id}}' <?php if(@old('role') == $role->id)echo"selected"; ?>>{{$role->name}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('role') )
                                                <span class="invalid-feedback text-danger">
                                                    {{ $errors->first('role') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col s12 mb-3 text-center">
                                            <button class="btn btn-gradient-primary" type="submit">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col s12">
                                <br>
                                <br>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




@endsection
@extends('admin.layouts.app')
@section('title', config('app.name') . ' | '.$sub_title)
@section('content')

<div class="page-wrapper"> 

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
                        @if($user)
                        <div class="row mt-3">
                            <div class="col s6">
                                <form action="{{route('admin.staff.update',['id'=> $user->id])}}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$user->id}}">
                                    <div class="row">
                                        <div class="col s12 mb-3">
                                            <label>First Name<span class="mandatory">*</span></label>
                                            <input type="text" class="form-control" placeholder="Name" value="{{$user->name}}" name="name">
                                            @if($errors->has('name') )
                                                <span class="invalid-feedback text-danger">
                                                    {{ $errors->first('name') }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="col s12 mb-3">
                                            <label>Email<span class="mandatory">*</span></label>
                                            <input type="text" class="form-control" placeholder="Email" value="{{$user->email}}" name="email" readonly>
                                            @if($errors->has('email') )
                                                <span class="invalid-feedback text-danger">
                                                    {{ $errors->first('email') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col s12 mb-3">
                                            <label>Mobile<span class="mandatory">*</span></label>
                                            <input type="text" class="form-control" placeholder="Mobile" value="{{$user->mobile}}" name="mobile">
                                            @if($errors->has('mobile') )
                                                <span class="invalid-feedback text-danger">
                                                    {{ $errors->first('mobile') }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="col s12 mb-3">
                                            <label>Role<span class="mandatory">*</span></label>
                                            <select class="form-control" name="role">
                                                <option value=''>Select Role</option>
                                                @foreach($roles as $role)
                                                    <option value='{{$role->id}}' @if($role->id == $user->role) {{'selected'}} @endif>{{$role->name}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('role') )
                                                <span class="invalid-feedback text-danger">
                                                    {{ $errors->first('role') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col s12 mb-3 text-center">
                                            <button class="btn btn-gradient-primary" type="submit">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col s12">
                                <br>
                                <br>
                            </div>
                        </div>
                        @endif



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




@endsection
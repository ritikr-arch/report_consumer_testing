@extends('admin.app.app')
@section('title', config('app.name') . ' | '.$sub_title)
@section('content')

<style>
    .data-delete-btn{
        background:transparent;
        color:#ffffff;
        border:0px;
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
                                        <div class="row">
                                                <?php //echo "<pre>";print_r($permission);die;?>
                                                <div class="col-lg-12 text-end mb-3" style="float:right;">
                                                        <a href="#" class="waves-effect red btn-round waves-light btn modal-trigger" data-target="createModule">Add New</a>
                                                </div>
                                                <div class="col-lg-12">
                                                        <table id="tb" class="table nowrap w-100 mb-5">
                                                                <thead>
                                                                        <tr>
                                                                                <th>S.No.</th>
                                                                                <th>Name</th>
                                                                                <th>Action</th>
                                                                        </tr>
                                                                </thead>
                                                                <tbody>
                                                                @php 
                                                                        $itr = 1;
                                                                @endphp
                                                                @if($permission && count($permission) > 0)
                                                                        @foreach($permission as $key => $row)
                                                                        <tr>
                                                                                @if ($key % 5 == 0)
                                                                                <td>{{$itr}}. </td>                                                                                
                                                                                        <?php 
                                                                                                $labelName = explode('_',$row->name); 
                                                                                        ?>
                                                                                <td>{{$labelName ? ucwords($labelName[0]) : $row->name}}</td>
                                                                               
                                                                                        <td>
                                                                                        <a href="#" data-url="{{ route('module.edit',$row->id) }}" data-id="{{$row->id}}" data-heading="{{$row->name}}" class="edit-module btn btn-primary"> <i class="fa fa-edit"></i></a>
                                                                                        <form method="post" class="btn store-category-delet-form display-contente" action="{{route('module.delete',['id'=> $row->id])}}">
                                                                                                @csrf
                                                                                                <input type="hidden" name="id" value="{{$row->id}}">
                                                                                                <input type="hidden" name="module_name" value="{{$labelName[0]}}">
                                                                                                <button type="submit" class="border-0 data-delete-btn p-0" name="submit"><i class="fa fa-trash"></i></button>
                                                                                        </form>
                                                                                </td>
                                                                                @php
                                                                                        $itr++;
                                                                                @endphp
                                                                                @endif
                                                                        </tr>                                                                       
                                                                        @endforeach
                                                                @else
                                                                        <tr>
                                                                                <td colspan="3" class="text-center">No records found</td>
                                                                        </tr>
                                                                @endif
                                                                </tbody>
                                                        </table>
                                                        @if($permission && count($permission) > 0)
                                                                <?=customePagination($permission)?>
                                                        @endif
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>
</div>
<div class="modal fade" id="createModule" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">New Module </h5>
                                <button type="button" class="close-modal" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                                <form class="create-module-form" action="{{ route('module.store') }}" method="post" enctype="multipart/form-data">
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
                                                                                        <button type="submit" class="btn btn-primary quck-save-btn " id="validate_module">Save</button>
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

<div class="modal fade" id="updateModule" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Update Module (<span class="businessName-lable"></span>) </h5>
                                <button type="button" class="close-modal" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                                <div class="insertForm"></div>
                        </div>
                </div>
        </div>
</div>
@endsection
@extends('admin.layouts.app')
@section('title', config('app.name') . ' | '.$sub_title)
@section('content')

<style>
    [type="checkbox"]:not(:checked), [type="checkbox"]:checked {
        position: absolute;
        opacity: 1;
        pointer-events: unset;
    }
      .permission-checkbox{
            margin: 5px 6px !important;
    }
</style>
<div class="page-wrapper">
   
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
                        <div class="tab-pane fade show active" id="tab_block_1">
                            <div class="row">
                                <div class="col-md-12 mb-md-4 mb-3">
                                    <input type="hidden" name="id" value="{{$role->id}}">
                                    <div class="container py-3">
                                        <div class="row">
                                            <div class="form-group">
                                                <strong class="mb-3">Role Name:</strong>
                                                <input type="text" name="name" class="form-control name" id="name"
                                                    value="{{$role->name}}" readonly>
                                            </div>

                                        </div>
                                        <div class="row mt-2 ">
                                            <strong>Permission:</strong>
                                                
                                            <div class="col-lg-12">
                                                <table id="tb" class="table nowrap w-100 mb-5">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 500px;">Menu</th>
                                                            <th class="text-center"> List </th>
                                                            <th class="text-center"> Add</th>
                                                            <th class="text-center"> Edit </th>
                                                            <th class="text-center"> Delete</th>
                                                            <th class="text-center"> View </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(!empty($permission))
                                                        @foreach($permission as $key => $row)
                                                            <tr>
                                                                @if ($key % 5 == 0)
                                                                    <?php $labelName = explode('_',$row->name); 
                                                                    
                                                                        if($labelName[0]=='List'){
                                                                            $labelName[0] = 'Roles';
                                                                        }
                                                                    ?>
                                                                    {{-- <td>{{$labelName ? ucwords($labelName[0]) : $row->name}}</td> --}}
                                                                    <td>{{
                                                                            $labelName
                                                                                ? (
                                                                                    $labelName[0] == 'our'
                                                                                        ? ucwords($labelName[1] ?? '')
                                                                                        : (
                                                                                            in_array($labelName[0], ['consumer', 'complaint','submit','contact','about','privacy','terms','public','tips','quick','useful'])
                                                                                                ? ucwords($labelName[0] ?? '') . ' ' . ucwords($labelName[1] ?? '')
                                                                                                : ucwords($labelName[0] ?? '')
                                                                                        )
                                                                                )
                                                                                : $row->name
                                                                        }}</td>
                                                                @for($i = $row->id; $i < $row->id+5; $i++)
                                                                    <td class="text-center"> <input type="checkbox" id="permission[]"
                                                                        name="permission[]" value="{{$i}}" <?= in_array($i, $rolePermissions) ? "checked" :''; ?> disabled></td>
                                                                        @endfor
                                                                    
                                                                    @else
                                                                    </tr>
                                                                @endif 
                                                                
                                                        @endforeach
                                                    @endif    
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
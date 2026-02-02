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
            margin: 4px 6px !important;
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
                        <form action="{{ route('admin.role.update',$role->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-pane fade show active" id="tab_block_1">
                                <div class="row">
                                    <div class="col-md-12 mb-md-4 mb-3">
                                        <input type="hidden" name="id" value="{{$role->id}}">
                                        <div class="container py-3">
                                            <div class="row">
                                                <div class="form-group">
                                                    <strong class="mb-3">Role Name:</strong>
                                                    <input type="text" name="name" class="form-control name" id="name"
                                                        value="{{$role->name}}">
                                                    @if($errors->has('name') )
                                                        <span class="invalid-feedback text-danger">
                                                            {{ $errors->first('name') }}
                                                        </span>
                                                    @endif
                                                    <!-- <span id="name_error">Name is required.</span> -->
                                                </div>

                                            </div>
                                            <div class="row mt-2 ">
                                                <strong>Permission:</strong>
                                                    
                                                <div class="col-lg-12">
                                                    @if($errors->has('permission') )
                                                        <span class="invalid-feedback text-danger">
                                                            {{ $errors->first('permission') }}
                                                        </span>
                                                    @endif
                                                    <table id="tb" class="table nowrap w-100 mb-5">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 500px;">Menu</th>
                                                                <th class="text-center"> List <input type="checkbox" class="list-permission permission-checkbox"></th>
                                                                <th class="text-center"> Add <input type="checkbox" class="add-permission permission-checkbox"></th>
                                                                <th class="text-center"> Edit <input type="checkbox" class="edit-permission permission-checkbox"></th>
                                                                <th class="text-center"> Delete <input type="checkbox" class="delete-permission permission-checkbox"></th>
                                                                <th class="text-center"> View <input type="checkbox" class="view-permission permission-checkbox"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($permission as $key => $row)
                                                                <tr>
                                                                    @if ($key % 5 == 0)
                                                                    <?php $labelName = explode('_',$row->name); 
                                                                    
                                                                        if($labelName[0]=='List'){
                                                                            $labelName[0] = 'Roles';
                                                                        }
                                                                    ?>
                                                                        {{-- <td>{{$labelName ? ucwords($labelName[0]) : $row->name}}</td> --}}

                                                                        <td>
                                                                        {{
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
                                                                        }}
                                                                    </td>

                                                                    @for($i = $row->id; $i < $row->id+5; $i++)
                                                                        @if($i == $row->id)
                                                                            <td class="text-center"> <input type="checkbox" id="permission[]"
                                                                            name="permission[]" value="{{$i}}" <?= in_array($i, $rolePermissions) ? "checked" :''; ?> class="check-box-list"></td>
                                                                        @elseif($i == $row->id+1)
                                                                            <td class="text-center"> <input type="checkbox" id="permission[]"
                                                                            name="permission[]" value="{{$i}}" <?= in_array($i, $rolePermissions) ? "checked" :''; ?> class="check-box-add"></td>
                                                                        @elseif($i == $row->id+2)
                                                                            <td class="text-center"> <input type="checkbox" id="permission[]"
                                                                            name="permission[]" value="{{$i}}" <?= in_array($i, $rolePermissions) ? "checked" :''; ?> class="check-box-edit"></td>
                                                                        @elseif($i == $row->id+3)
                                                                            <td class="text-center"> <input type="checkbox" id="permission[]"
                                                                            name="permission[]" value="{{$i}}" <?= in_array($i, $rolePermissions) ? "checked" :''; ?> class="check-box-delete"></td>
                                                                        @elseif($i == $row->id+4)
                                                                            <td class="text-center"> <input type="checkbox" id="permission[]"
                                                                            name="permission[]" value="{{$i}}" <?= in_array($i, $rolePermissions) ? "checked" :''; ?> class="check-box-view"></td>
                                                                        @endif
                                                                    @endfor
                                                                    
                                                                    @else
                                                                    </tr>
                                                                @endif 
                                                                
                                                            @endforeach
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 text-end mt-5">
                                                <button type="submit" class="btn btn-primary role_validate">Update</button>
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

<script>

document.addEventListener("DOMContentLoaded", function () {
    // Function to handle checkbox toggle
    function toggleCheckboxes(mainCheckboxClass, checkboxClass) {
        let mainCheckbox = document.querySelector("." + mainCheckboxClass);
        let checkboxes = document.querySelectorAll("." + checkboxClass);

        if (mainCheckbox) {
            mainCheckbox.addEventListener("change", function () {
                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = mainCheckbox.checked;
                });
            });
        }
    }

    // Apply the function to each permission type
    toggleCheckboxes("list-permission", "check-box-list");
    toggleCheckboxes("add-permission", "check-box-add");
    toggleCheckboxes("edit-permission", "check-box-edit");
    toggleCheckboxes("delete-permission", "check-box-delete");
    toggleCheckboxes("view-permission", "check-box-view");
});

</script>

@endsection
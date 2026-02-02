@extends('admin.layouts.app')
@section('title', config('app.name') . ' | '.$sub_title)
@section('content')

<style>
    [type="checkbox"]:not(:checked), [type="checkbox"]:checked {
        position: absolute;
        opacity: 1;
        pointer-events: unset;
    }
    .role_validate{
        float:right;
    }

    [type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: inherit;
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

                        <form action="{{ route('admin.role.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-pane fade show active" id="tab_block_1">
                                <div class="row">
                                    <div class="col s12 mb-3">
                                            <div class="container py-3">
                                                <div class="row">
                                                <div class="col-md-6 ">
                                                    <div class="form-group">
                                                        {!! Form::text('name', null, array('placeholder' => 'Name *','class' => 'form-control name', 'id'=> 'name')) !!}
                                                        @if($errors->has('name') )
                                                            <span class="invalid-feedback text-danger">
                                                                {{ $errors->first('name') }}
                                                            </span>
                                                        @endif
                                                        <!-- <span id="name_error">Name is required.</span> -->
                                                    </div>
                                                </div>
                                                    
                                                </div>
                                                <div class="row mt-2 ">
                                                                  
                                                    <div class="col s12">
                                                        @if($errors->has('permission') )
                                                            <span class="invalid-feedback text-danger">
                                                                {{ $errors->first('permission') }}
                                                            </span>
                                                        @endif
                                                        <table id="tb" class="table nowrap w-100 mb-5">
                                                            <thead>
                                                                <tr>

                                                                    <th>Module</th>
                                                                    <th class="text-center"> 
                                                                        <div class="text-center">
                                                                            <label>List</label> 
                                                                            <input type="checkbox" class="list-permission permission-checkbox">
                                                                        </div>
                                                                        
                                                                    </th>
                                                                    <th class="text-center"> Add 
                                                                        <input type="checkbox" class="add-permission permission-checkbox">
                                                                </th>
                                                                    <th class="text-center"> Edit 
                                                                        <input type="checkbox" class="edit-permission permission-checkbox">
                                                                    </th>
                                                                    <th class="text-center"> Delete 
                                                                        <input type="checkbox" class="delete-permission permission-checkbox">
                                                                    </th>
                                                                    <th class="text-center"> View 
                                                                        <input type="checkbox" class="view-permission permission-checkbox">
                                                                    </th>

                                                                </tr>
                                                            </thead>
                                                          <!--   <tbody>
                                                            @php
                                                            $groupedPermissions = [];
                                                            $actionTypes = ['list', 'create', 'edit', 'delete', 'view'];

                                                            foreach ($permission as $perm) {
                                                                $parts = explode('_', strtolower($perm->name));
                                                                $last = end($parts);

                                                                // Skip if no valid action at the end
                                                                if (!in_array($last, $actionTypes)) {
                                                                    continue;
                                                                }

                                                                // Remove last part (action) to get the entity key
                                                                array_pop($parts);
                                                                $entityKey = implode('_', $parts);

                                                                $label = $labelMappings[$entityKey] ?? ucwords(str_replace('_', ' ', $entityKey));

                                                                // Normalize "create" as "add" in UI (optional)
                                                                $action = $last === 'create' ? 'add' : $last;

                                                                $groupedPermissions[$entityKey]['label'] = $label;
                                                                $groupedPermissions[$entityKey]['actions'][$action] = $perm->id;
                                                            }
                                                            @endphp
                                                            @foreach($groupedPermissions as $group)
                                                                <tr>
                                                                    <td>{{ $group['label'] }}</td>
                                                                    @foreach($actionTypes as $action)
                                                                        <td class="text-center">
                                                                            @if(isset($group['actions'][$action]))
                                                                                <input type="checkbox" name="permission[]" value="{{ $group['actions'][$action] }}" class="check-box-{{ $action }}" data-error=".errorTxt9">
                                                                           
                                                                            @endif
                                                                        </td>
                                                                    @endforeach
                                                                </tr>
                                                            @endforeach
                                                        </tbody> -->


                                                       <tbody>
                                                                @foreach($permission as $key => $row)


                                                                    <tr>
                                                                        @if ($key % 5 == 0)
                                                                        <?php $labelName = explode('_',$row->name); 
                                                                        
                                                                            if($labelName[0]=='List'){
                                                                                $labelName[0] = 'Roles List';
                                                                            }
                                                                        ?>
                                                                        <td>{{
                                                                            $labelName
                                                                                ? (
                                                                                    $labelName[0] == 'our'
                                                                                        ? ucwords($labelName[1] ?? '')
                                                                                        : (
                                                                                            $labelName[0] === 'consumer'
                                                                                                ? ucwords($labelName[0] ?? '') . ' ' . ucwords($labelName[1] ?? '')
                                                                                                : ucwords($labelName[0] ?? '')
                                                                                        )
                                                                                )
                                                                                : $row->name
                                                                        }}</td>

                                                                            {{-- <td>{{$labelName ? ucwords($labelName[0]) : $row->name}}</td> --}}
                                                                          @for($i = $row->id; $i < $row->id+5; $i++)
                                                                            <td class="text-center"> 
                                                                                <label>
                                                                                    @if($i == $row->id)
                                                                                    <input type="checkbox" id="permission[]" name="permission[]" data-error=".errorTxt9"  value="{{$i}}" class="check-box-list">
                                                                                    @elseif($i == $row->id+1)
                                                                                    <input type="checkbox" id="permission[]" name="permission[]" data-error=".errorTxt9"  value="{{$i}}" class="check-box-add">
                                                                                    @elseif($i == $row->id+2)
                                                                                    <input type="checkbox" d="permission[]" name="permission[]" data-error=".errorTxt9"  value="{{$i}}" class="check-box-edit">
                                                                                    @elseif($i == $row->id+3)
                                                                                    <input type="checkbox" id="permission[]" name="permission[]" data-error=".errorTxt9"  value="{{$i}}" class="check-box-delete">
                                                                                    @elseif($i == $row->id+4)
                                                                                    <input type="checkbox" id="permission[]" name="permission[]" data-error=".errorTxt9"  value="{{$i}}" class="check-box-view">
                                                                                    @endif
                                                                                </label>
                                                                                
                                                                            </td>
                                                                        @endfor
                                                                        
                                                                        @else
                                                                        </tr>
                                                                    @endif 
                                                                @endforeach
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div class="col s12 text-end mt-3">
                                                    <button type="submit" class="btn btn-primary role_validate">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- new section end -->
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

        mainCheckbox.addEventListener("change", function () {
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = mainCheckbox.checked;
            });
        });
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
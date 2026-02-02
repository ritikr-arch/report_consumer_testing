@extends('admin.layouts.app')
@section('title', config('app.name') . ' | '. $sub_title ?? '')
@section('content')


<div class="page-wrapper">

    <div class="container-fluid">
        <div class="row">
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col l6">
                                <!-- <h4 class="card-title">{{$title ??''}}</h4> -->
                            </div>
                            @can('staff_create')
                             <div class="col d-flex justify-content-end l6">
                                <a href="{{route('admin.staff.create')}}" class="waves-effect red btn-round waves-light btn modal-trigger">Add New</a>
                            </div>
                            @endcan
                            <div class="col s12">
                                @if (Session::has('message'))
                                    <div class="alert-message-my">
                                        <h4 class="flash ml-3 mt-3"> {{ session('message') }} </h4>
                                    </div>
                                @endif
                            </div>
                        </div>


                        <table id="demo-foo-addrow" class="table m-t-10 highlight contact-list main-dataTables" data-page-size="10">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody >
                        
                                @foreach ($users as $key => $row)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>
                                            {{($row->name)}}
                                        </td>
                                        <td>{{($row->email)}}</td>
                                        <td>
                                            @if ($row->roles->isNotEmpty())
                                               {{ $row->roles->pluck('name')->implode(', ') }}
                                           @else
                                               No Role Assigned
                                           @endif
                                        </td>
                                        <td>
                                            {{-- @can('staff_edit') --}}
                                            <div class="switch">
                                                <label>
                                                    <?php $ischecked = '';?>
                                                    @if($row->status == 1)
                                                        <?php $ischecked = 'checked';?>
                                                    @endif
                                                    <input type="checkbox" {{$ischecked }} data-user-id="{{$row->id}}" data-url="{{route('admin.staff.status.change')}}" class="userActive-class" value="1">
                                                    <span class="lever"></span>
                                                </label>
                                            </div>
                                            {{-- @else
                                                @if($row->status == 1) {{'Active'}}
                                                @elseif($row->status == 0) {{'Inactive'}}
                                                @endif
                                            @endcan --}}
                                            
                                        </td>
                                        </td>
                                        <td class="action-btn">
                                            {{-- @can('staff_edit') --}}
                                            <a href="{{route('admin.staff.edit',['id'=> $row->id])}}"  class=""><i class="fa fa-edit"></i></a>&nbsp; | &nbsp;
                                            {{-- @endcan
                                            @can('staff_delete') --}}
                                            <form method="post" class="store-category-delet-form display-contente" action="{{route('admin.staff.delete',['id' => $row->id])}}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$row->id}}">
                                                <button type="submit" class="border-0 p-0 data-delete-btn" name="submit"><i class="fa fa-trash"></i></button>
                                            </form>
                                            {{-- @endcan --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    // START FOR DELETE USER
    $('table').on('click', '.delete_user', function() {
            let id = $(this).data('id');
            let url = `{{ url('user/delete/${id}') }}`
            Swal.fire({
                title: 'Do you want to DELETE ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#24695c',
                cancelButtonColor: '#d22d3d',
                confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(response) {
                            toastr.success('User Deleted successfully')
                            setTimeout(() => {
                                location.reload()
                            }, 1000);
                        }
                    });
                }
            });
        });

        // END FOR DELETE USER

        // START FOR CHANGE USER STATUS 
        $('.change-status-user').click(function() {
            let id = $(this).data('id');
            let status = $(this).data('status');

            Swal.fire({
                title: 'Do you want to Change Status ?',
                showCancelButton: true,
                confirmButtonColor: '#24695c',
                cancelButtonColor: '#d22d3d',
                confirmButtonText: 'Yes, Change it !'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ url('/user/changestatus') }}",
                        data: {
                            _token:'{{ csrf_token() }}',
                            id,
                            status
                        },
                        success: function(response) {
                            toastr.success('Status changed successfully')
                            setTimeout(() => {
                                location.reload()
                            }, 1000);
                        }
                    });
                }
            });
        });
        // END FOR CHANGE USER STATUS 
</script>

@endsection
@if($permission)
<form class="update-module-form" action="{{ route('module.update', ['id' => $permission->id]) }}" method="post" enctype="multipart/form-data">
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
                                    <?php 
                                        $labelName = explode('_',$permission->name); 
                                    ?>
                                    <input type="text" value="{{ucwords($labelName[0])}}" maxlength="250" class="form-control oye-f2 module_name" name="module_name" id="module_name">
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
                        <button type="submit" class="btn btn-primary quck-save-btn " id="validate_banner">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endif
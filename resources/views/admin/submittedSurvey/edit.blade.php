@extends('admin.layouts.app')

@section('title', @$title)

@section('content')

<div class="px-3">

   <!-- Start Content-->

   <div class="container-fluid">
      <div class="row mt-3">
         <div class="col-xl-12">
            <div class="card">
               <div class="card-body">
                  <div class="row align-items-center d-flex mb-3">
                     <div class="col-xl-12">
                        <h4 class="header-title mb-0 font-weight-bold" style="font-size:24px;">
                          Edit Submitted Survey
                        </h4>
                        <form action="{{route('admin.update.submitted.survey')}}" method="post" enctype="multipart/form-data">
                           <div class="row mb-3">
                              @csrf
                              <input type="hidden" value="{{$data->id}}" name="id">
                              <input type="hidden" value="{{$data->survey_id}}" name="survey_id">
                              <div class="col-md-6">
                                 <label for="zone">Zone</label>
                                 <input type="text" class="form-control" name="zone" id="zone" placeholder="Zone" value="{{($data->zone)?$data->zone->name:''}}" readonly>
                              </div>
                              <div class="col-md-6">
                                 <label for="zone">Market</label>
                                 <input type="text" class="form-control" name="market" id="market" placeholder="Market" value="{{($data->market)?$data->market->name:''}}" readonly>
                              </div>
                           </div>

                           <div class="row mb-3">
                              <div class="col-md-6">
                                 <label for="zone">Category</label>
                                 <input type="text" class="form-control" name="category" id="category" placeholder="Category" value="{{($data->category)?$data->category->name:''}}" readonly>
                              </div>
                              <div class="col-md-6">
                                 <label for="commodity">Commodity</label>
                                 <input type="text" class="form-control" name="commodity" id="commodity" placeholder="Commodity" value="{{($data->commodity)?$data->commodity->name:''}}" readonly>
                              </div>
                           </div>

                           <div class="row mb-3">
                              <div class="col-md-6">
                                 <label for="brand">Brand</label>
                                 <input type="text" class="form-control" name="brand" id="brand" placeholder="Zone Name" value="{{($data->brand)?$data->brand->name:''}}" readonly>
                              </div>
                              <div class="col-md-6">
                                 <label for="unit">Unit</label>
                                 <input type="text" class="form-control" name="unit" id="unit" placeholder="Unit" value="{{($data->unit)?$data->unit->name:''}}" readonly>
                              </div>
                           </div>
                           <div class="row mb-3">
                              <div class="col-md-6">
                                 <label for="price">Price <span class="text-danger">*</span></label>
                                 <input type="text" class="form-control" name="price" id="price" placeholder="Price" value="{{number_format($data->amount, 2)}}" oninput="this.value = this.value.replace(/[^0-9.%]/g, '').replace(/(\..*)\./g, '$1').replace(/(%.?)*%/, '%');">
                                 @error('price')
                                 <small class="text-danger text-bold"> {{ $message }} </small>
                                 @enderror
                              </div>
                              <div class="col-md-6">
                                 <label for="availability">Availability</label>
                                 <select class="form-select" name="availability" id="availability" aria-label="Default select example">
                                       <option value="high" {{($data->availability == 'high')?'selected':''}} >High</option>
                                       <option value="low" {{($data->availability == 'low')?'selected':''}}>Low</option>
                                       <option value="moderate" {{($data->availability == 'moderate')?'selected':''}}>Moderate</option>
                                 </select>
                                 @error('availability')
                                 <small class="text-danger text-bold"> {{ $message }} </small>
                                 @enderror
                              </div>
                           </div>

                           <div class="row mb-3">
                              <div class="col-md-6">
                                 <label for="image">Commodity Image</label>
                                 <input type="file" class="form-control" name="image" id="image">
                                 @error('image')
                                 <small class="text-danger text-bold"> {{ $message }} </small>
                                 @enderror
                              </div>
                              <div class="col-md-6">
                                 <label for="expiry_date">Expiry Date</label>
                                 <input type="date" class="form-control" name="expiry_date" id="expiry_date" value="{{($data->commodity_expiry_date)?date('Y-m-d', strtotime($data->commodity_expiry_date)):''}}">
                                 @error('expiry_date')
                                 <small class="text-danger text-bold"> {{ $message }} </small>
                                 @enderror
                              </div>
                           </div>

                           <div class="row mb-3">
                              <div class="col-md-6">
                                 <label for="status">Status </label>
                                 <select class="form-control" name="status" id="status">
                                    <option {{($data->status == '1')?'selected':''}} value="1">Approve</option>
                                    <option {{($data->status == '0')?'selected':''}} value="0">Pending</option>
                                    option
                                 </select>
                              </div>
                              <div class="col-md-6">
                                 <label for="expiry_date">Is Approve</label>
                                 <select class="form-control" name="is_approve" id="is_approve">
                                    <option {{($data->publish == 1)?'selected':''}} value="1">Yes</option>
                                    <option {{($data->publish == 0)?'selected':''}} value="0">No</option>
                                 </select>
                              </div>
                           </div>

                           <div class="row mb-3">
                              @if($data->commodity_image)
                                 <div class="col-md-6">
                                    <img style="height: 80px;width: 120px;" src="{{asset('submittedSurveyImage/'.$data->commodity_image)}}" alt="commodity image">
                                 </div>
                              @endif
                              <div class="col-md-6">
                                 <button type="Submit" class="btn btn-success">Update</button>
                              </div>
                           </div>
                        </form>

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
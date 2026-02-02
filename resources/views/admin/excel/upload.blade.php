@extends('admin.layouts.app')

@section('content')
<style>
    

    .container {
        max-width: 600px;
        background: white;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        margin: auto;
    }

    h2 {
        text-align: center;
        color: #333;
    }

    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
    }

    input, select {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    input[readonly] {
        background-color: #e9ecef;
    }

    button {
        margin-top: 20px;
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        border: none;
        color: white;
        font-size: 16px;
        border-radius: 6px;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }

    .note {
        text-align: center;
        font-size: 12px;
        color: #999;
    }
</style>
<div class="container">
    <h2>Upload Excel File</h2>
    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.upload.excel') }}">
        @csrf
        <label>Survey Name</label>
        <select name="survey_name" id="survey_name" required>
            <option value="">-- Select Survey --</option>
            @foreach ($surveys as $survey)
                <option value="{{ $survey->name }}" data-id="{{ $survey->id }}" data-category="{{ $survey->zone_id }}"  data-status="{{ $survey->zone->name }}">{{ $survey->name }}</option>
            @endforeach
        </select>
        <input type="hidden" name="survey_id" id="survey_id" readonly required>
        <input type="hidden" name="zone_id" id="zone_id" readonly required>
        <input type="hidden" name="zone_name" id="zone_name" readonly required>

        <label>Select Excel File</label>
        <input type="file" name="input_file" accept=".xls,.xlsx" required>

        <button type="submit">Upload & Download Excel</button>
    </form>
</div>

@endsection

@push('scripts')
<script>
function updateId(select, targetId) {
    const selected = select.options[select.selectedIndex];
    document.getElementById(targetId).value = selected.getAttribute('data-id') || '';
}
$(document).ready(function(){
    $("#survey_name").on('change', function(e){
        let selectedOption = $(this).find('option:selected');
        let dataId = selectedOption.data('id');
        let categoryId = selectedOption.data('category');
        let zoneName = selectedOption.data('status');
        $("#survey_id").val(dataId);
        $("#zone_id").val(categoryId);
        $("#zone_name").val(zoneName);
    })
})
</script>
@endpush
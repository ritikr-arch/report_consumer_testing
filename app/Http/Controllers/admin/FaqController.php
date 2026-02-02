<?php

namespace App\Http\Controllers\admin;

use App\Models\FAQ;
use App\Models\FAQTYPE;
use Illuminate\Http\Request;
use App\Services\SlugService;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FaqController extends Controller
{

    public function index()
    {
        $title = 'Faq List';
        $faqtype = FAQTYPE::where('status', '1')->orderBy('type', 'asc')->get();
        $data = FAQ::orderby('id', 'desc')->paginate(10);
        return view('admin.faq.index', compact('title', 'data', 'faqtype'));
    }

    public function faqUniqueSlug(Request $request)
    {
        $slugService = new SlugService();
        $slug = $slugService->generateUniqueSlug(FAQ::class, $request->input('productName'), $request->input('id'));
        return ['error' => false, 'msg' => '', 'slug' => $slug];
    }

    public function save(Request $request)
    {

        $rules = [
            'title' => 'required|max:250',
            'type_id' => 'required|max:50',
            'description' => 'required',
            'status' => 'required|in:0,1',
        ];


        $messages = [
            'type_id.required' => 'The type field is required.',
            'title.required' => 'The question field is required.',
            'description.required' => 'The answer field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $faq = FAQ::find($request->id) ?? new FAQ();
        $faq->title = $request->title;
        $faq->type_id = $request->type_id;
        // $faq->slug = $request->slug;
        $faq->description = $request->description;
        $faq->status = $request->status;

        $faq->save();
        return response()->json([
            'success' => true, 'message' => $request->id ? 'FAQ updated successfully.' : 'FAQ added successfully.',

        ]);    
    }

    public function editFaq($id)
    {
        $data = FAQ::find($id);
        if ($data) {
            return response()->json(['success' => true, 'data' => $data]);
        } else {
            return response()->json(['success' => false, 'message' => 'No record found.']);
        }
    }

    public function updateStatus(Request $request)
    {
        $faq = FAQ::find($request->id);
        if ($faq) {
            $faq->status = $request->status;
            $faq->save();
            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        } else {

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    public function filter(Request $request)
    {
        $title = 'Faq List';
        $query = FAQ::query();
        if ($request->name) {
            $query->where('title', 'like', '%' . $request->name . '%');
        }
        if (($request->status === '0') || ($request->status === '1')) {
            $query->where('status', $request->status);
        }
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->start_date)));
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->end_date)));
        }
        $data = $query->orderby('id', 'desc')->paginate(10);
        return view('admin.faq.index', compact('title', 'data'));
    }

    public function view($id)
    {
        $title = 'Faq Details';
        $data = FAQ::find($id);
        if ($data) {
            return view('admin.faq.view', compact('data', 'title'));
        } else {
            return back()->with('error', 'Something went wrong.');
        }
    }

    public function delete($id)
    {
        $faq = FAQ::find($id);
        if ($faq) {
            $faq->delete();
            return response()->json(['success' => true, 'message' => 'Faq deleted successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Something went wrong. Please try later.']);
        }
    }



    public function type_index()
    {
        $title = 'Faq Type List';
        $data = FAQTYPE::orderby('id', 'desc')->paginate(10);

        return view('admin.faq.type.index', compact('title', 'data'));
    }

    public function type_save(Request $request)
    {
        $rules = [
            'name' => 'required|max:250',
            'status' => 'required|in:0,1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $faq = FAQTYPE::find($request->id) ?? new FAQTYPE();

        $faq->type = $request->name;
        $faq->status = $request->status;
        $faq->save();
        return response()->json([
            'success' => true, 'message' => $request->id ? 'Faq Type updated successfully.' : 'Faq Type added successfully.',

        ]);
    }



    public function type_updateStatus(Request $request)
    {
        $faq = FAQTYPE::find($request->id);
        if ($faq) {
            $faq->status = $request->status;
            $faq->save();
            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    public function type_delete($id)
    {
        $faq = FAQTYPE::find($id);
        if ($faq) {
            $faq->delete();
            return response()->json(['success' => true, 'message' => 'Faq Type deleted successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Something went wrong. Please try later.']);
        }
    }


    public function type_editFaq($id)
    {
        $data = FAQTYPE::find($id);
        if ($data) {
            return response()->json(['success' => true, 'data' => $data]);
        } else {
            return response()->json(['success' => false, 'message' => 'No record found.']);
        }
    }
}

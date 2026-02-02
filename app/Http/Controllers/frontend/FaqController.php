<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FAQ;

class FaqController extends Controller
{
    public function index()
    {
        $title = 'FAQs';
        $faq = FAQ::with('types')->where('status',1)->orderBy('id','desc')->get();
        return view('frontend.faq', compact('title','faq'));
    }

    public function searchFaq(Request $request){

        $query = $request->get('q');
        $faqs = Faq::where('title', 'LIKE', '%'.$query.'%')
               ->get();
         return response()->json($faqs);
    }
}
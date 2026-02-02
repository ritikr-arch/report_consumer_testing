<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CMS;
use App\Models\FAQ;

class AboutController extends Controller
{
    public function index()
    {
        $title = 'About Us';
        $about = CMS::where('type','ABOUT_US')->first();
        $mission = CMS::where('type','Our_Mission')->first();
        $vision = CMS::where('type','Our_Vision')->first();
        $aim = CMS::where('type','Our_Aim')->first();
        $faq = FAQ::orderBy('id','desc')->where('status','1')->get();

        return view('frontend.about', compact('title','mission','vision','aim','faq', 'about'));
    }
}

<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Banner;
use App\Models\CMS;
use App\Models\NewsAndUpdate;
use App\Models\ConsumerCorner;
use App\Models\QuickLinks;
use App\Models\Feedback;
use App\Models\Notices;
use App\Models\TipsAdvice;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnquiryThankYouMail;
use App\Mail\AdminEnquiryNotificationMail;


class HomeController extends Controller
{
    public function index()
    {
        $title = 'Home';

        $banner = Banner::where('status','1')->get();
        // $news = NewsAndUpdate::where(['type' => 'news','status' => '1'])->get();
        // $press = NewsAndUpdate::where(['type' => 'press','status' => '1'])->orderBy('id','desc')->paginate('4');
        $press = TipsAdvice::where('status', '1')
            ->orderBy('created_at', 'desc')->limit(4)->get();
        $news = Notices::where('status', '1')
            ->orderBy('created_at', 'desc')->limit(4)->get();
        $about = CMS::where('type','ABOUT_US')->first();

        $blog = NewsAndUpdate::where(['type' => 'blog','status' => '1'])->get();

        
       $announcement = ConsumerCorner::where('status', '1')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
        $quick_link = QuickLinks::where('status','1')->orderBy('id','desc')->get();

        $heading = CMS::where('type', 'Banner_Heading')->first();

        return view('frontend.home', compact('title','banner','news','about','blog','press','announcement', 'quick_link', 'heading'));
    }

    public function blog_detail($slug)
    {
        
        $blog = NewsAndUpdate::where('id',$slug)->first();
        $blogs = NewsAndUpdate::orderBy('id','desc')->where(['status'=> '1', 'type'=> 'blog']) ->paginate(5);
        if($blog){
          return view('frontend.blogdetail',compact('blog','blogs'));
        }else{
           return redirect('/');
        }
        
    }

    public function article_detail($slug)
    {
    
        $article = NewsAndUpdate::where('id',$slug)->first();
        $articles = NewsAndUpdate::orderBy('id','desc')->where([ 'status' => '1', 'type'=>'article'])->paginate(5);
        if($articles && $article){
          return view('frontend.links.publications.articledetail',compact('article','articles'));
        }else{
           return redirect('/articles');
        }
        
    }

    public function quick_link($slug){
        $quickLink = QuickLinks::where('id', $slug)->first();
        $quickLinks = QuickLinks::where('status', '1')
            ->whereNot('slug', $slug)
            ->orderBy('id', 'desc')
            ->paginate(5);   
        
        if($quickLinks && $quickLink){
          return view('frontend.links.publications.quickLinks', compact('quickLinks', 'quickLink'));
        }else{
           return redirect('/');
        }   
        
    }

    public function news_page(){

        $news = Notices::where('status', '1')->paginate(12);

        return view('frontend.links.publications.news', compact('news', 'news'));

    }
    
    public function news_deatils($slug){
        $newsdetails = Notices::where('status', '1')->where('id',$slug )->first();
        $news = Notices::orderBy('id','desc')->where('status', '1')->paginate(5);
        if($news && $newsdetails){
          return view('frontend.links.publications.newsDetails', compact('news', 'newsdetails'));
        }else{
           return redirect('/');
        } 
     

    }

    public function submit_feedback(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|max:150|email:rfc,dns',
            'comments' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $data=array();
        $data['name'] = $request->input('name');
        $data['email'] = $request->input('email');
        $data['comment'] = $request->input('comments');
        $data['rating'] = $request->input('rating');

        Feedback::create($data);
        Mail::to($data['email'])->send(new EnquiryThankYouMail($data['name'], 'feedback'));
        Mail::to(adminEmail())->send(new AdminEnquiryNotificationMail($data));
        return response()->json(['message' => 'Thank you! Your feedback has been submitted successfully.'], 200);
    }
}
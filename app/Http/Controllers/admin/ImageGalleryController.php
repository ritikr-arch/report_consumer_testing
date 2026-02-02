<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SlugService;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ImageTrait;
use Auth;

use App\Models\ImageGallery;
use App\Models\GalleryMultiImage;

class ImageGalleryController extends Controller
{

    use ImageTrait;

    public function index()
    {
        $title = 'Image Gallery';
        $data = ImageGallery::orderby('id', 'desc')->paginate(10);
        return view('admin.imageGallery.index', compact('title', 'data'));
    }
    // imageGallery


    // public function save(Request $request)
    // {
    //     $rules = [
    //         'status' => 'required|in:0,1',
    //     ];

    //     if ($request->id) {
    //         $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:5120';
    //     } else {
    //         $rules['image'] = 'required|array';
    //         $rules['image.*'] = 'image|mimes:jpeg,png,jpg|max:5120';
    //     }

    //     $validator = Validator::make($request->all(), $rules);
    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $path = 'admin/images/imageGallery/';

    //     if ($request->id) {
            
    //         $news = ImageGallery::find($request->id);
    //         if (!$news) {
    //             return response()->json(['errors' => ['id' => 'Record not found']], 404);
    //         }
    //         $news->status = $request->status;

    //         if ($request->hasFile('image')) {
    //             // Delete old image if exists
    //             if ($news->image) {
    //                 $oldImagePath = public_path($path . $news->image);
    //                 if (file_exists($oldImagePath)) {
    //                     unlink($oldImagePath);
    //                 }
    //             }

    //             $filename = $this->imageUpload($request->file('image'), $path);
    //             $news->image = $filename;
    //         }

    //         $news->save();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Image Gallery updated successfully.',
    //         ]);
    //     }

    //     foreach ($request->file('image') as $img) {
    //         $filename = $this->imageUpload($img, $path);
    //         $gallery = new ImageGallery();
    //         $gallery->status = $request->status;
    //         $gallery->image = $filename;
    //         $gallery->save();
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Image Gallery added successfully.',
    //     ]);
    // }


    // public function save(Request $request){
    //      $rules = [
    //          'status' => 'required|in:0,1',
    //      ];
      
    //      if (!$request->id) {
    //         $rules['image'] = 'required|array';
    //         $rules['image.*'] = 'image|mimes:jpeg,png,jpg|max:2048 ';

    //      }
    //       else {
    //         $rules['image'] = 'nullable|array';
    //          $rules['image.*'] = 'image|mimes:jpeg,png,jpg|max:2048';
    //      }

    //      $validator = Validator::make($request->all(), $rules );
    //      if ($validator->fails()) {
    //          return response()->json(['errors' => $validator->errors()], 422);
    //      }

    //      if (!$request->id) {
    //         if ($request->hasFile('image')) {
    //             $path = 'admin/images/imageGallery/';
    //             foreach ($request->file('image') as $img) {
    //                 $filename = $this->imageUpload($img, $path);
    //                 $gallery = new ImageGallery();
    //                 $gallery->status = $request->status;
    //                 $gallery->image = $filename;
    //                 $gallery->save();
    //             }
    //         }
    //     } else {
          
    //         $news = ImageGallery::find($request->id);
    //         $path = 'admin/images/imageGallery/';
        
    //         if ($request->hasFile('image')) {
    //             // Delete old image if it exists
    //             if ($news->image) {
    //                 $oldImagePath = public_path($path . $news->image);
    //                 if (file_exists($oldImagePath)) {
    //                     unlink($oldImagePath);
    //                 }
    //             }
        
    //             $img = $request->file('image')[0]; // Use first uploaded image
    //             $filename = $this->imageUpload($img, $path);
    //             $news->image = $filename;
    //         }
        
          
    //         $news->status = $request->status;
    //         $news->save();
    //     }
        

    
    //     return response()->json([ 'success' => true,'message' => $request->id ? 'Image Gallery updated successfully.' : 'Image Gallery added successfully.',

    //     ]);
    // }

 public function save(Request $request)
{
    $rules = [
        'status' => 'required|in:0,1',
    ];

    if (!$request->id) {
        // Create Mode
        $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:51200'; // MAX 50MB
        $rules['gallery_images.*'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048'; // ✅ MAX 2MB
    } else {
        // Edit Mode
        $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:51200';
        $rules['gallery_images.*'] = 'image|mimes:jpeg,png,jpg,gif|max:2048'; // ✅ MAX 2MB
    }


    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $path = 'admin/images/imageGallery/';

    if (!$request->id) {
        // Create New Record
        $data = [
            'title' => $request->image_title,
            'status' => $request->status,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $this->imageUpload($request->file('image'), $path);
        }

        $result = ImageGallery::create($data);

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $images) {
                $imagePath = $this->imageUpload($images, $path);
                GalleryMultiImage::create([
                    'image_gallery_id' => $result->id,
                    'name' => $imagePath,
                ]);
            }
        }

    } else {
        // Update Existing Record
        $news = ImageGallery::find($request->id);
        if (!$news) {
            return response()->json(['success' => false, 'message' => 'Image Gallery not found.'], 404);
        }

        if ($request->hasFile('image')) {
            if ($news->image) {
                $oldImagePath = public_path($path . $news->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $news->image = $this->imageUpload($request->file('image'), $path);
        }

        $news->title = $request->image_title;
        $news->status = $request->status;
        $news->save();

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $images) {
                $imagePath = $this->imageUpload($images, $path);
                GalleryMultiImage::create([
                    'image_gallery_id' => $news->id,
                    'name' => $imagePath,
                ]);
            }
        }
    }

    return response()->json([
        'success' => true,
        'message' => $request->id ? 'Image Gallery updated successfully.' : 'Image Gallery added successfully.'
    ]);
}


    public function uniqueSlug(Request $request)
    {
        $slugService = new SlugService();
        $slug = $slugService->generateUniqueSlug(ImageGallery::class, $request->input('productName'), $request->input('id'));
        return ['error' => false, 'msg' => '', 'slug' => $slug];
    }

    public function editImageGallery($id)
    {
        $data = ImageGallery::with('multiImages')->find($id);
        if ($data) {
            $data->image = url('admin/images/imageGallery/' . $data->image);
            if(count($data->multiImages)>0){
                foreach($data->multiImages as $key=> $multiImage){
                    $data->multiImages[$key]->name = url('admin/images/imageGallery/' . $multiImage->name);
                }
            }
            return response()->json(['success' => true, 'data' => $data]);
        } else {
            return response()->json(['success' => false, 'message' => 'No record found.']);
        }
    }

    public function updateStatus(Request $request)
    {
        $image = ImageGallery::find($request->id);
        if ($image) {
            $image->status = $request->status;
            $image->save();
            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        } else {

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    public function deleteMultiImage($id){
        $faq = GalleryMultiImage::find($id);
        if ($faq) {
            $faq->delete();
            return response()->json(['success' => true, 'message' => 'Image deleted successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Something went wrong. Please try later.']);
        } 
    }

    public function delete($id)
    {
        $faq = ImageGallery::find($id);
        if ($faq) {
            $faq->delete();
            return response()->json(['success' => true, 'message' => 'Image deleted successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Something went wrong. Please try later.']);
        }
    }

    public function filter(Request $request)
    {
        $title = 'Image Gallery';
        $query = ImageGallery::query();
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
        return view('admin.imageGallery.index', compact('title', 'data'));
    }
}

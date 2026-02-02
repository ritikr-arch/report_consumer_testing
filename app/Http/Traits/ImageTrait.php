<?php

namespace App\Http\Traits;

trait ImageTrait{
    public function imageUpload($image, $path){
        $namebydate = time();
        $imageName = $namebydate.'_'.rand().'.'.$image->getClientOriginalExtension();
        $image->move(public_path($path),$imageName);
        return $imageName;
    }

    public function deleteImage($path,$file){
        if(!empty($file)){
            unlink(public_path($path.$file));
        }

    }

}
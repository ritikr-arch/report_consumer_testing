<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class SlugService
{

    public function generateUniqueSlug($model, $name, $id = null){
        $slug = Str::of($name)->slug('-');
        $rndSlug = $slug;

        $res = $model::where('slug', $slug)
            ->when($id, function (Builder $query) use ($id) {
                $query->where('id', '!=', $id);
            })
            ->count();

        if($res > 0){
            $count = 1;
            while ($model::where('slug', $slug)->count() > 0){
                $slug = $rndSlug . '-' . $count;
                $count++;
            }
        }

        return $slug;
    }
}

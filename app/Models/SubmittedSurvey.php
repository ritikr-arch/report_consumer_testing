<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class SubmittedSurvey extends Model

{


    use HasFactory;

    protected $table = 'submitted_surveys';


    protected $fillable = ['user_id', 'zone_id', 'survey_id', 'market_id', 'category_id', 'commodity_id', 'unit_id', 'brand_id', 'amount', 'amount_1', 'availability', 'commodity_image', 'submitted_by', 'updated_by', 'status', 'publish', 'commodity_expiry_date', 'is_save', 'is_submit'];


    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function zone(){
        return $this->hasOne(Zone::class, 'id', 'zone_id');
    }

    public function survey(){
        return $this->hasOne(Survey::class, 'id', 'survey_id');
    }

    public function market(){
        return $this->hasOne(Market::class, 'id', 'market_id');
    }

    public function category(){
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function commodity(){
        return $this->hasOne(Commodity::class, 'id', 'commodity_id');
    }

    public function unit(){
        return $this->hasOne(UOM::class, 'id', 'unit_id');
    }

    public function brand(){
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }



    public function submitter(){

        return $this->hasOne(User::class, 'id', 'submitted_by');

    }



    public function updater(){

        return $this->hasOne(User::class, 'id', 'updated_by');

    }





}


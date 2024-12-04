<?php

namespace App;

use App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Mailing;

class Ratings extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['from','from_company','to','value','job_id','feedback_msg','status'];

    public static function newRating($rating){
        
        if(Self::checkRated($rating)):
            
            

            $newRating = Self::create([
                'from'=>$rating->from,
                'from_company'=>$rating->from_company,
                'to'=>$rating->to,
                'value'=>$rating->value,
                'job_id'=>$rating->job_id,
                'feedback_msg'=>$rating->feedback_msg,
                'status'=> 'default' ]);
            
            //rating notification

            Self::evaluateRating($rating->to);

            return $newRating;
        else:
            return false;
        endif;
    }
    public static function evaluateRating($user_id){
        $ratingInfo = Self::getUserRating($user_id);
        $intRating = intval($ratingInfo->value);
        $currentCat = App\User_meta::where('meta_key','profile_pj_category')->where('user_id',$user_id)->first();
        switch ($intRating) {
            case 0:
                $catID = 1;
            break;
            case 1:
                    $catID = 1;
                break;
            case 2:
                    $catID = 1;
                break;
            case 3:
                    $catID = 1;
                break;
            case 4:
                    if( $ratingInfo->number >= 3 && $ratingInfo->number < 6 ):
                        $catID = 2;
                    elseif( $ratingInfo->number >= 6 && $ratingInfo->number < 10 ):
                        $catID = 3;
                    elseif( $ratingInfo->number >= 10 ):
                        $catID = 4;
                    else:
                        $catID = 1;
                    endif;
                break;
            case 5:
                if( $ratingInfo->number >= 3 && $ratingInfo->number < 6 ):
                    $catID = 2;
                elseif( $ratingInfo->number >= 6 && $ratingInfo->number <= 10 ):
                    $catID = 3;
                elseif( $ratingInfo->number >= 10 ):
                    $catID = 4;
                else:
                    $catID = 1;
                endif;
                break;
            
            default:
                $catID = 1;
                break;
            }
        if($currentCat->meta_value != $catID):
            //category changed
            $category = App\User_meta::where('meta_key','profile_pj_category')->where('user_id',$user_id)->update(['meta_value'=>$catID]);

            //try {
                $mailing = new Mailing;
                $mailing->categoryChange($user_id,$catID);
            /* } catch (\Throwable $th) {
                error_log($th);
            } */
        endif;
        
    }
    public static function checkPremium($user_id){
        return true;
    }
    public static function checkRated($rating){
        $ratingCount = Self::where('from',$rating->from)
                            ->where('from_company',$rating->from_company)
                            ->where('to',$rating->to)
                            ->where('job_id',$rating->job_id)
                            ->count();
        if($ratingCount>0):
            return false;
        else:
            return true;
        endif;
    }
    public static function getRatings($userid){
        $ratings = Self::where('to',$userid)->get();

        return $ratings;
    }
    public static function getUserRating($userid){
        $ratings = Self::getRatings($userid);
        $sumRatings = 0;
        $rating = 0;
        $numRatings = count($ratings);
        if($numRatings>0):
            foreach($ratings as $rating):
                $ratingValues[] = $rating->value;
            endforeach;
            $sumRatings = array_sum($ratingValues);
            $rating = $sumRatings / $numRatings;
        endif;
        
        $ratingInfo['number'] = $numRatings;
        $ratingInfo['value'] = $rating;

        $ratingData = (object) $ratingInfo;

        return $ratingData;
    }
    
}

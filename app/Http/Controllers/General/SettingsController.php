<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Http\Resources\General\CategoryResource;

class SettingsController extends Controller
{

        //TODO:: Admin show category for users
        public function category($id){
            try{

                //* creating the category database entry
                $category = Category::query()->where('id',$id)->first();
                if(!$category){
                    return response()->json(['status'=>'failed','message'=>'Category not found'],404);
                }
                return response()->json(['status'=>'success','category'=>new CategoryResource($category)],200);

            }catch(\Exception $e){
                return response()->json(['status'=>'success','message'=>$e->getMessage()],500);
            }
        }

           //TODO:: Admin show all categories for users
        public function categories(){
            try{

                //* creating the category database entry
                $categories = Category::query()->get();
                if(!$categories){
                    return response()->json(['status'=>'failed','message'=>'Category not found'],404);
                }
                return response()->json(['status'=>'success','categories'=>CategoryResource::collection($categories)],200);

            }catch(\Exception $e){
                return response()->json(['status'=>'success','message'=>$e->getMessage()],500);
            }
        }
}

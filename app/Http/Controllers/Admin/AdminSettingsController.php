<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//* Models
use App\Models\Category;

//* Resources
use App\Http\Resources\General\CategoryResource;

//* Utilities
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class AdminSettingsController extends Controller
{

    public $admin;

    public function __construct(){
        $admin = auth()->guard('api')->user();
        $this->admin =  $admin;
        $is_admin = ($admin->role === "super_admin" || $admin->role === "admin") ? true :false;
        if(!$is_admin){
            abort(403, 'You must be an admin to access this page');
        }
    }


    //TODO:: Admin created category for users
    public function add_category(){
        try{
            $rules = [
                'category'      =>'string|required',
                'description'   =>'nullable|string',
            ];
            $validation=  Validator::make(request()->all(),$rules);

            if($validation->fails()){
                return response()->json(['status'=>'failed','message'=>$validation->errors()->first()],422);
            }

            //* creating the category database entry
            $category = Category::query()->create([
                'category'=>request()->category,
                'description'=>request()->description ?? null,
            ]);
            if(!$category){
                return response()->json(['status'=>'failed','message'=>'Sorry, a problem occurred during the creation of the category. Please try again later'],400);
            }
            return response()->json(['status'=>'success','message'=>'Great, New Category has been added', 'category'=>$category->category]);

        }catch(\Exception $e){
            return response()->json(['status'=>'success','message'=>$e->getMessage()],500);
        }
    }

        //TODO:: Admin updating category for users
        public function update_category($id){
            try{
                $rules = ['category'=>'string|required','description'=>'nullable|string'];
                $validation=  Validator::make(request()->all(),$rules);

                if($validation->fails()){
                    return response()->json(['status'=>'failed','message'=>$validation->errors()->first()],422);
                }

                //* creating the category database entry
                $category = Category::query()->where('id',$id)->first();
                if(!$category){
                    return response()->json(['status'=>'failed','message'=>'Category not found'],404);
                }
                $category_update = $category->update(['category'=>request()->category, 'description'=>request()->description ?? $category->description]);
                if(!$category_update){
                    return response()->json(['status'=>'failed','message'=>'Sorry, a problem occurred during the update of the category. Please try again later'],400);
                }
                return response()->json(['status'=>'success','message'=>'Great, You have successfully updated the category'],200);

            }catch(\Exception $e){
                return response()->json(['status'=>'success','message'=>$e->getMessage()],500);
            }
        }

        //TODO:: Admin remove category for users
        public function delete_category($id){
            try{

                //* creating the category database entry
                $category = Category::query()->where('id',$id)->first();
                if(!$category){
                    return response()->json(['status'=>'failed','message'=>'Category not found'],404);
                }
                $category->delete();
                return response()->json(['status'=>'success','message'=>'Great, Successfully removed this category'],200);

            }catch(\Exception $e){
                return response()->json(['status'=>'success','message'=>$e->getMessage()],500);
            }
        }

        //TODO:: Admin show category for users
        public function show_category($id){
            try{

                //* creating the category database entry
                $category = Category::query()->where('id',$id)->first();
                if(!$category){
                    return response()->json(['status'=>'failed','message'=>'Category not found'],404);
                }
                return response()->json(['status'=>'success','category'=> new CategoryResource($category)],200);

            }catch(\Exception $e){
                return response()->json(['status'=>'success','message'=>$e->getMessage()],500);
            }
        }

           //TODO:: Admin show all categories for users
        public function show_all_category(){
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

<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Category;
use App\CategoryImg;
use Session;
use Input;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languageSession = Session::get('lang', 'nl');

        $hoofdCategories = Category::where('subcategoryId', NULL)->orderBy('order', 'asc')->get();
        $subCategories = Category::where('subcategoryId', '!=', NULL)->orderBy('order', 'asc')->get();

        foreach ($hoofdCategories as $key => $hoofdCategory) {
            $subsTemp = array();
            foreach ($subCategories as $subCategory) {
                if ($hoofdCategory->id == $subCategory->subcategoryId) {
                    $subsTemp[] = $subCategory;
                }
            }
            $hoofdCategories[$key]->subCategories = $subsTemp;
            $subCategoriesTemp = $subsTemp;

            if(sizeof($hoofdCategories[$key]->subCategories) > 0){
                foreach($hoofdCategories[$key]->subCategories as $key2 => $subCategoryfromHoofd){
                    $subsTemp2 = array();
                    foreach ($subCategories as $subCategory) {
                        if ($subCategoryfromHoofd->id == $subCategory->subcategoryId) {
                            $subsTemp2[] = $subCategory;
                        }
                    }
                    $subCategoriesTemp[$key2]->subCategories = $subsTemp2;
                }
            }
            $hoofdCategories[$key]->subCategories = $subCategoriesTemp;
        }
        return view('admin.category.edit', ['categories' => $hoofdCategories, 'lang' => $languageSession]);
    }

    public function updateOrder(Request $request) {
        /*if(!is_array($request->input('hierarchy'))){
            ob_start();
            var_dump($request->input('hierarchy'));
            $r = ob_get_clean();
            return $r;
        }*/

        $html = "";

        $objects = json_decode($request->input('valuesdrag'), true);


        $counter = 0;
        foreach($objects as $item){

            $cat = Category::find($item['id']);
            $cat->order = $counter++;
            $cat->subcategoryId = null;
            $cat->save();

            if(array_key_exists("children", $item)){
                foreach($item['children'] as $child1){
                    $catsub = Category::find($child1['id']);
                    $catsub->order = $counter++;
                    $catsub->subcategoryId = $item['id'];
                    $catsub->save();

                    if(array_key_exists("children", $child1)){
                        foreach($child1['children'] as $child2){
                            $catsubsub = Category::find($child2['id']);
                            $catsubsub->order = $counter++;
                            $catsubsub->subcategoryId = $child1['id'];
                            $catsubsub->save();
                        }
                    }
                }
            }
        }

        return "1";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'		=> 'required'
        ]);

        Category::create([
            'name' 			=> $request->input('name'),
            'slug' 			=> str_slug($request->input('name')),
            'description' 	=> $request->input('description'),
            'status' 		=> $request->input('status')
        ]);

        return redirect()->route('admin.category.index')->with('success', 'Successfully added!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->input('id');

        $category = Category::where('id',$id)->first();

        $category->naam_nl = $request->input('naam_nl');
        $category->naam_en = $request->input('naam_en');
        $category->naam_fr = $request->input('naam_fr');
        $category->naam_de = $request->input('naam_de');

        $category->update();
        $imgs = json_decode($request->input('imgValues'));

        if(sizeof($imgs) > 0) {
            foreach ($imgs as $img) {
                $productImg = new CategoryImg;

                $productImg->naam = $img->name;
                $productImg->directory = $img->uuid;

                $productImg->groupHash = $img->proxyGroupId;

                $productImg->categoryId = $id;

                $productImg->save();
            }
        }
        return $id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::find($id)->delete();

        return redirect()->route('admin.category.index')->with('success', 'Successfully deleted!');
    }
}

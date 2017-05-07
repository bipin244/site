<?php

namespace App\Http\Controllers\Admin;
use App\Category;
use App\PageImg;
use App\PageText;
use App\Color;
use App\Coating;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Page;
use Validation;
use Auth;
use Session;

class PageController extends Controller
{
    public function index()
    {
        return view('admin.page.list', ['pages' => Page::paginate(10) ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.page.create');
    }

    public function createproduct()
    {
        return view('admin.image.create');
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
        	'title'		=> 'required'
        ]);

        $user = Auth::user();

        $data = [
        	'user_id' 		=> $user->id,
        	'title' 		=> $request->input('title'),
        	'slug'  		=> str_slug($request->input('title')),
        	'content' 		=> $request->input('content'),
        	'status' 		=> $request->input('status')
        ];

        Page::create($data);

        return redirect()->route('admin.page.index')->with('success', 'Successfully created!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Session::put('pageId', $id);
        $languageSession = Session::get('lang', 'nl');
        return view('admin.page.edit', ['posts' => PageText::where('page_id', $id)->where('language', $languageSession)->get(),'pageId' => $id]);
    }

    public function colors()
    {
        $colors = Color::all();
        $languageSession = Session::get('lang', 'nl');
        return view('admin.color.edit', ['colors' => $colors, 'lang' => $languageSession]);
    }

    public function category(){
        $languageSession = Session::get('lang', 'nl');
        $alleCategories = Category::all();
        $hoofdCategories = Category::where('subcategoryId', NULL)->get();

        foreach($alleCategories as $key => $category){
            if($category->subcategoryId != NULL){
                foreach($hoofdCategories as $hoofdCategory){
                    if($category->subcategoryId == $hoofdCategory->id){
                        $alleCategories[$key]->hoofdCategory = $hoofdCategory;
                    }
                }
            }else{
                $alleCategories[$key]->hoofdCategory = null;
            }
        }



        return view('admin.category.edit', ['categories' => $alleCategories, 'lang' => $languageSession, 'hoofdcategories' => $hoofdCategories]);
    }

    public function coatings(){
        $coatings = Coating::all();
        $languageSession = Session::get('lang', 'nl');
        return view('admin.coating.edit', ['coatings' => $coatings, 'lang' => $languageSession]);
    }

    public function editLang($lang){
        Session::put('lang',$lang);
        $pageId = Session::get('pageId');
        return view('admin.page.edit', ['posts' => PageText::where('page_id', $pageId)->where('language', $lang)->get()]);
    }

    public function editimg($id)
    {
        $languageSession = Session::get('lang', 'nl');
        return view('admin.page.editimg', ['posts' => PageImg::where('page_id', $id)->get()]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
        	'title'		=> 'required'
        ]);

        $user = Auth::user();

        $data = [
        	'title' 		=> $request->input('title'),
        	'slug'  		=> str_slug($request->input('title')),
        	'content' 		=> $request->input('content'),
        	'status' 		=> $request->input('status')
        ];

        Page::find($id)->update($data);

        return redirect()->route('admin.page.index')->with('success', 'Successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Page::find($id)->delete();

        return redirect()->route('admin.page.index')->with('success', 'Successfully deleted!');
    }
}

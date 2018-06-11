<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\GoodsCategoryService;
use Auth;

class GoodsCategoryController extends Controller
{
    protected $goodsCategory;
    protected $pageSize = 50;

    public function __construct(GoodsCategoryService $goodsCategory)
    {
      $this->middleware('auth');
      $this->goodsCategory = $goodsCategory;
    }

    public function index()
    {
      $this->isAdmin();
      $title = '商品分类列表';
      $goodsCategories = $this->goodsCategory->get($this->pageSize);

      return view('admin.goodsCategory.index', compact('title', 'goodsCategories'));
    }

    public function show($id)
    {
      $this->isAdmin();
      $title = '分类信息详情';
      $goodsCategory = $this->goodsCategory->find($id);

      return view('admin.goodsCategory.show', compact('goodsCategory', 'title'));
    }

    public function create()
    {
      $this->isAdmin();

      $title = '增加栏目分类';
      $options = $this->goodsCategory->optionsForSelect();

      return view('admin.goodsCategory.create', compact('title', 'options'));
    }

    public function store(Request $request)
    {
      $this->validate($request, [
        'name' => 'required|min:1|max:30',
        'parent_id' => 'required|min:1',
        'order' => 'required|min:0|max:99',
        'is_shown' => 'required|boolean',
        'is_recommended' => 'required|boolean',
        'font_icon' => 'nullable|min:6|max:250',
        'image' => 'required|image',
      ]);

      $this->isAdmin();

      if ($this->goodsCategory->store($request)) {
        return back()->with('success', '成功增加栏目分类！');
      } else {
        return back()->with('warning', '增加栏目分类失败！');
      }
    }

    public function edit()
    {
      $this->isAdmin();
    }

    public function update()
    {
      $this->isAdmin();
    }

    public function destroy()
    {
      $this->isAdmin();
    }

    // 判断是否是管理员
    public function isAdmin()
    {
      $this->authorize('isAdmin', Auth::user());
    }
}

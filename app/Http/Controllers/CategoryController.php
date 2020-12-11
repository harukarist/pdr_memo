<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategory;
use App\Http\Requests\EditCategory;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // カテゴリー作成画面
    public function showCreateForm()
    {
        $categories = Auth::user()->categories()->get();
        if (!isset($categories)) {
            CategoryController::createUsersCategory('Input');
            CategoryController::createUsersCategory('Output');
            CategoryController::createUsersCategory('Etc');
            $categories = Auth::user()->categories()->get();
        }

        return view('categories.create', compact('categories'));
    }

    // カテゴリーの作成
    public function create(CreateCategory $request)
    {
        // Categoryモデルのインスタンスを作成する
        $category = new Category();
        // ログインユーザーに紐づけて保存
        // $category->category_name = $request->category_name;
        // Auth::user()->categories()->save();
        Auth::user()->categories()->save($category->fill($request->all()));

        // そのカテゴリーのタスク一覧画面にリダイレクト
        return redirect()->route('categories.create')->with('flash_message', 'カテゴリーを作成しました');
    }

    // カテゴリー編集画面を表示
    public function showEditForm(int $category_id)
    {
        // 該当のカテゴリーIDのデータを取得し、ビューテンプレートに返却
        $edit_category = Auth::user()->categories()->find($category_id);
        $categories = Auth::user()->categories()->get();

        if ($edit_category) {
            return view('categories.edit', compact('edit_category', 'categories'));
        } else {
            return redirect()->route('home');
        }
    }

    // カテゴリー編集処理
    public function edit(int $category_id, EditCategory $request)
    {
        // リクエストのIDからカテゴリーデータを取得
        $category = Auth::user()->categories()->find($category_id);

        // 該当のカテゴリーデータをフォームの入力値で書き換えて保存
        Auth::user()->categories()->save($category->fill($request->all()));

        // 編集対象のカテゴリーが属するカテゴリーのカテゴリー一覧にリダイレクト
        return redirect()->route('categories.create')->with('flash_message', 'カテゴリーを変更しました');
    }

    // カテゴリー削除処理
    public function delete(int $category_id)
    {
        // リクエストで受け取ったIDのカテゴリーを削除
        // Category::find($category_id)->delete();
        Category::destroy($category_id);

        // 削除対象のカテゴリーが属するカテゴリーのカテゴリー一覧にリダイレクト
        return redirect()->route('categories.create')->with('flash_message', 'カテゴリーを削除しました');
    }

    // カテゴリー作成
    public static function createUsersCategory(string $category_name)
    {
        $category = new Category();
        $category->category_name = $category_name;
        // ログインユーザーに紐づけて保存

        Auth::user()->categories()->save($category);
        // Auth::user()->categories()->save();
    }
}

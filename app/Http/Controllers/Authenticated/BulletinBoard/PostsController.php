<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use Auth;

class PostsController extends Controller
{
    public function show(Request $request)
    {
        $posts = Post::with('user', 'postComments');
        $categories = MainCategory::with('subCategories')->get(); // サブカテゴリーも取得
        $like = new Like;
        $post_comment = new Post;

        //  ① 検索欄にキーワードを入力
        if (!empty($request->keyword)) {
            $posts = $posts->where(function ($query) use ($request) {
                $query->where('post_title', 'like', '%' . $request->keyword . '%')
                    ->orWhere('post', 'like', '%' . $request->keyword . '%')
                    ->orWhereHas('subCategories', function ($subQuery) use ($request) {
                        $subQuery->where('sub_category', $request->keyword);
                    });
            });
        }
        // } else if ($request->category_word) {
        //     $sub_category = $request->category_word;
        //     $posts = Post::with('user', 'postComments')->get();

        // ④ サブカテゴリーをクリック
        if (!empty($request->sub_category_id)) {
            // 追加したサブカテゴリー検索処理（変更最小限）
            $posts = $posts->whereHas('subCategories', function ($subQuery) use ($request) {
                $subQuery->where('sub_categories.id', $request->sub_category_id);
            });
        }
        // ② いいねした投稿をクリック
        if ($request->like_posts) {
            $likes = Auth::user()->likePostId()->pluck('like_post_id'); // 修正get→pluck
            $posts = $posts->whereIn('posts.id', $likes);
            // ここで `get()` を実行

            // ③ 自分の投稿をクリック
        } else if ($request->my_posts) {
            $posts = $posts->where('posts.user_id', Auth::id());
        }
        // 最終的な検索結果を取得
        $posts = $posts->get(); // ここで確実に `$posts` はクエリビルダーの状態
        // いいねの数をビューに渡す処理
        $like = new Like;
        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment'));
    }

    public function postDetail($post_id)
    {
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput()
    {
        $mainCategories = MainCategory::get();
        return view('authenticated.bulletinboard.post_create', compact('mainCategories'));
    }

    public function postCreate(PostFormRequest $request)
    {
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body
        ]);
        // 選択されたサブカテゴリを取得
        // attach() で post_sub_categories に post_id と sub_category_id を登録する
        if ($request->has('sub_category_id')) {
            $post->subCategories()->attach($request->sub_category_id); // 中間テーブルに保存
        }
        return redirect()->route('post.show');
    }
    // 投稿編集機能
    public function postEdit(PostFormRequest $request)
    {
        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id)
    {
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }
    public function mainCategoryCreate(Request $request)
    {
        // カスタムメッセージの定義
        $customMessages = [
            'main_category_name.required' => 'メインカテゴリー名は必須項目です。',
            'main_category_name.unique' => 'このメインカテゴリー名は既に登録されています。',
            'main_category_name.max' => 'メインカテゴリー名は最大100文字まで入力できます。',
        ];
        // バリデーション
        $request->validate([
            'main_category_name' => 'required|string|max:100|unique:main_categories,main_category'
        ], $customMessages);

        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }

    public function subCategoryCreate(Request $request)
    {
        // カスタムメッセージの定義
        $customMessages = [
            'main_category_id.required' => 'メインカテゴリーを選択してください。',
            'main_category_id.exists' => '選択されたメインカテゴリーは存在しません。',
            'sub_category_name.required' => 'サブカテゴリー名は必須項目です。',
            'sub_category_name.string' => 'サブカテゴリー名は文字列で入力してください。',
            'sub_category_name.max' => 'サブカテゴリー名は最大100文字まで入力できます。',
            'sub_category_name.unique' => 'このサブカテゴリー名は既に登録されています。',
        ];

        $request->validate([
            'main_category_id' => 'required|exists:main_categories,id',
            'sub_category_name' => 'required|string|max:100|unique:sub_categories,sub_category'
        ], $customMessages);

        SubCategory::create([
            'main_category_id' => $request->main_category_id,
            'sub_category' => $request->sub_category_name
        ]);

        return redirect()->route('post.input');
    }
    // $mainCategories をビューに渡すアクションメソッド
    public function showForm()
    {
        // メインカテゴリーを取得
        $mainCategories = MainCategory::all();

        // ビューに渡す
        return view('your_view_name', compact('mainCategories'));
    }

    public function commentCreate(Request $request)
    { // バリデーションを適用
        $request->validate([
            'comment' => ['required', 'string', 'max:250'],
        ], [
            'comment.required' => 'コメントは必須です。',
            'comment.string'   => 'コメントは文字列で入力してください。',
            'comment.max'      => 'コメントは250文字以内で入力してください。',
        ]);

        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment // コメント用のエラーを'session'に保存
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]); // コメント用のエラーを'session'に保存;
    }

    public function myBulletinBoard()
    {
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard()
    {
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike($id)
    {
        Like::create([
            'like_user_id' => Auth::id(),
            'like_post_id' => $id
        ]);


        return redirect()->back();
    }

    public function postUnLike($id)
    {
        Like::where('like_user_id', Auth::id())->where('like_post_id', $id)->delete();

        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\MemoTag;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //メモデータを取得
        $memos = Memo::select('memos.*')
        ->where('user_id','=',\Auth::id())
        ->whereNull('deleted_at')
        ->orderBy('updated_at','DESC')
        ->get();

        return view('create',['memos' => $memos]);
    }

    public function store(Request $request){
        $posts = $request->all();

        /* ここからトランザクション開始 */
        DB::transaction(function() use($posts){
            $memo_id = Memo::insertGetId([
                'content' => $posts['content'],
                'user_id' => \Auth::id(),
            ]);
            $tag_exists = Tag::where('user_id','=',\Auth::id())->where('name','=',$posts['new_tag'])->exists();
            if(!empty($posts['new_tag']) && !$tag_exists){
                $tag_id = Tag::insertGetId([
                    'name' => $posts['new_tag'],
                    'user_id' => \Auth::id(),
                ]);
                MemoTag::insert([
                    'memo_id' => $memo_id,
                    'tag_id' => $tag_id,
                ]);
            }
        });
        /* ここまでがトランザクションの範囲 */

        

        return redirect()->route('home');
    }

    public function edit($id){
        $memos = Memo::select('memos.*')
        ->where('user_id','=',\Auth::id())
        ->whereNull('deleted_at')
        ->orderBy('updated_at','DESC')
        ->get();

        $edit_memo = Memo::find($id);

        return view('edit',[
            'memos' => $memos,
            'edit_memo' => $edit_memo,
        ]);
    }

    public function update(Request $request){
        $posts = $request->all();

        Memo::where('id',$posts['memo_id'])->update([
            'content' => $posts['content'],
            'user_id' => \Auth::id(),
        ]);

        return redirect()->route('home');
    }

    public function destroy(Request $request){
        $posts = $request->all();

        Memo::where('id',$posts['memo_id'])->update([
            'deleted_at' => date("Y-m-d H:i:s", time()),
            'user_id' => \Auth::id(),
        ]);

        return redirect()->route('home');
    }
}

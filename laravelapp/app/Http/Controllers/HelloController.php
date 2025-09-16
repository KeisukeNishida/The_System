<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use Illuminate\Support\Facades\Input; // ← 未使用なので削除
use App\Http\Requests\HelloRequest;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Person;

class HelloController extends Controller
{
    public function getAuth(Request $request)
    {
        $param = ['message' => 'ログインして下さい。'];
        return view('hello.auth', $param);
    }

    public function postAuth(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $msg = 'ログインしました。（' . Auth::user()->name . '）';
        } else {
            $msg = 'ログインに失敗しました。';
        }
        return view('hello.auth', ['message' => $msg]);
    }

    public function index(Request $request)
    {
        // 並び替え可能カラムを限定（people テーブルの実在カラムに合わせる）
        $sortable = ['id', 'name', 'mail', 'age'];

        // ?sort=xxx&dir=asc|desc を受け取り、未指定や不正値は安全なデフォルトへ
        $sort = $request->query('sort', 'id');
        if (!in_array($sort, $sortable, true)) {
            $sort = 'id';
        }
        $dir = strtolower($request->query('dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        // ページネーションしつつ、現在の並び替え条件をクエリに保持
        $items = Person::orderBy($sort, $dir)
            ->simplePaginate(10)
            ->appends(['sort' => $sort, 'dir' => $dir]);

        return view('hello.index', ['items' => $items, 'sort' => $sort, 'dir' => $dir]);
    }

    public function post(Request $request)
    {
        $items = DB::select('select * from people');
        return view('hello.index', ['items' => $items]);
    }

    public function show(Request $request)
    {
        $page = (int) $request->page;
        $items = DB::table('people')
            ->offset($page * 3)
            ->limit(3)
            ->get();
        return view('hello.show', ['items' => $items]);
    }

    public function add(Request $request)
    {
        return view('hello.add');
    }

    public function create(Request $request)
    {
        $param = [
            'name' => $request->name,
            'mail' => $request->mail,
            'age'  => $request->age,
        ];
        DB::table('people')->insert($param);
        return redirect('/hello');
    }

    public function edit(Request $request)
    {
        $item = DB::table('people')->where('id', $request->id)->first();
        return view('hello.edit', ['form' => $item]);
    }

    public function update(Request $request)
    {
        $param = [
            'name' => $request->name,
            'mail' => $request->mail,
            'age'  => $request->age,
        ];
        DB::table('people')->where('id', $request->id)->update($param);
        return redirect('/hello');
    }

    public function del(Request $request)
    {
        $item = DB::table('people')->where('id', $request->id)->first();
        return view('hello.del', ['form' => $item]);
    }

    public function remove(Request $request)
    {
        DB::table('people')->where('id', $request->id)->delete();
        return redirect('/hello');
    }

    public function rest(Request $request)
    {
        return view('hello.rest');
    }

    // session.

    public function ses_get(Request $request)
    {
        $sesdata = Session::get('msg');
        return view('hello.session', ['session_data' => $sesdata]);
    }

    public function ses_put(Request $request)
    {
        $msg = $request->input;
        Session::put('msg', $msg);
        return redirect('hello/session');
    }
}

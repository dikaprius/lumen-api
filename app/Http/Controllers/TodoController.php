<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;
use Auth;
use App\User;

class TodoController extends Controller
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
    * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $todo = Auth::user()->todo()->get();
      return response()->json(['status'=> 'success', 'result'=> $todo]);
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
         'todo' => 'required',
         'description' => 'required',
         'category' => 'required'
       ]);

       if(Auth::user()->todo()->Create($request->all())){
         return response()->json(['status' => 'success'], 200);
       }else{
         return response()->json(['status'=> 'failed'], 401);
       }
     }

     public function show($id)
     {
       $userId = Auth::user()->id;
       $todo = Todo::where([
                    'id' =>$id,
                    'user_id'=> $userId
                    ])->get();
       // if(!$todo){
       //   return response()->json(['message'=> 'data not found']);
       // }
       return response()->json(['message'=>'success' , 'status'=> 200, 'data'=>$todo]);
     }

     public function edit($id)
     {
       $todo = Todo::where('id', $id)->get();
       return view('todo.edittodo', ['todos' => $todo]);
     }

     public function update(Request $request)
     {
       $return = [];
       $returnNew= ['message'=> 'nothing changes'];
       $this->validate($request, [
         'todo' => 'required',
         'description' => 'required',
         'category' => 'required'
       ]);
       $id = $request->id;
       $todo = $request->get('todo');
       $description = $request->get('description');
       $category = $request->get('category');

       $oldTodo = Todo::find($id);

       if(!$oldTodo){
         return response()->json(['message'=> 'data not found']);
       }

       if($oldTodo->todo != $todo){
         $returnNew['todo'] = $oldTodo->todo. ' has changes to '. $todo;
       }
       if($oldTodo->description != $description){
         $returnNew['description'] = $oldTodo->description. ' has changes to '. $description;
       }
       if($oldTodo->category != $category){
         $returnNew['category'] = $oldTodo->category. ' has changes to '. $category;
       }

        $oldTodo->todo = $todo;
        $oldTodo->description = $description;
        $oldTodo->category = $category;

       if($oldTodo->save()){
         $return = ['message'=> 'success', 'status'=>200, 'data'=> $returnNew];
       }
       return response()->json($return);
     }

     public function destroy($id)
     {
       $return = [];
       $todo = Todo::find($id);
       if(!$todo){
         $return= ['status'=> 200, 'message'=>'data not found'];
         return response()->json($return);
       }
       if($todo->delete()){
         $return= ['status'=> 'data has removed'];
       }
       return response()->json($return);
     }
}

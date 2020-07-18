<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Message;
use App\Events\MessageSent;

class MessageController extends Controller
{
    //get user
    public function user_list()
    {
        $users=User::latest()->where('id','!=',auth()->user()->id)->get();
        if(\Request::ajax()){ 
        return response()->json($users);
        }
        return abort(404);
    }
    //get message
    public function user_message($id=null)
    {
       // if(\Request::ajax()){ 
           // return abort(404);
           // }
           $user=User::findOrFail($id);
            $message=Message::where(function($q) use($id){
                $q->where('from',auth()->user()->id);
                $q->where('to',$id);
                $q->where('type',0);

            })->orWhere(function($q) use($id){
                $q->where('from',$id);
                $q->where('to',auth()->user()->id);
                $q->where('type',1);
            })->with('user')->get();
            return response()->json([
                'messages'=>$message,
                'user'=>$user,
            ]);
            
           
    }
    public function send_message(Request $request)
    {
        if(!$request->ajax())
        {
            abort(404);
        }
        $message=Message::create([
            'message'=>$request->message,
            'from'=>auth()->user()->id,
            'to'=>$request->user_id,
            'type'=>0,
        ]);
        $message=Message::create([
            'message'=>$request->message,
            'from'=>auth()->user()->id,
            'to'=>$request->user_id,
            'type'=>1,

        ]);
        broadcast(new MessageSent($message));
        return response()->json($message);
    }
    public function delete_single_message($id=null)
    {
        if(!\Request::ajax())
        {
            return  abort(404);
        }
        Message::findOrFail($id)->delete();
        return response()->json('delete');
    }
    public function delete_all_message($id=null)
    {
        
        $messages=$this->all_user($id);
        foreach($messages as $value)
        {
            Message::findOrFail($value->id)->delete();
        }
        return response()->json('all message deleted');
    }
    public function all_user($id)
    {
        $message=Message::where(function($q) use($id){
            $q->where('from',auth()->user()->id);
            $q->where('to',$id);
            $q->where('type',0);

        })->orWhere(function($q) use($id){
            $q->where('from',$id);
            $q->where('to',auth()->user()->id);
            $q->where('type',1);
        })->with('user')->get();
        return $message;
    }
}

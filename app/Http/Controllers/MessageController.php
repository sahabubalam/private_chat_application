<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Message;

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

            })->orWhere(function($q) use($id){
                $q->where('from',$id);
                $q->where('to',auth()->user()->id);
            })->with('user')->get();
            return response()->json([
                'messages'=>$message,
                'user'=>$user,
            ]);
            
           
    }
}

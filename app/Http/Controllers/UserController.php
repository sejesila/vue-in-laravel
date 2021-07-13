<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function getUsers(){
        //get users list
        $user = User::orderBy('created_at','desc')->paginate(5);
      if(empty($user))
      {
          return false;
      }
        return UserCollection::collection($user);
    }
    public function deleteUser($id){
        //delete user
        $user = User::findOrFail($id)->delete();
        if(empty($user)){
            return false;
        }
        return $user;
    }

    public function addUser(Request $request){
        //validation of inputs
        $validator = Validator::make($request->all(),
        [
            'name'=>'required',
            'email'=>'required|email'

        ]);
        $fail['message'] = 'Please enter valid inputs';
        if($validator->fails()){
            return $fail;
        }

        //Add user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make(rand(1000,99999));
        $user->save();
        return $user;
    }
    public function editUser(Request $request){
        //Validation of inputs
        $validator = Validator::make($request->all(),
        [
            'name'=>'required',
            'email'=>'required|email'

        ]);
        $fail['message'] = 'Please enter valid inputs';
        if($validator->fails()){
            return $fail;
        }

        //update user
        DB::table('users')->where('id',$request->id)->update(['name' => $request->name]);
        return true;
    

    }

}

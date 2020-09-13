<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

      /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Gate::allows('isAdmin')){
            return User::latest()->paginate(3);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|max:191|unique:users',
            'mobile' => 'required',
            'type' => 'required',
            'password' => 'required|string|min:6',
        ]);

        return User::create([
            'name'  => $request['name'],
            'email' => $request['email'], 
            'password' => Hash::make($request['password']),
            'mobile' => $request['mobile'],
            'photo' => $request['photo'],
            'type' => $request['type']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function profile()
    {
        return auth('api')->user();
    }

    public function updateProfile(Request $request){
        $user = auth('api')->user();
        
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|max:191|unique:users,email,'.$user->id,
            'mobile' => 'required',
            'password' => 'sometimes|required|string|min::6'
        ]);

        $currentPhoto = $user['photo'];
        
        if($request->photo != $currentPhoto){
            $name = time().'.'.explode('/',explode(':',substr($request->photo,0,strpos($request->photo,';')))[1])[1];
            \Image::make($request->photo)->save(public_path('img/profile/').$name);

            $request->merge(['photo'=>$name]);

            $userPhoto = public_path('img/profile/').$currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }

        if(!empty($request->password)){
            $request->merge(['password'=>Hash::make($request->password)]);
        }

        $user->update($request->all());
        return ['message','success'];
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
        //
        $user = User::findOrFail($id);
        
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|max:191|unique:users,email,'.$user->id,
            'mobile' => 'required',
            'type' => 'required',
            'password' => 'sometimes|required|string|min::6'
        ]);
        
        $user->update([
            'name'  => $request['name'],
            'email' => $request['email'], 
            'mobile' => $request['mobile'],
            'photo' => $request['photo'],
            'type' => $request['type']
        ]);

        return ['message','success'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return ['message'=>'User deleted'];
    }
}

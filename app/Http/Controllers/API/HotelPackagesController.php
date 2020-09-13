<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HotelPackage;

class HotelPackagesController extends Controller
{
    /* Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //if(\Gate::allows('isAdmin')){
            return HotelPackage::latest()->paginate(5);
        //}
    }

    public function getAll()
    {
        return json_encode(HotelPackage::get()->all());
        //return json_encode(HotelPackage::paginate(3));
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
            'hotel_name' => 'required|string|max:191',
            'price' => 'required',
            'package_validity' => 'required',
            'duration' => 'required',
            'description' => 'required',
            //'photo' => 'required',
        ]);

        $name = time().'.'.explode('/',explode(':',substr($request['photo'],0,strpos($request['photo'],';')))[1])[1];
            \Image::make($request['photo'])->save(public_path('img/hotel/').$name);

            $request->merge(['photo'=>$name]);

        return HotelPackage::create([
            'hotel_name'  => $request['hotel_name'],
            'price' => $request['price'], 
            'package_validity' => $request['package_validity'],
            'photo' => $request['photo'],
            'duration' => $request['duration'],
            'description' => $request['description']
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
        $res = HotelPackage::findOrFail($id);
        return $res;
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
         $HotelPackage = HotelPackage::findOrFail($id);
        
         $this->validate($request,[
            'hotel_name' => 'required|string|max:191',
            'price' => 'required',
            'package_validity' => 'required',
            'duration' => 'required',
            'description' => 'required',
            //'photo' => 'required',
        ]);

        $currentPhoto = $HotelPackage['photo'];
        
        if($request->photo != $currentPhoto){
            $name = time().'.'.explode('/',explode(':',substr($request->photo,0,strpos($request->photo,';')))[1])[1];
            \Image::make($request->photo)->save(public_path('img/hotel/').$name);

            $request->merge(['photo'=>$name]);

            $photo = public_path('img/hotel/').$currentPhoto;
            if(file_exists($photo)){
                @unlink($photo);
            }
        }

        $HotelPackage->update([
            'hotel_name'  => $request['hotel_name'],
            'price' => $request['price'], 
            'package_validity' => $request['package_validity'],
            'photo' => $request['photo'],
            'duration' => $request['duration'],
            'description' => $request['description']
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
        $HotelPackage = HotelPackage::findOrFail($id);
        $HotelPackage->delete();
        return ['message'=>'Package deleted'];
    }
}

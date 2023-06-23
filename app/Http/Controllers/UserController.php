<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\ClinicDoctor;
use App\Models\User;
use App\Models\ClinicManager;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;


class UserController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index(Request $request)
    {
        $data = User::get();
        $roles = Role::pluck('name','name')->all();
        $clinics = Clinic::all();

        return view('users.index', compact('data', 'roles', 'clinics'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm_password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        if($request->input('roles') == 'office manager'){
            $clinic_manager = new ClinicManager();
            $clinic_manager->clinic_id = $request->input('clinic_id');
            $clinic_manager->manager_id = $user->id;
            $clinic_manager->save();
        }

        if($request->input('roles') == 'doctor'){
            $clinic_manager = new ClinicDoctor();
            $clinic_manager->clinic_id = $request->input('clinic_id');
            $clinic_manager->doctor_id = $user->id;
            $clinic_manager->save();
        }

        return redirect()->route('users.index')
            ->with('flash_success','User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        $clinics = Clinic::all();

        return view('users.edit',compact('user','roles','userRole', 'clinics'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));

        DB::table('clinic_managers')->where('manager_id', $id)->delete();

        if($request->input('roles')[0] == 'office manager'){
            $clinic_manager = new ClinicManager();
            $clinic_manager->clinic_id = $request->input('clinic_id');
            $clinic_manager->manager_id = $id;
            $clinic_manager->save();
        }

        DB::table('clinic_doctors')->where('doctor_id', $id)->delete();

        if($request->input('roles')[0] == 'doctor'){
            $clinic_doctor = new ClinicDoctor();
            $clinic_doctor->clinic_id = $request->input('clinic_id');
            $clinic_doctor->doctor_id = $id;
            $clinic_doctor->save();
        }

        return redirect()->route('users.index')
            ->with('flash_success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('flash_success','User deleted successfully');
    }
}

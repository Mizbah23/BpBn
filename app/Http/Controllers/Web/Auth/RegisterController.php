<?php

namespace Vanguard\Http\Controllers\Web\Auth;

use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\Auth\RegisterRequest;
use Vanguard\Repositories\Role\RoleRepository;
use Vanguard\Repositories\User\UserRepository;
use Vanguard\Support\Enum\UserStatus;
use Vanguard\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __construct(private UserRepository $users)
    {
        $this->middleware('registration')->only('show', 'register');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('auth.register', [
            'socialProviders' => config('auth.social.providers')
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param RegisterRequest $request
     * @param RoleRepository $roles
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request, RoleRepository $roles)
    {
        // dd($request->all());
        $role_id=$roles->findByName('User')->id;

        $request->validate([
            'phone' => 'required|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);
        // dd($request->all());
        // dd($request->file('profile_picture'));
        // $user = $this->users->create(
        //     array_merge($request->validFormData(), ['role_id' => $roles->findByName('User')->id])
        // );
        $codeExists = User::where('promo_code', $request->input_referral_code)->exists();
        // dd($codeExists);
        if($request->profile_picture)
		{
			 $file = $request->file('profile_picture');
			 $user_image = "profile".time().'.'.$file->getClientOriginalExtension();
			 $file->move(storage_path()."/app/public/upload/users/", $user_image);
		}

        $lastUser = User::latest('id')->first(); // Get the last inserted user by ID
        $nextId = $lastUser ? $lastUser->id + 1 : 1;

        $user=new User();
        $user->username=$request->first_name.$nextId;
        $user->password=$request->password;
        $user->first_name=$request->first_name;
        $user->phone=$request->phone;
        $user->avatar=$user_image??null;
        $user->address=$request->address;
        $user->role_id=$role_id;
        $user->status='Active';
        $user->promo_code=uniqid();
        if($codeExists==true){
            $user->input_referral_code=$request->input_referral_code;
        }
        $user->email_verified_at=Carbon::now();
        $user->save();

        // event(new Registered($user));

        $message =  __('Your account is created successfully!');

        \Auth::login($user);

        return redirect('/')->with('success', $message);
    }

    public function checkCode(Request $request){

        $exists = User::where('promo_code', $request->input_referral_code)->exists();
        return response()->json(['valid' => $exists]);
    }
}

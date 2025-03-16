<?php

namespace Vanguard\Http\Controllers\Web\Auth;

use Carbon\Carbon;
use DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\Auth\RegisterRequest;
use Vanguard\Repositories\Role\RoleRepository;
use Vanguard\Repositories\User\UserRepository;
use Vanguard\Support\Enum\UserStatus;
use Vanguard\User;
use Illuminate\Support\Facades\Hash;
use Vanguard\Barber;
use Vanguard\Bonus;

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
    //    dd($request->all());
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
        $codeExists = User::where('promo_code', $request->input_referral_code)->first();
        // dd($codeExists);
        if($request->profile_picture)
		{
			 $file = $request->file('profile_picture');
			 $user_image = "profile".time().'.'.$file->getClientOriginalExtension();
			 $file->move(storage_path()."/app/public/upload/users/", $user_image);
		}

        // if($request->work_image1)
		// {
        //     // dd($request->file('work_image1'));
		// 	 $file = $request->file('work_image1');
		// 	 $work_image1 = "work".time().'.'.$file->getClientOriginalExtension();
		// 	 $file->move(storage_path()."/app/public/upload/work/", $work_image1);
		// }

        // if($request->work_image2)
		// {
		// 	 $file = $request->file('work_image2');
		// 	 $work_image2 = "work".time().'.'.$file->getClientOriginalExtension();
		// 	 $file->move(storage_path()."/app/public/upload/work/", $work_image2);
		// }

        // if($request->work_image3)
		// {
		// 	 $file = $request->file('work_image3');
		// 	 $work_image3 = "work".time().'.'.$file->getClientOriginalExtension();
		// 	 $file->move(storage_path()."/app/public/upload/work/", $work_image3);
		// }

        // if($request->work_image4)
		// {
		// 	 $file = $request->file('work_image4');
		// 	 $work_image4 = "work".time().'.'.$file->getClientOriginalExtension();
		// 	 $file->move(storage_path()."/app/public/upload/work/", $work_image4);
		// }

        // if($request->work_image5)
		// {
		// 	 $file = $request->file('work_image5');
		// 	 $work_image5 = "work".time().'.'.$file->getClientOriginalExtension();
		// 	 $file->move(storage_path()."/app/public/upload/work/", $work_image5);
		// }

        if($request->nid_front)
		{
			 $file = $request->file('nid_front');
			 $nid_front = "work".time().'.'.$file->getClientOriginalExtension();
			 $file->move(storage_path()."/app/public/upload/work/", $nid_front);
		}

        if($request->nid_back)
		{
			 $file = $request->file('nid_back');
			 $nid_back = "work".time().'.'.$file->getClientOriginalExtension();
			 $file->move(storage_path()."/app/public/upload/work/", $nid_back);
		}
        $lastUser = User::latest('id')->first(); // Get the last inserted user by ID
        $nextId = $lastUser ? $lastUser->id + 1 : 1;
        DB::beginTransaction();
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
        if($codeExists){
            $user->input_referral_code=$request->input_referral_code;
        }
        $user->email_verified_at=Carbon::now();
        $user->save();

        $barber=new Barber();
        $barber->user_id=$user->id;
        $barber->full_name=$user->first_name;
        $barber->phone_1=$user->phone;
        $barber->phone_2=$request->phone2;
        $barber->address=$request->address;
        $barber->work_address=$request->work_address;
        $barber->gender=$request->gender;
        $barber->experience = implode(',', $request->service);
        $barber->bkash_number=$request->bkash_number;
        $barber->work_images = $request->work_images;
        $barber->nid_front=$nid_front??null;
        $barber->nid_back=$nid_back??null;
        $barber->profile_picture=$user_image;
        $barber->work_status=$request->work_status;
        $barber->is_verified=0;
        $barber->save();
        // dd($barber);
        $bonus= new Bonus();
        $bonus->user_id=$user->id;
        $bonus->total_earnings=50;
        $bonus->total_withdrawals=0;
        $bonus->save();
        if($codeExists){
          $newBonus=Bonus::where('user_id',$codeExists->user_id)->first();
          $newBonus->total_earnings=$codeExists->total_earnings+50;
          $newBonus->update();
        }

        // event(new Registered($user));
        DB::commit();
        $message =  __('Your account is created successfully!');

        \Auth::login($user);

        return redirect('/')->with('success', $message);
    }

    public function checkCode(Request $request){

        $exists = User::where('promo_code', $request->input_referral_code)->exists();
        return response()->json(['valid' => $exists]);
    }


    public function uploadWorkImages(Request $request)
{
    $request->validate([
        'work_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    if ($request->hasFile('work_images')) {
        $file = $request->file('work_images')[0]; // Single file upload
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(storage_path()."/app/public/upload/work/", $filename);

        return response()->json(['path' => 'upload/work/' . $filename]);
    }

    return response()->json(['error' => 'Upload failed'], 400);
}
}

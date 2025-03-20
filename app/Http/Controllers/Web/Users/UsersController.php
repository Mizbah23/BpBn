<?php

namespace Vanguard\Http\Controllers\Web\Users;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Vanguard\Events\User\Deleted;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\User\CreateUserRequest;
use Vanguard\Repositories\Activity\ActivityRepository;
use Vanguard\Repositories\Country\CountryRepository;
use Vanguard\Repositories\Role\RoleRepository;
use Vanguard\Repositories\User\UserRepository;
use Vanguard\Support\Enum\UserStatus;
use Vanguard\User;

/**
 * Class UsersController
 * @package Vanguard\Http\Controllers
 */
class UsersController extends Controller
{
    public function __construct(private UserRepository $users)
    {
    }

    /**
     * Display paginated list of all users.
     *
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $users = $this->users->paginate($perPage = 20, $request->search, $request->status);

        $statuses = ['' => __('All')] + UserStatus::lists();

        return view('user.list', compact('users', 'statuses'));
    }

    /**
     * Displays user profile page.
     *
     * @param User $user
     * @return Factory|View
     */
    public function show(User $user)
    {
        return view('user.view', compact('user'));
    }

    /**
     * Displays form for creating a new user.
     *
     * @param CountryRepository $countryRepository
     * @param RoleRepository $roleRepository
     * @return Factory|View
     */
    public function create(CountryRepository $countryRepository, RoleRepository $roleRepository)
    {
        return view('user.add', [
            'countries' => $this->parseCountries($countryRepository),
            'roles' => $roleRepository->lists(),
            'statuses' => UserStatus::lists()
        ]);
    }

    /**
     * Parse countries into an array that also has a blank
     * item as first element, which will allow users to
     * leave the country field unpopulated.
     *
     * @param CountryRepository $countryRepository
     * @return array
     */
    private function parseCountries(CountryRepository $countryRepository)
    {
        return [0 => __('Select a Country')] + $countryRepository->lists()->toArray();
    }

    /**
     * Stores new user into the database.
     *
     * @param CreateUserRequest $request
     * @return mixed
     */
    public function store(CreateUserRequest $request)
    {
        // When user is created by administrator, we will set his
        // status to Active by default.
        $data = $request->all() + [
            'status' => UserStatus::ACTIVE,
            'email_verified_at' => now()
        ];

        if (! data_get($data, 'country_id')) {
            $data['country_id'] = null;
        }

        // Username should be updated only if it is provided.
        if (! data_get($data, 'username')) {
            $data['username'] = null;
        }

        $this->users->create($data);

        return redirect()->route('users.index')
            ->withSuccess(__('User created successfully.'));
    }

    /**
     * Displays edit user form.
     *
     * @param User $user
     * @param CountryRepository $countryRepository
     * @param RoleRepository $roleRepository
     * @return Factory|View
     */
    public function edit(User $user, CountryRepository $countryRepository, RoleRepository $roleRepository)
    {

        return view('user.edit', [
            'edit' => true,
            'user' => $user,
            'countries' => $this->parseCountries($countryRepository),
            'roles' => $roleRepository->lists(),
            'statuses' => UserStatus::lists(),
            'socialLogins' => $this->users->getUserSocialLogins($user->id)
        ]);
    }

    /**
     * Removes the user from database.
     *
     * @param User $user
     * @return $this
     */
    public function destroy(User $user)
    {
        if ($user->is(auth()->user())) {
            return redirect()->route('users.index')
                ->withErrors(__('You cannot delete yourself.'));
        }

        $this->users->delete($user->id);

        event(new Deleted($user));

        return redirect()->route('users.index')
            ->withSuccess(__('User deleted successfully.'));
    }

    public function smsSend()
    {
        $users = User::all();
        // dd($users);


        return view('user.sms', compact('users'));
    }

    public function smsSendPost(Request $request){
        //  dd($request->all());
         if($request->status==""){
            $users=User::leftjoin('barbers','barbers.user_id','=','users.id')->where('barbers.is_verified',0)->get();
         }elseif($request->status==2){
            $users=User::leftjoin('barbers','barbers.user_id','=','users.id')->where('barbers.is_verified',2)->get();
         }elseif($request->status==1){
            $users=User::leftjoin('barbers','barbers.user_id','=','users.id')->where('barbers.is_verified',1)->get();
         }else{
            $users=User::all();
         }

         $c=count($users);

         for ($i=0; $i < $c; $i++) {

         $api_url = "http://bulksmsbd.net/api/smsapi?api_key=IBPJuXuI2JXlzglJkS3Z&type=text&senderid=8809617613089";


         $text=$request->message;


             // dd($text);
         $url = $api_url;
         $number=$users[$i]->phone;//"88017,88018,88019";
         $data= array(
         'number'=>"$number",
         'message'=>"$text"
         );
         // dd($data);
         $ch = curl_init(); // Initialize cURL
         curl_setopt($ch, CURLOPT_URL,$url);
         curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         $smsresult = curl_exec($ch);
         $p = explode("|",$smsresult);
         // // dd($p);
         $sendstatus = $p[0];
         $r=json_decode($sendstatus);

         }
         return redirect()->back()
         ->withSuccess(__('Message hase been sent to '.$c.' users.'));
    }
}

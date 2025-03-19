<?php

namespace Vanguard\Http\Controllers\Web\Users;

use Illuminate\Http\Request;
use Vanguard\Barber;
use Vanguard\Bonus;
use Vanguard\Events\User\Banned;
use Vanguard\Events\User\UpdatedByAdmin;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\User\UpdateDetailsRequest;
use Vanguard\Repositories\User\UserRepository;
use Vanguard\Support\Enum\UserStatus;
use Vanguard\User;

/**
 * Class UserDetailsController
 * @package Vanguard\Http\Controllers\Users
 */
class DetailsController extends Controller
{
    public function __construct(private UserRepository $users)
    {
    }

    /**
     * Updates user details.
     *
     * @param User $user
     * @param UpdateDetailsRequest $request
     * @return mixed
     */
    public function update(User $user, UpdateDetailsRequest $request)
    {
        $data = $request->all();
        $user=User::find($data['user_id']);
        $barber=Barber::where('user_id',$data['user_id'])->first();
        // dd($barber);
        $barber->is_verified=$data['is_verified'];
        $barber->message=$data['message'];
        $barber->update();

        if($barber->is_verified==2){
            $text=$barber->message;
            $api_url = "http://bulksmsbd.net/api/smsapi?api_key=IBPJuXuI2JXlzglJkS3Z&type=text&senderid=8809617613089";

            $url = $api_url;
            $number="$user->phone";//"88017,88018,88019";
            $data= array(
                'number'=>"$number",
                'message'=>"$text"
            );

            $ch = curl_init(); // Initialize cURL
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $smsresult = curl_exec($ch);
            $p = explode("|",$smsresult);
        }


		// $sendstatus = $p[0];

		// return $p;

        if($barber->is_verified==1){
         $bonus=Bonus::where('user_id',$data['user_id'])->first();
         $bonus->total_withdrawals=50;
         $bonus->update();

         $referral_user = User::where('promo_code', $user->input_referral_code)->first();

         if ($referral_user) {
             $bonus = Bonus::where('user_id', $referral_user->id)->first();

             if ($bonus) {
                 $bonus->total_withdrawals += 50; // Add 50 to the current total_earnings
                 $bonus->update();
             }
         }
        }





        return redirect()->back()
            ->withSuccess(__('User updated successfully.'));
    }

    /**
     * Check if user is banned during last update.
     *
     * @param User $user
     * @param Request $request
     * @return bool
     */
    private function userWasBanned(User $user, Request $request)
    {
        return $user->status != $request->status
            && $request->status == UserStatus::BANNED;
    }
}

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
        $barber->update();

        if($barber->is_verified==1){
         $bonus=Bonus::where('user_id',$data['user_id'])->first();
         $bonus->total_withdrawals=50;
         $bonus->update();

         $referral_user=User::where('promo_code',$user->input_referral_code)->first();
            if($referral_user){
                $bonus=Bonus::where('user_id',$referral_user->id)->first();
                $bonus->total_withdrawals=50;
                $bonus->update();
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

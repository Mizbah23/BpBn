<?php

namespace Vanguard\Http\Controllers\Web\Profile;

use Request;
use Vanguard\Barber;
use Vanguard\Events\User\UpdatedProfileDetails;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\User\UpdateProfileDetailsRequest;
use Vanguard\Repositories\User\UserRepository;

/**
 * Class DetailsController
 * @package Vanguard\Http\Controllers
 */
class DetailsController extends Controller
{
    public function __construct(private UserRepository $users)
    {
    }

    /**
     * Update profile details.
     *
     * @param UpdateProfileDetailsRequest $request
     * @return mixed
     */
    public function update(UpdateProfileDetailsRequest $request)
    {
        // dd($request->all());


        $barber=Barber::where('user_id',$request->user_id)->first();

        $barber->full_name=$request->first_name;
        $barber->phone_1=$request->phone;
        $barber->phone_2=$request->phone_2;
        $barber->address=$request->address;
        $barber->gender=$request->gender;
        // $barber->experience = implode(',', $request->experience);
        $barber->bkash_number=$request->bkash;

        if ($request->work_images) {

               $workImagePaths = [];

            foreach ($request->file('work_images') as $image) {
                if ($image->isValid()) {
                    $file = $image; // Single file upload
                    dd($file);
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path()."/app/public/upload/work/", $filename);

                    $workImagePaths[] = $image;
                }
            }
            $barber->work_images = json_encode($workImagePaths);
        }

        $barber->nid_front=$nid_front??$barber->nid_front;
        $barber->nid_back=$nid_back??$barber->nid_back;
        $barber->is_verified=0;
        //  $barber->is_verified=0;
        $barber->update();

        return redirect()->back()
            ->withSuccess(__('Profile updated successfully.'));
    }



    public function uploadWorkImages(Request $request)
    {
        $request->validate([
            'work_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('work_images')) {
            $file = $request->file('work_images')[0]; // Single file upload
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path()."/app/public/upload/work/", $filename);

            return response()->json(['path' => 'upload/work/' . $filename]);
        }

          return response()->json(['error' => 'Upload failed'], 400);
    }
}

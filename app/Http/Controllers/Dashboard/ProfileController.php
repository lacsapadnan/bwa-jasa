<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Http\Requests\Dashboard\Profile\UpdateProfileRequest;
use App\Http\Requests\Dashboard\Profile\UpdateDetailUserRequest;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;



use App\Models\User;
use App\Models\DetailUser;
use App\Models\ExperienceUser;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::where('id', Auth::user()->id)->first();
        $experience = ExperienceUser::where('detail_user_id', $user->detail_user->id)
                                    ->orderBy('id', 'asc')
                                    ->get();

        return view('pages.dashboard.profile', compact('user', 'experience'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $request_profile, UpdateDetailUserRequest $request_detail_user)
    {
        $data_profile = $request_profile->all();
        $data_detail_user = $request_detail_user->all();

        // Get Photo
        $photo = DetailUser::where('users_id', Auth::user()->id)->first();

        // Delete old photo
        if(isset($data_detail_user['photo'])) {
            $data = 'storage/'.$photo['photo'];
            if(File::exists($data)) {
                File::delete($data);
            } else {
                File::delete('storage/app/public'.$photo['photo']);
            }
        }

        // Store new photo
        if (isset($data_detail_user['photo'])) {
            $data_detail_user['photo'] = $request_detail_user->file('photo')->store(
                'assets/photo', 'public'
            );
        }

        // Save t0 user table
        $user = User::find(Auth::user()->id);
        $user->update($data_profile);

        // Save to detail user
        $detail_user = DetailUser::find($user->detail_user->id);
        $detail_user->update($data_detail_user);


        // Save to experience table
        $experience = ExperienceUser::where('detail_user_id', $detail_user['id'])->first();
        if (isset($experience)) {
            foreach ($data_profile['experience'] as $key => $value) {
                $experience = ExperienceUser::find($key);
                $experience->detail_user_id = $detail_user['id'];
                $experience->experience = $value;
                $experience->save();
            }
        } else {
            foreach ($data_profile['experience'] as $key => $value) {
                if (isset($value)) {
                    $experience = new ExperienceUser;
                    $experience->detail_user_id = $detail_user['id'];
                    $experience->experience = $value;
                    $experience->save();
                }
            }
        }

        toast()->success('Profile has been updated successfully');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return abort(404);
    }

    // Delete Old Photo
    public function delete()
    {
        // Get User
        $userPhoto = DetailUser::where('users_id', Auth::user()->id)->first();
        $pathPhoto = $userPhoto['photo'];

        //Second update value to null
        $data = DetailUser::find($userPhoto['id']);
        $data->photo = NULL;
        $data->save();

        // Delete file from storage
        $data = 'storage/'.$pathPhoto;
        if (File::exists($data)) {
            File::delete($data);
        } else {
            File::delete('storage/app/public'.$pathPhoto);
        }

        toast()->success('Photo has been deleted successfully');
        return back();
    }
}

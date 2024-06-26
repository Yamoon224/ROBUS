<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\Yoda\Yoda;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Sid\SidUser;

class ToolController extends Controller
{
    public function activity()
    {
        if (!Auth::user()->hasPermissionTo('logsActivity')) {
            abort(403);
        }
        $activities = Activity::orderBy('created_at', 'DESC')->paginate(10);
        dd(  $activities);
        return view('pages.admin.tools.activitylog', compact('activities'));
    }

    public function enableImpersonate($id)
    {
        if (!Auth::user()->hasPermissionTo('impersonate')) {
            abort(403);
        }
        $check = SidUser::find($id);
        if (!empty($check)) {
            Auth::user()->impersonate($check);
            return redirect('/');
        } else {
            abort(403);
        }
    }

    public function leaveImpersonate()
    {
        $manager = app('impersonate');
        $manager->leave();
        return redirect('/');
    }

    public function randomQuote()
    {
        if (!session(date('DMY'))) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.quotable.io/random',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $response = json_decode($response);
            session([date('DMY') => $response->content]);
        }
    }
}
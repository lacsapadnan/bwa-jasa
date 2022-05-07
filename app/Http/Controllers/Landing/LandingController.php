<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Order;
use App\Models\Tagline;
use App\Models\Service;
use App\Models\AdvantageUser;
use App\Models\AdvantageService;
use App\Models\ThumbnailService;



class LandingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service = Service::orderBy('created_at', 'desc')->get();

        return view('pages.landing.index', compact('service'));
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
    public function update(Request $request, $id)
    {
        return abort(404);
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


    // Explore
    public function explore()
    {
        $service = Service::orderBy('created_at', 'desc')->get();

        return view('pages.landing.explore', compact('service'));
    }

    // Detail
    public function detail($id)
    {
        $service = Service::where('id', $id)->first();
        $thumbnail = ThumbnailService::where('service_id', $id)->get();
        $advantage_user = AdvantageUser::where('service_id', $id)->get();
        $advantage_service = AdvantageService::where('service_id', $id)->get();
        $tagline = Tagline::where('service_id', $id)->get();

        return view('pages.landing.detail', compact('service', 'thumbnail', 'advantage_user', 'advantage_service', 'tagline'));
    }

    // Booking
    public function booking($id)
    {
        $service = Service::where('id', $id)->first();
        $buyer_id = Auth::user()->id;

        // Validation booking
        if($service->users_id == $buyer_id) {
            toast()->warning('You can not book your own service');
            return back();
        }

        $order = new Order;
        $order->buyer_id = $buyer_id;
        $order->freelancer_id = $service->user->id;
        $order->service_id = $service->id;
        $order->file = NULL;
        $order->note = NULL;
        $order->expired = Date('Y-m-d', strtotime('+3 day'));
        $order->order_status_id = 4;
        $order->save();

        $order_detail = Order::where('id', $order->id)->first();

        return redirect()->route('detail.booking.landing', $order_detail->id);
    }

    // Detail Booking
    public function detail_booking($id)
    {
        $order = Order::where('id', $id)->first();

        return view('pages.landing.booking', compact('order'));
    }
}

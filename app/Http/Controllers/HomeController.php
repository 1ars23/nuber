<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class HomeController extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    public function postCreateBookings(\Illuminate\Http\Request $request) {


        $input = $request->all();
        $values = (object) $input;
        if(!(array)$values)
        {
            $error400 = "Error 400 = Bad Request";
            return json_encode($error400);
        };

        //VALIDATE CARTYPE
        if ($values->car_type != 'Premium' && $values->car_type != 'Standard') {
            $error100 = "Error 100 = Invalid_CarType: Values must Premium or Standard";
            return json_encode($error100);
        }

        //VALIDATE EMAIL
        if (!filter_var($values->email, FILTER_VALIDATE_EMAIL)) {
            $error101 = "Error 101 = Invalid email format";
            return json_encode($error101);
        }

        //GENERATE ALPHANUMERIC BOOKING REFERENCE
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $alpha_numeric = self::generate_string($permitted_chars, 10);


        $bookings = new \App\Bookings;
        $bookings->name = $values->name;
        $bookings->email = $values->email;
        $bookings->car_type = $values->car_type;
        $bookings->address = $values->address;
        $bookings->booking_reference = $alpha_numeric;
        $bookings->status = "pending";
        $bookings->save();

        return json_encode("{status:200 OK}");
    }

    public function postCancelBookings(\Illuminate\Http\Request $request) {


        $input = $request->all();
        $values = (object) $input;
        if(!(array)$values)
        {
            $error400 = "Error 400 = Bad Request";
            return json_encode($error400);
        };
        $booking_reference = $values->bookingReference;

        $bookings = \App\Bookings::where('booking_reference', '=', $booking_reference)->whereNull('deleted_at')->first();
        if (count($bookings) > 0) {
            $bookings->status = "Canceled";
            $bookings->save();
            return json_encode("{status:200 OK}");
        } else {
            $error103 = "Error 103 = Booking not found in System";
            return json_encode($error103);
        }
    }

    public function getBookings() {

        $bookings = \App\Bookings::select('*')->whereNull('deleted_at')->get();
        return json_encode($bookings);
    }

    public function postAcceptBookings(\Illuminate\Http\Request $request) {

        $input = $request->all();
        $values = (object) $input;
        if(!(array)$values)
        {
            $error400 = "Error 400 = Bad Request";
            return json_encode($error400);
        };

        $booking_reference = $values->bookingReference;
        $bookings = \App\Bookings::where('booking_reference', '=', $booking_reference)->whereNull('deleted_at')->first();
        if (count($bookings) > 0) {
        $bookings->status = "Accepted";
        $bookings->save();
        return json_encode("{status:200 OK}");
        } else {
            $error103 = "Error 103 = Booking not found in System";
            return json_encode($error103);
        }

    }

    public function generate_string($input, $strength = 16) {
        $input_length = strlen($input);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }

}

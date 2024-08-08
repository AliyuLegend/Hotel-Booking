<?php

namespace App\Http\Controllers\Hotels;

use Illuminate\Support\Facades\Auth;



use App\Http\Controllers\Controller;
use App\Models\Apartment\Apartment;
use App\Models\Booking\Booking;
use App\Models\Hotel\Hotel;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;


class HotelsController extends Controller
{
    

    public function rooms($id) {

        $getRooms = Apartment::select()->orderBy('id', 'desc')->take(6)
         ->where('hotel_id', $id)->get();

         return view('hotels.rooms', compact('getRooms'));
         
    
    }


    public function roomDetails($id) {

        $getRoom = Apartment::find($id);
         
         return view('hotels.roomdetails', compact('getRoom'));
         
    
    }
    
    public function roomBooking(Request $request, $id) {

        $room = Apartment::find($id);
        $hotel = Hotel::find($id);

         // Convert request dates to DateTime objects
            $checkInDate = new DateTime($request->check_in);
            $checkOutDate = new DateTime($request->check_out);
            $currentDate = new DateTime();

            // Ensure the check-in and check-out dates are in the future
                if($checkInDate > $currentDate && $checkOutDate > $currentDate) {
                    // Check that the check-in date is before the check-out date
                    if($checkInDate < $checkOutDate) {
                        $interval = $checkInDate->diff($checkOutDate);
                        $days = $interval->format('%a');

                        $bookRooms = Booking::create([
                            "name" => $request->name,
                            "email" => $request->email, // Changed from phone_number to email
                            "phone_number" => $request->phone_number,
                            "check_in" => $request->check_in,
                            "check_out" => $request->check_out,
                            "days" => $days,
                            "price" => $days * $room->price,
                            "user_id" => Auth::user()->id,
                            "room_name" => $room->name,
                            "hotel_name" => $hotel->name
                        ]);

                        $totalPrice = $days * $room->price; // Make sure the property is 'price', not 'proce'
                        Session::put('price', $totalPrice);
                        $a =$totalPrice;


                      //  $totalPrice  = $days * $room->price;
                       // $price = Session::put('price', $totalPrice);

                        $getPrice = Session::get($a);

                        return Redirect::route('hotels.pay');

                            } else {
                                
                                return Redirect::route('hotels.rooms.details', $room->id)->with(['error' => 'Check-out date should be greater than check-in date']);

                            }

                        } else {

                            return Redirect::route('hotels.rooms.details', $room->id)->with(['error_dates' => 'Choose dates in the future, invalid check-in or check-out dates']);

                        
                        }
                    
                    }



                   public function  payWithPypal ()   {


                        return view('hotels.pay');
                   }    

                   public function  success ()   {

                    Session::forget(('price'));

                    return view('hotels.success');
               }    




      }
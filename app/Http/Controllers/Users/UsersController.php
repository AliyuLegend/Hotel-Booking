<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking\Booking;

class UsersController extends Controller
{
    public function myBookings() {

        $bookings = Booking::select()->orderBy('id', 'desc')
         ->where('user_id', Auth::user()->id)
          ->get();

          return view('users.bookings', compact('bookings'));
    }
}

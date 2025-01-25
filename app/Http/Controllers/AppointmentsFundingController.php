<?php

namespace App\Http\Controllers;

use App\Models\AppointmentsFunding;
use Illuminate\Http\Request;

class AppointmentsFundingController extends Controller
{
    public function destroy($id)
    {
        try {
            $appointment_funding = AppointmentsFunding::find($id);
            $appointment_funding->delete();

            session()->flash('flash', ['banner' => 'Appointment Funding data deleted successfully.', 'bannerStyle' => 'success']);
        } catch (\Exception $e) {
            session()->flash('flash', ['banner' => 'Failed to delete Appointment Funding data.', 'bannerStyle' => 'danger']);
        }
        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\PersonnelDataExport;
use App\Models\Personnel;
use App\Jobs\UpdateStepIncrement;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class PersonnelController extends Controller
{
    public function index()
    {
        $personnels = Personnel::all();
        return view('personnel.index', compact('personnels'));
    }

    public function show($id)
    {
        $personnel = Personnel::findOrFail($id);
        return view('personnel.show', compact('personnel'));
    }

    public function profile()
    {
        $personnel = Personnel::findOrFail(Auth::user()->personnel->id);
        return view('personnel.show', compact('personnel'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'personnel_id' => 'required',
                'school_id' => 'required',
            ]);

                Personnel::create($request->all());
                session()->flash('flash.banner', 'Personnel Created Successfully');
                session()->flash('flash.bannerStyle', 'success');
            } catch (ValidationException $e) {
                session()->flash('flash.banner', 'Failed to create Personnel');
                session()->flash('flash.bannerStyle', 'danger');
            }
            return redirect()->back();
    }

    public function create()
    {
        return view('personnel.create');
    }


    public function loyaltyAwards()
    {
        $recipients = Personnel::getLoyaltyAwardRecipients();
        return view('personnel.loyalty-awards', compact('recipients'));
    }

    public function export($id)
    {
        $personnel = Personnel::findOrFail($id);

        // Pass the personnel data to the export class
        $export = new PersonnelDataExport($personnel->id);

        return response()->download($export->getOutputPath(), $personnel->personnel_id . '_pds.xlsm');
    }

    public function destroy($id)
    {
        try {
            $personnel = Personnel::findOrFail($id);


            $personnel->delete();

            session()->flash('flash.banner', 'Personnel Deleted Successfully');
            session()->flash('flash.bannerStyle', 'success');
        } catch (\Exception $e) {

            session()->flash('flash.banner', 'Failed To Delete Personnel.' . $e);
            session()->flash('flash.bannerStyle', 'danger');
        }
        return redirect()->route('personnels.index');
    }
}

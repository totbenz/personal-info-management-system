<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::all();
        return view('position.index', compact('positions'));
    }


    public function store(Request $request)
    {
        try {
        $request->validate([
            'title' => 'required',
            'classification' => 'required',
        ]);

            Position::create($request->all());
            session()->flash('flash.banner', 'Position Created Successfully');
            session()->flash('flash.bannerStyle', 'success');
        } catch (ValidationException $e) {
            session()->flash('flash.banner', 'Failed to create Position');
            session()->flash('flash.bannerStyle', 'danger');
        }
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required|max:255',
                'classification' => 'required',
            ]);
                $position = Position::findOrFail($id);
                $position->update($request->all());

                session()->flash('flash.banner', "Position successfully updated");
                session()->flash('flash.bannerStyle', 'success');

            } catch (ValidationException $e) {
                session()->flash('flash.banner', "Failed to saved Position");
                session()->flash('flash.bannerStyle', 'danger');

            } catch (ModelNotFoundException $e) {
                session()->flash('flash.banner', 'Personnel Not Found.');
                session()->flash('flash.bannerStyle', 'danger');
            }
        return redirect()->back();
    }


    public function destroy($id)
    {
        try {
                $position = Position::findOrFail($id);
                $position->delete();

                session()->flash('flash.banner', "Position successfully deleted");
                session()->flash('flash.bannerStyle', 'success');
            } catch (ModelNotFoundException $e) {
                session()->flash('flash.banner', 'Personnel Not Found.');
                session()->flash('flash.bannerStyle', 'danger');
            }

        return redirect()->route('position.index');
    }
}


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Signature;

class SignatureController extends Controller
{
    public function edit()
    {
        $signatures = Signature::all();
        return view('admin.signatures.edit', compact('signatures'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'signatures' => 'required|array',
            'signatures.*.id' => 'required|exists:signatures,id',
            'signatures.*.position_name' => 'required|string|max:255',
            'signatures.*.full_name' => 'required|string|max:255',
        ]);

        foreach ($data['signatures'] as $sigData) {
            $signature = Signature::find($sigData['id']);
            $signature->position_name = $sigData['position_name'];
            $signature->full_name = $sigData['full_name'];
            $signature->save();
        }

        return redirect()->route('admin.signatures.edit')->with('success', 'Signatures updated successfully.');
    }
}

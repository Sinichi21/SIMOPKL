<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;
use App\Models\Register;
use App\Models\Logbook;
use App\Models\Mitra;
use App\Models\Periode;
use App\Models\User;
use App\Models\Awardee;

class PklController extends Controller
{
    # Registration Index
    public function registrationIndex()
    {
        $registers = Register::with(['user', 'periode', 'mitra'])->get();
        return view('pkl.register.index', compact('registers'));
    }

    # Registration Create Index
    public function registrationCreate(){
        $awardee = Awardee::where('user_id', auth()->user()->id)->first();
        $periods = Periode::all()->where('status', '1');
        $mitras = Mitra::all()->where('status', '1');
        return view('pkl.register.create', compact('awardee', 'periods', 'mitras'));
    }

    # Registration Store
    public function registrationStore(Request $request){
        $request->validate([
            'periodId' => 'required',
            'mitraId' => 'required',
            'awardeeId' => 'required',
            'form_2a' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:1024'],
            'form_2b' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:1024'],
            'transkrip_nilai' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:1024'],
            'sk_penerimaan_mitra' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:1024'],
        ]);

        $form_2a = $request->file('form_2a')->store('documents/form_2a', 'public');
        $form_2b = $request->file('form_2b')->store('documents/form_2b', 'public'); 
        $transkrip_nilai = $request->file('transkrip_nilai')->store('documents/transkrip_nilai', 'public');
        $sk_penerimaan_mitra = $request->file('sk_penerimaan_mitra')->store('documents/sk_penerimaan_mitra', 'public');
        $awardee = Awardee::findOrFail($request->awardeeId);

        Register::create([
            'registration_number' => now()->format('YmdHis'),
            'awardee_id' => $request->awardeeId,
            'periode_id' => $request->periodId,
            'mitra_id' => $request->mitraId,
            'fullname' => $awardee->fullname,
            'nim' => $awardee->nim,
            'faculty' => $awardee->studyProgram->faculty->name,
            'study_program' => $awardee->studyProgram->name,
            'email' => $awardee->user->email
        ]);
        return redirect()->route('Registration.index')->with('success', 'Registrasi PKL berhasil diajukan.');
    }   
}

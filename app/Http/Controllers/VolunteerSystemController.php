<?php

namespace App\Http\Controllers;

use App\Models\DisasterReport;
use App\Models\Volunteer;
use App\Models\VolunteerLog;
use App\Models\VolunteerAssignment;
use Illuminate\Http\Request;

class VolunteerSystemController extends Controller
{
    public function index(Request $request)
    {
        $disasters = DisasterReport::with(['actionTags'])->latest()->get();
        $volunteers = Volunteer::with(['profile'])->latest()->get();

        $logs = VolunteerLog::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                return $query->where('log_title', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('min_vote'), function ($query) use ($request) {
                return $query->where('verified_votes', '>=', $request->min_vote);
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('proyek_relawan.dashboard', compact('disasters', 'volunteers', 'logs'));
    }

    public function storeVolunteer(Request $request)
    {
        $volunteer = Volunteer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        $volunteer->profile()->create([
            'blood_type' => $request->blood_type,
            'emergency_contact' => $request->emergency_contact,
            'skills' => $request->skills,
        ]);

        return redirect()->back()->with('success', 'Akun & Profil Relawan Baru Berhasil Terdaftar!');
    }

    public function assignVolunteer(Request $request)
    {
        VolunteerAssignment::create([
            'disaster_report_id' => $request->disaster_report_id,
            'volunteer_id' => $request->volunteer_id,
            'role_in_field' => $request->role_in_field,
            'briefing_note' => $request->briefing_note,
        ]);

        $disaster = DisasterReport::find($request->disaster_report_id);
        $disaster->increment('joined_volunteers');

        return redirect()->back()->with('success', 'Relawan Berhasil Dialokasikan ke Posko Musibah!');
    }
}
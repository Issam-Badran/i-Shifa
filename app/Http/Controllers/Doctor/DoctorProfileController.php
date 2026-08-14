<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoctorProfileController extends Controller
{

    public function show(Request $request)
    {
        $doctor = $request->user()->doctor;
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'profile' => [
                // Identity
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'latitude' => $user->latitude,
                'longitude' => $user->longitude,
                'phone' => $doctor->phone,

                // Professional
                'specialization' => $doctor->specialization,
                'sub_specialization' => $doctor->sub_specialization,
                'experience_years' => $doctor->experience_years,
                'languages' => $doctor->languages,
                'license_number' => $doctor->license_number,
                'degree' => $doctor->degree,
                'university' => $doctor->university,
                'bio' => $doctor->bio,


                // Stats
                'appointments_count' => $doctor->appointments_count,
            ]
        ]);
    }

    public function update(Request $request)
    {
        $doctor = $request->user()->doctor;
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => 'string|nullable',
            'last_name' => 'string|nullable',
            'phone' => 'string|nullable',

            'specialization' => 'sometimes|in:طب عام,طب القلب,طب الأطفال,طب الأسرة,طب العيون,طب الأعصاب,الأمراض الجلدية,أمراض النساء,جراحة عامة,جراحة العظام,جراحة الأعصاب,جراحة التجميل,جراحة الأوعية,جراحة الصدر,جراحة المسالك,الأمراض الصدرية,الأمراض الباطنية,أمراض الغدد,أمراض الكلى,أمراض الدم,أمراض الروماتيزم,أمراض المناعة,أمراض الهضم,أمراض الأنف والأذن والحنجرة,الطب النفسي,طب الطوارئ,طب التخدير,طب الأورام,طب الجلدية والتجميل,طب الشيخوخة,طب الفيزياء والتأهيل',
                        
            'sub_specialization' => 'string|nullable',
            'experience_years' => 'integer|nullable',
            'languages' => 'string|nullable',
            'license_number' => 'string|nullable',
            'degree' => 'string|nullable',
            'university' => 'string|nullable',
            'bio' => 'string|nullable',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Update user fields
        if (isset($validated['first_name'])) $user->first_name = $validated['first_name'];
        if (isset($validated['last_name'])) $user->last_name = $validated['last_name'];
        if (isset($validated['latitude'])) $user->latitude = $validated['latitude'];
        if (isset($validated['longitude'])) $user->longitude = $validated['longitude'];
        $user->save();

        // Update doctor fields
        $doctor->fill($validated);
        $doctor->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully.'
        ]);
    }

}

<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    public function index()
    {
        $specializations = [
            'طب عام',
            'طب القلب',
            'طب الأطفال',
            'طب الأسرة',
            'طب العيون',
            'طب الأعصاب',
            'الأمراض الجلدية',
            'أمراض النساء',
            'جراحة عامة',
            'جراحة العظام',
            'جراحة الأعصاب',
            'جراحة التجميل',
            'جراحة الأوعية',
            'جراحة الصدر',
            'جراحة المسالك',
            'الأمراض الصدرية',
            'الأمراض الباطنية',
            'أمراض الغدد',
            'أمراض الكلى',
            'أمراض الدم',
            'أمراض الروماتيزم',
            'أمراض المناعة',
            'أمراض الهضم',
            'أمراض الأنف والأذن والحنجرة',
            'الطب النفسي',
            'طب الطوارئ',
            'طب التخدير',
            'طب الأورام',
            'طب الجلدية والتجميل',
            'طب الشيخوخة',
            'طب الفيزياء والتأهيل'
        ];

        return response()->json([
            'status' => 'success',
            'specializations' => $specializations
        ]);
    }
}

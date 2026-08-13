<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->enum('specialization', [
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
                'طب الفيزياء والتأهيل',
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            //
        });
    }
};

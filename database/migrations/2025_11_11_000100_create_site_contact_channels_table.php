<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('site_contact_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_setting_id')->constrained('site_settings')->cascadeOnDelete();
            $table->unsignedTinyInteger('position')->default(1); // 1 أو 2
            $table->string('label')->nullable(); // مثال: "الرئيسية" / "الفرع"
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address_ar')->nullable();
            $table->timestamps();
        });

        // ترحيل بيانات الحقول القديمة لقناتين
        // نفترض أن عندك صف واحد في site_settings (أو أكثر: نكرر لكل صف)
        $settings = DB::table('site_settings')->get();
        foreach ($settings as $s) {
            // القناة الأولى من الحقول الأساسية
            if ($s->email || $s->phone || $s->address_ar) {
                DB::table('site_contact_channels')->insert([
                    'site_setting_id' => $s->id,
                    'position'        => 1,
                    'label'           => 'الرئيسية',
                    'email'           => $s->email,
                    'phone'           => $s->phone,
                    'address_ar'      => $s->address_ar,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }

            // القناة الثانية من الحقول contact_*
            $contactEmail   = $s->contact_email   ?? null;
            $contactPhone   = $s->contact_phone   ?? null;
            $contactAddress = $s->contact_address ?? null;

            if ($contactEmail || $contactPhone || $contactAddress) {
                DB::table('site_contact_channels')->insert([
                    'site_setting_id' => $s->id,
                    'position'        => 2,
                    'label'           => 'بديلة',
                    'email'           => $contactEmail,
                    'phone'           => $contactPhone,
                    'address_ar'      => $contactAddress,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }
        }
    }

    public function down(): void {
        Schema::dropIfExists('site_contact_channels');
    }
};

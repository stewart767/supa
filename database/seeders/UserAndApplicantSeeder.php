<?php

namespace Database\Seeders;

use App\Models\AcademicProfile;
use App\Models\AcademicYear;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\Intake;
use App\Models\Payment;
use App\Models\Programme;
use App\Models\User;
use App\Services\AdmissionDecisionService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAndApplicantSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('Password123!');

        // 1. Administrative Users (Ensure passwords match Password123!)
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@supa.ac.tz'],
            ['name' => 'Dr. Emmanuel M. (Super Admin)', 'phone' => '+255711000001', 'role' => 'super_admin', 'password' => $password, 'is_active' => true, 'email_verified_at' => now()]
        );

        $registrar = User::updateOrCreate(
            ['email' => 'registrar@supa.ac.tz'],
            ['name' => 'Prof. Josephat K. (Registrar)', 'phone' => '+255711000002', 'role' => 'registrar', 'password' => $password, 'is_active' => true, 'email_verified_at' => now()]
        );

        $admissionOfficer = User::updateOrCreate(
            ['email' => 'admission@supa.ac.tz'],
            ['name' => 'Sarah J. (Admission Officer)', 'phone' => '+255711000003', 'role' => 'admission_officer', 'password' => $password, 'is_active' => true, 'email_verified_at' => now()]
        );

        $financeOfficer = User::updateOrCreate(
            ['email' => 'finance@supa.ac.tz'],
            ['name' => 'David M. (Finance Officer)', 'phone' => '+255711000004', 'role' => 'finance_officer', 'password' => $password, 'is_active' => true, 'email_verified_at' => now()]
        );

        // Fetch References
        $academicYear = AcademicYear::first();
        $intake = Intake::first();
        $baed = Programme::where('code', 'BAED')->first();
        $bsced = Programme::where('code', 'BSCED')->first();

        // 2. Student Applicant 1 (Direct Entry - Approved)
        $user1 = User::updateOrCreate(
            ['email' => 'applicant1@supa.ac.tz'],
            ['name' => 'Baraka Ally Juma', 'phone' => '+255755100100', 'role' => 'applicant', 'password' => $password, 'is_active' => true, 'email_verified_at' => now()]
        );

        $applicant1 = Applicant::firstOrCreate(
            ['user_id' => $user1->id],
            [
                'gender' => 'male',
                'date_of_birth' => '2001-05-12',
                'nida_number' => '20010512123450000112',
                'whatsapp_number' => '+255755100100',
                'region' => 'Dar es Salaam',
                'district' => 'Kinondoni',
                'ward' => 'Kijitonyama',
                'next_of_kin_name' => 'Ally Juma',
                'next_of_kin_phone' => '+255755100101',
                'next_of_kin_relation' => 'Father',
            ]
        );

        $app1 = Application::firstOrCreate(
            ['application_number' => 'SUPA/2026/00001'],
            [
                'applicant_id' => $applicant1->id,
                'programme_id' => $baed->id,
                'academic_year_id' => $academicYear->id,
                'intake_id' => $intake->id,
                'admission_type' => 'Diploma',
                'admission_category' => 'Direct Entry',
                'status' => 'Approved',
                'submitted_at' => now()->subDays(5),
            ]
        );

        AcademicProfile::firstOrCreate(
            ['application_id' => $app1->id],
            [
                'admission_type' => 'Diploma',
                'college_name' => 'Dar es Salaam Institute of Technology',
                'diploma_programme_name' => 'Diploma in Education',
                'diploma_registration_number' => 'DIT/2022/098',
                'diploma_graduation_year' => 2024,
                'gpa' => 3.65,
            ]
        );

        Payment::firstOrCreate(
            ['application_id' => $app1->id],
            [
                'control_number' => '991002026000001',
                'amount' => 20000.00,
                'payment_status' => 'paid',
                'verified_by' => $financeOfficer->id,
                'verified_at' => now()->subDays(4),
            ]
        );

        // Generate Admission Letter for Approved Student
        app(AdmissionDecisionService::class)->makeDecision($app1, $superAdmin, 'approve');

        // 3. Student Applicant 2 (Foundation Category - Under Review)
        $user2 = User::updateOrCreate(
            ['email' => 'applicant2@supa.ac.tz'],
            ['name' => 'Neema Charles Mwangi', 'phone' => '+255755200200', 'role' => 'applicant', 'password' => $password, 'is_active' => true, 'email_verified_at' => now()]
        );

        $applicant2 = Applicant::firstOrCreate(
            ['user_id' => $user2->id],
            [
                'gender' => 'female',
                'date_of_birth' => '2002-08-20',
                'nida_number' => '20020820123450000223',
                'region' => 'Arusha',
                'district' => 'Arusha Urban',
                'next_of_kin_name' => 'Charles Mwangi',
                'next_of_kin_phone' => '+255755200201',
                'next_of_kin_relation' => 'Father',
            ]
        );

        $app2 = Application::firstOrCreate(
            ['application_number' => 'SUPA/2026/00002'],
            [
                'applicant_id' => $applicant2->id,
                'programme_id' => $bsced->id,
                'academic_year_id' => $academicYear->id,
                'intake_id' => $intake->id,
                'admission_type' => 'Diploma',
                'admission_category' => 'Foundation',
                'status' => 'Under Review',
                'submitted_at' => now()->subDays(2),
            ]
        );

        AcademicProfile::firstOrCreate(
            ['application_id' => $app2->id],
            [
                'admission_type' => 'Diploma',
                'college_name' => 'Arusha Technical College',
                'diploma_programme_name' => 'Diploma in Science',
                'diploma_registration_number' => 'ATC/2022/112',
                'diploma_graduation_year' => 2024,
                'gpa' => 2.45,
            ]
        );

        Payment::firstOrCreate(
            ['application_id' => $app2->id],
            [
                'control_number' => '991002026000002',
                'amount' => 20000.00,
                'payment_status' => 'paid',
                'verified_by' => $financeOfficer->id,
                'verified_at' => now()->subDays(1),
            ]
        );
    }
}

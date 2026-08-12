<?php

namespace App\Http\Requests;

use App\Rules\CseeNumberRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'admission_type' => ['required', 'in:Diploma,Form Six'],
            'programme_id' => ['required', 'exists:programmes,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'intake_id' => ['required', 'exists:intakes,id'],

            // Diploma rules
            'college_name' => ['required_if:admission_type,Diploma', 'nullable', 'string', 'max:255'],
            'diploma_programme_name' => ['required_if:admission_type,Diploma', 'nullable', 'string', 'max:255'],
            'diploma_registration_number' => ['required_if:admission_type,Diploma', 'nullable', 'string', 'max:100'],
            'diploma_graduation_year' => ['required_if:admission_type,Diploma', 'nullable', 'integer', 'min:1990', 'max:' . date('Y')],
            'gpa' => ['required_if:admission_type,Diploma', 'nullable', 'numeric', 'min:0.0', 'max:5.0'],

            // Form Six rules
            'csee_number' => ['required_if:admission_type,Form Six', 'nullable', new CseeNumberRule],
            'csee_year' => ['required_if:admission_type,Form Six', 'nullable', 'integer', 'min:1990', 'max:' . date('Y')],
            'csee_school' => ['required_if:admission_type,Form Six', 'nullable', 'string', 'max:255'],
            'acsee_number' => ['required_if:admission_type,Form Six', 'nullable', new CseeNumberRule],
            'acsee_year' => ['required_if:admission_type,Form Six', 'nullable', 'integer', 'min:1990', 'max:' . date('Y')],
            'acsee_school' => ['required_if:admission_type,Form Six', 'nullable', 'string', 'max:255'],
            'acsee_combination' => ['required_if:admission_type,Form Six', 'nullable', 'string', 'max:50'],
            'acsee_points' => ['required_if:admission_type,Form Six', 'nullable', 'integer', 'min:1', 'max:30'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $admissionType = $this->input('admission_type');
            $programmeId = $this->input('programme_id');

            if (!$programmeId) {
                return;
            }

            $programme = \App\Models\Programme::find($programmeId);
            if (!$programme) {
                return;
            }

            if ($admissionType === 'Diploma') {
                $gpa = $this->input('gpa');
                if ($gpa !== null) {
                    $gpa = (float) $gpa;
                    if ($gpa < 2.0) {
                        $validator->errors()->add('gpa', 'Kiwango cha chini cha GPA kinachohitajika ili kuomba udahili ni 2.0. (A minimum GPA of 2.0 is required to apply for any programme.)');
                    } elseif ($gpa >= 2.0 && $gpa < 3.0) {
                        if ($programme->code !== 'Foundation') {
                            $validator->errors()->add('programme_id', 'Kwa GPA ya 2.0 hadi 2.9, una sifa za kujiunga na Foundation Course pekee. (With a GPA between 2.0 and 2.9, you only qualify for the Foundation Course bridging programme.)');
                        }
                    } else {
                        // gpa >= 3.0
                        if ($programme->code === 'Foundation') {
                            $validator->errors()->add('programme_id', 'Kwa GPA ya 3.0 au zaidi, una sifa za kujiunga na Shahada (Bachelor Degree) au Uzamili. Tafadhali chagua programu ya shahada. (With a GPA of 3.0 or above, you qualify for direct degree entry. Please choose a Bachelor or Postgraduate programme.)');
                        }
                    }
                }
            } elseif ($admissionType === 'Form Six') {
                $points = $this->input('acsee_points');
                if ($points !== null) {
                    $points = (int) $points;
                    if ($points < 5) {
                        if ($programme->code !== 'Foundation') {
                            $validator->errors()->add('programme_id', 'Kwa pointi chini ya 5 za Form Six, una sifa za kujiunga na Foundation Course pekee. (With less than 5 points, you only qualify for the Foundation Course bridging programme.)');
                        }
                    } else {
                        // points >= 5
                        if ($programme->code === 'Foundation') {
                            $validator->errors()->add('programme_id', 'Kwa pointi 5 au zaidi za Form Six, una sifa za kujiunga na Shahada (Bachelor Degree) au Uzamili. Tafadhali chagua programu ya shahada. (With 5 or more points, you qualify for direct degree entry. Please choose a Bachelor or Postgraduate programme.)');
                        }
                    }
                }
            }
        });
    }
}


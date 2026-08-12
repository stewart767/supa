<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\ProgrammeResource;
use App\Models\AdmissionLetter;
use App\Models\Application;
use App\Models\Contact;
use App\Models\Download;
use App\Models\Event;
use App\Models\Faq;
use App\Models\News;
use App\Models\Programme;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicPortalController extends Controller
{
    public function programmes(): JsonResponse
    {
        $programmes = Programme::where('is_active', true)->get();

        return response()->json([
            'programmes' => ProgrammeResource::collection($programmes),
        ]);
    }

    public function trackApplication(Request $request): JsonResponse
    {
        $request->validate([
            'application_number' => ['required', 'string'],
        ]);

        $app = Application::with(['programme', 'payment', 'admissionLetter'])
            ->where('application_number', $request->application_number)
            ->first();

        if (!$app) {
            return response()->json(['message' => 'No application found with the provided application number.'], 444);
        }

        return response()->json([
            'application_number' => $app->application_number,
            'programme' => $app->programme->name ?? 'N/A',
            'admission_category' => $app->admission_category,
            'status' => $app->status,
            'submitted_at' => $app->submitted_at?->toFormattedDateString(),
            'payment_status' => $app->payment->payment_status ?? 'pending',
            'has_admission_letter' => (bool) $app->admissionLetter,
        ]);
    }

    public function news(): JsonResponse
    {
        return response()->json(['news' => News::latest()->get()]);
    }

    public function events(): JsonResponse
    {
        return response()->json(['events' => Event::where('is_active', true)->orderBy('event_date')->get()]);
    }

    public function faqs(): JsonResponse
    {
        return response()->json(['faqs' => Faq::orderBy('order')->get()]);
    }

    public function downloads(): JsonResponse
    {
        return response()->json(['downloads' => Download::all()]);
    }

    public function submitContact(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $contact = Contact::create($validated);

        return response()->json([
            'message' => 'Thank you for reaching out. Your message has been received.',
            'contact_id' => $contact->id,
        ], 201);
    }

    public function downloadAdmissionLetter(string $verificationCode)
    {
        $letter = AdmissionLetter::with(['application.applicant.user', 'application.programme'])
            ->where('verification_code', $verificationCode)
            ->firstOrFail();

        return view('pdf.admission-letter', compact('letter'));
    }
}

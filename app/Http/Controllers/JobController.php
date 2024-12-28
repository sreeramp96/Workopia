<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class JobController extends Controller
{
    use AuthorizesRequests;
    public function index(): View
    {
        $jobs = Job::latest()->paginate(9);
        return view('jobs.index')->with('jobs', $jobs);
    }

    public function create()
    {
        return view('jobs.create');
    }
    public function store(Request $request): RedirectResponse
    {
        $valdidateData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'nullable|integer',
            'tags' => 'required|string',
            'job_type' => 'required|string',
            'remote' => 'required|boolean',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zipcode' => 'nullable|string',
            'contact_email' => 'required|string',
            'contact_phone' => 'nullable|string',
            'company_name' => 'required|string',
            'company_description' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,jpg,png,gif:max:2048',
            'company_website' => 'nullable|url',
        ]);

        $valdidateData['user_id'] = auth()->user()->id;

        if ($request->hasFile('company_logo')) {
            $path = $request->file('company_logo')->store('logos', 'public');
            $valdidateData['company_logo'] = $path;
        }

        Job::create($valdidateData);

        return redirect()->route('jobs.index')->with('success', 'Job listing created successfully');
    }

    public function show(Job $job): view
    {
        return view('jobs.show')->with('job', $job);
    }

    public function edit(Job $job): View
    {
        $this->authorize('update', $job);
        return view('jobs.edit')->with('job', $job);
    }

    public function update(Request $request, Job $job): string
    {
        $this->authorize('update', $job);

        $valdidateData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'nullable|integer',
            'tags' => 'required|string',
            'job_type' => 'required|string',
            'remote' => 'required|boolean',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zipcode' => 'nullable|string',
            'contact_email' => 'required|string',
            'contact_phone' => 'nullable|string',
            'company_name' => 'required|string',
            'company_description' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,jpg,png,gif:max:2048',
            'company_website' => 'nullable|url',
        ]);

        if ($request->hasFile('company_logo')) {
            Storage::delete('public/logos' . basename($job->company_logo));
            $path = $request->file('company_logo')->store('logos', 'public');
            $valdidateData['company_logo'] = $path;
        }

        $job->update($valdidateData);

        return redirect()->route('jobs.index')->with('success', 'Job listing updated successfully');
    }

    public function destroy(Job $job): RedirectResponse
    {
        $this->authorize('delete', $job);

        if ($job->company_logo) {
            Storage::delete('public/logos/' . $job->company_logo);
        }
        $job->delete();

        if (request()->query('from') == 'dashboard') {
            return redirect()->route('dashboard')->with('success', 'Job listing deleted successfully!');
        }

        return redirect()->route('jobs.index')->with('success', 'Job listing deleted successfully');
    }

    public function search(Request $request): view
    {
        $keywords = strtolower($request->input('keywords'));
        $location = strtolower($request->input('location'));
        $query = Job::query();
        if ($keywords) {
            $query->where(function ($q) use ($keywords) {
                $q->whereRaw('LOWER(address) like ?', ['%' . $keywords . '%'])
                    ->orWhereRaw('LOWER(city) like ?', ['%' . $keywords . '%'])
                    ->orWhereRaw('LOWER(state) like ?', ['%' . $keywords . '%'])
                    ->orWhereRaw('LOWER(zipcode) like ?', ['%' . $keywords . '%']);
            });

            $jobs = $query->latest()->paginate(12);

            return view('jobs.index')->with('jobs', $jobs);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Http\RedirectResponse;

class BookmarkController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $bookmarks = $user->bookmarkedJobs()->orderBy('job_user_bookmarks.created_at', 'desc')->paginate(9);
        return view('jobs.bookmarked')->with('bookmarks', $bookmarks);
    }

    public function store(Job $job): RedirectResponse
    {
        $user = Auth::user();
        if ($user->bookmarkedJobs()->where('job_id', $job->id)->exists()) {
            return back()->with('error', 'Job already bookmarked');
        }

        $user->bookmarkedJobs()->attach($job->id);
        return back()->with('success', 'Job bookmarked successfully');
    }

    public function destroy(Job $job): RedirectResponse
    {
        $user = Auth::user();
        if (!$user->bookmarkedJobs()->where('job_id', $job->id)->exists()) {
            return back()->with('error', 'Job is not bookmarked');
        }

        $user->bookmarkedJobs()->detach($job->id);
        return back()->with('success', 'Bookmarked removed successfully');
    }
}

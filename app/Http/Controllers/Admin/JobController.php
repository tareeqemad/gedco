<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::orderBy('sort')->paginate(20);
        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('admin.jobs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'link'        => 'nullable|url',
            'image'       => 'nullable|image|max:2048', // 2MB
            'sort'        => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('jobs', 'public');
        }

        Job::create($data);
        return redirect()->route('admin.jobs.index')->with('success', 'تمت الإضافة بنجاح');
    }

    public function edit(Job $job)
    {
        return view('admin.jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'link'        => 'nullable|url',
            'image'       => 'nullable|image|max:2048',
            'sort'        => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            // احذف القديمة لو موجودة
            if ($job->image) Storage::disk('public')->delete($job->image);
            $data['image'] = $request->file('image')->store('jobs', 'public');
        }

        $job->update($data);
        return redirect()->route('admin.jobs.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy(Job $job)
    {
        if ($job->image) Storage::disk('public')->delete($job->image);
        $job->delete();
        return back()->with('success', 'تم الحذف');
    }
}

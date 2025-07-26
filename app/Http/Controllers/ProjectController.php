<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $service;

    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $projects = Project::with('parent')->latest()->get();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $projects = Project::pluck('name', 'id');
        return view('admin.projects.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $this->service->create($request);
        return redirect()->route('admin.projects.index')->with('success', 'Tạo dự án thành công');
    }

    public function edit(Project $project)
    {
        $projects = Project::where('id', '!=', $project->id)->pluck('name', 'id');
        return view('admin.projects.edit', compact('project', 'projects'));
    }

    public function update(Request $request, Project $project)
    {
        $this->service->update($request, $project);
        return redirect()->route('admin.projects.index')->with('success', 'Cập nhật dự án thành công');
    }

    public function destroy(Project $project)
    {
        $this->service->delete($project);
        return redirect()->route('admin.projects.index')->with('success', 'Đã xóa dự án');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Workflow;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class WorkflowController extends Controller
{
    public function index()
    {
        $search = request()->input('search');
        $workflows = Workflow::when($search, function ($query) use ($search) {
                                $query->where('name', 'LIKE', '%' . $search . '%');
                            })
                            ->orderBy('id', 'DESC')
                            ->paginate(10)
                            ->withQueryString();

        return view('workflow.index', compact(
            'workflows'
        ));
    }

    public function tambah() 
    {
        $roles = Role::orderBy('name')->get();
        return view('workflow.tambah', compact(
            'roles'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'role_id' => 'required',
            'ordinal' => 'required',
            'status' => 'required',
        ]);

        Workflow::create($validated);
        return redirect('/workflow')->with('success', 'Data berhasil disimpan');
    }
    
    public function edit($id) 
    {
        $workflow = Workflow::find($id);
        $roles = Role::orderBy('name')->get();
        return view('workflow.edit', compact(
            'roles',
            'workflow',
        ));
    }

    public function update(Request $request, $id)
    {
        $workflow = Workflow::find($id);
        $validated = $request->validate([
            'name' => 'required',
            'role_id' => 'required',
            'ordinal' => 'required',
            'status' => 'required',
        ]);

        $workflow->update($validated);
        return redirect('/workflow')->with('success', 'Data berhasil disimpan');
    }
}

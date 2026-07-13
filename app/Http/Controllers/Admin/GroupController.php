<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::withCount(['users', 'metrics'])->orderBy('kode_group')->get();
        return view('admin.group.index', compact('groups'));
    }

    public function create()
    {
        return view('admin.group.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_group' => ['required', 'string', 'max:10', 'unique:groups,kode_group'],
            'nama_group' => ['nullable', 'string', 'max:100'],
        ]);

        Group::create($data);

        return redirect()->route('admin.group.index')
                         ->with('success', 'Group "' . $data['kode_group'] . '" created successfully.');
    }

    public function edit(Group $group)
    {
        return view('admin.group.edit', compact('group'));
    }

    public function update(Request $request, Group $group)
    {
        $data = $request->validate([
            'kode_group' => ['required', 'string', 'max:10', 'unique:groups,kode_group,' . $group->id],
            'nama_group' => ['nullable', 'string', 'max:100'],
        ]);

        $group->update($data);

        return redirect()->route('admin.group.index')
                         ->with('success', 'Group updated successfully.');
    }

    public function destroy(Group $group)
    {
        if ($group->users()->exists()) {
            return redirect()->route('admin.group.index')
                             ->with('error', 'Cannot delete group "' . $group->kode_group . '" because it still has PIC accounts assigned.');
        }

        if ($group->metrics()->exists()) {
            return redirect()->route('admin.group.index')
                             ->with('error', 'Cannot delete group "' . $group->kode_group . '" because it has metrics associated.');
        }

        $group->delete();

        return redirect()->route('admin.group.index')
                         ->with('success', 'Group deleted successfully.');
    }
}

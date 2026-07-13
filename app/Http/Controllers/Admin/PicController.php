<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PicController extends Controller
{
    public function index()
    {
        $pics = User::where('role', 'pic')
            ->with('group')
            ->orderBy('name')
            ->get();

        return view('admin.pic.index', compact('pics'));
    }

    public function create()
    {
        $groups = Group::orderBy('kode_group')->get();
        return view('admin.pic.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'group_id' => ['nullable', 'exists:groups,id'],
        ], [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.unique' => 'Email has already been taken.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pic',
            'group_id' => $request->group_id,
            'is_aktif' => true,
        ]);

        return redirect()->route('admin.pic.index')
            ->with('success', 'PIC account has been successfully created.');
    }

    public function edit(User $pic)
    {
        abort_if($pic->role !== 'pic', 404);
        $groups = Group::orderBy('kode_group')->get();
        return view('admin.pic.edit', compact('pic', 'groups'));
    }

    public function update(Request $request, User $pic)
    {
        abort_if($pic->role !== 'pic', 404);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($pic->id)],
            'group_id' => ['nullable', 'exists:groups,id'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.unique' => 'Email has already been taken.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'group_id' => $request->group_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pic->update($data);

        return redirect()->route('admin.pic.index')
            ->with('success', 'PIC account has been successfully updated.');
    }

    public function toggleAktif(User $pic)
    {
        abort_if($pic->role !== 'pic', 404);

        $pic->update(['is_aktif' => !$pic->is_aktif]);

        $status = $pic->is_aktif ? 'activated' : 'deactivated';

        return redirect()->route('admin.pic.index')
            ->with('success', "Account {$pic->name} has been successfully {$status}.");
    }

    public function destroy(User $pic)
    {
        abort_if($pic->role !== 'pic', 404);

        $name = $pic->name;
        $pic->delete();

        return redirect()->route('admin.pic.index')
            ->with('success', "Account {$name} has been successfully deleted.");
    }
}

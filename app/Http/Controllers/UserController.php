<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use LogActivity;

    public function index()
    {
        $users = User::where('role', 'karyawan')->get();
        return view('pages.karyawan.tambahKaryawan', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:3|confirmed',
        ]);

        do {
            $identificationNumber = str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);
        } while (User::where('identification_number', $identificationNumber)->exists());

        $user = User::create([
            'name'                  => $request->name,
            'username'              => $request->username,
            'password'              => Hash::make($request->password),
            'role'                  => 'karyawan',
            'identification_number' => $identificationNumber,
        ]);

        $this->logActivity($user, 'create', $request->all());

        return redirect()->route('page.karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('pages.karyawan.editKaryawan', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:3|confirmed',
        ]);

        $data = [
            'name'     => $request->name,
            'username' => $request->username,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        $this->logActivity($user, 'update', $request->all());

        return redirect()->route('page.karyawan.index')->with('success', 'Karyawan berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun sendiri.']);
        }

        $this->logActivity($user, 'delete');
        $user->delete();

        return redirect()->route('page.karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}

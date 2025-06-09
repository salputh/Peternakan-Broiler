<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Peternakan;
use Illuminate\Support\Facades\Storage;

class OwnerController extends Controller
{
     public function index()
     {
          return view('developer.owner.index', compact('peternakan'));
     }

     public function create(User $owner)
     {
          return view('developer.owner.create', compact('owner'));
     }

     public function edit(User $owner)
     {
          return view('developer.owner.edit', compact('owner'));
     }

     public function store(Request $request)
     {
          $validated = $request->validate([
               'name' => 'required|string|max:255', // nama user
               'email' => 'required|email|unique:users,email',
               'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
               'phone' => 'required|string|max:20',
               'password' => 'required|string|min:6|confirmed',
               'nama_peternakan' => 'required|string|max:255', // nama peternakan
          ]);
          if ($request->hasFile('photo')) {
               $validated['photo'] = $request->file('photo')->store('owner_photos', 'public');
          }

          DB::beginTransaction();
          try {
               // 1. Buat User Owner
               $owner = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'photo' => $validated['photo'] ?? null,
                    'password' => Hash::make($validated['password']),
                    'role' => 'owner',
               ]);

               // 2. Buat Peternakan
               $peternakan = Peternakan::create([
                    'nama' => $validated['nama_peternakan'],
                    'owner_id' => $owner->id,
               ]);

               DB::commit();
               $owner->update(['peternakan_id' => $peternakan->id]);
               return redirect()->route('developer.dashboard')->with('success', 'Owner dan peternakan berhasil ditambahkan.');
               // Akhir Transaction

          } catch (\Exception $e) {
               DB::rollBack();
               return back()
                    ->withErrors(['error' => $e->getMessage()])
                    ->withInput();
          }
     }

     public function update(Request $request, User $owner)
     {
          $validated = $request->validate([
               'name' => 'required|string|max:255',
               'phone' => 'required|string|max:20',
               'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
               'nama_peternakan' => 'required|string|max:255',
          ]);

          DB::beginTransaction();
          try {
               // Update owner data
               $updateData = [
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'photo' => $validated['photo'] ?? null,
               ];


               // Handle photo upload if provided
               if ($request->hasFile('photo')) {
                    $updateData['photo'] = $request->file('photo')->store('owner_photos', 'public');
               }

               $owner->update($updateData);

               // Update peternakan data
               if ($owner->peternakan) {
                    $owner->peternakan->update([
                         'nama' => $validated['nama_peternakan']
                    ]);
               }

               DB::commit();
               return redirect()->route('developer.dashboard')
                    ->with('success', 'Owner data has been updated successfully.');
          } catch (\Exception $e) {
               DB::rollBack();
               return back()
                    ->withErrors(['error' => 'Failed to update owner: ' . $e->getMessage()])
                    ->withInput();
          }
     }

     public function destroy(User $owner)
     {
          DB::beginTransaction();
          try {
               // Get the peternakan associated with this owner
               $peternakan = $owner->peternakan;

               if ($peternakan) {
                    // Delete the peternakan first
                    $peternakan->delete();
               }

               // Delete the owner
               $owner->delete();
               DB::commit();
               return redirect()->route('developer.dashboard')
                    ->with('success', 'Owner dan Peternakannya berhasil dihapus.');
          } catch (\Exception $e) {
               DB::rollBack();
               return back()->withErrors(['error' => 'Gagal Menghapus Owner' . $e->getMessage()]);
          }
     }

     // public function showOwners()
     // {
     //      $owners = User::with('peternakan')
     //           ->where('user_group', 'owner')->get();
     //      return view('admin.owner-show', compact('owners'));
     // }

     // public function showOwnerCreateForm()
     // {
     //      return view('admin.owner-create');
     // }

     // public function storeOwner(Request $request)
     // {
     //      $validated = $request->validate([
     //           'user_nama' => 'required|string|max:255',
     //           'user_email' => 'required|email|unique:users,user_email',
     //           'user_no_hp' => 'required|string|max:20',
     //           'user_password' => 'required|string|min:6|confirmed',
     //           'peternakan_nama' => 'required|string|max:255',
     //      ]);

     //      // 1. Buat User Owner & Peternakan
     //      $owner = \App\Models\User::create([
     //           'user_nama' => $validated['user_nama'],
     //           'user_email' => $validated['user_email'],
     //           'user_no_hp' => $validated['user_no_hp'],
     //           'user_password' => Hash::make($validated['user_password']),
     //           'user_group' => 'owner',
     //      ]);

     //      $peternakan = \App\Models\Peternakan::create([
     //           'nama_peternakan' => $validated['peternakan_nama'],
     //           'owner_id' => $owner->user_id,
     //      ]);

     //      $owner->update(['peternakan_id' => $peternakan->peternakan_id]);


     //      return redirect()->route('dev.dashboard')->with('success', 'Owner dan peternakan berhasil ditambahkan.');
     // }

     // public function editOwner(User $owner)
     // {
     //      // Memastikan user yang diedit adalah owner
     //      if ($owner->user_group !== 'owner') {
     //           return redirect()->route('dev.dashboard')
     //                ->with('error', 'User yang dipilih bukan owner.');
     //      }

     //      return view('admin.owner-edit', compact('owner'));
     // }

     // public function updateOwner(Request $request, User $owner)
     // {
     //      // Memastikan user yang diedit adalah owner
     //      if ($owner->user_group !== 'owner') {
     //           return redirect()->route('dev.dashboard')
     //                ->with('error', 'User yang dipilih bukan owner.');
     //      }

     //      $validated = $request->validate([
     //           'user_nama' => 'required|string|max:255',
     //           'user_email' => 'required|email|unique:users,user_email,' . $owner->user_id . ',user_id',
     //           'user_no_hp' => 'required|string|max:20',
     //           'user_password' => 'nullable|string|min:6|confirmed',
     //           'peternakan_nama' => 'required|string|max:255',
     //           'peternakan_alamat' => 'required|string|max:255',
     //      ]);

     //      // Update data peternakan
     //      $owner->peternakan->update([
     //           'nama_peternakan' => $validated['peternakan_nama'],
     //           'alamat' => $validated['peternakan_alamat'],
     //      ]);

     //      // Update data owner
     //      $updateData = [
     //           'user_nama' => $validated['user_nama'],
     //           'user_email' => $validated['user_email'],
     //           'user_no_hp' => $validated['user_no_hp'],
     //      ];

     //      // Update password jika diisi
     //      if (!empty($validated['user_password'])) {
     //           $updateData['user_password'] = Hash::make($validated['user_password']);
     //      }

     //      $owner->update($updateData);

     //      return redirect()->route('dev.dashboard')
     //           ->with('success', 'Data owner dan peternakan berhasil diperbarui.');
     // }


}

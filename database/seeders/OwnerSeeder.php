<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Peternakan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();
        try {
            // Data owner + peternakan
            $ownerData = [
                [
                    'nama'        => 'Owner Misjiwati',
                    'email'       => 'owner@misjiwatifarm.com',
                    'no_hp'       => '082233445566',
                    'peternakan'  => 'Misjiwati Farm',
                ],
                [
                    'nama'        => 'Owner Suherman',
                    'email'       => 'owner@suhermanfarm.com',
                    'no_hp'       => '082244556677',
                    'peternakan'  => 'Suherman Farm',
                ],
                [
                    'nama'        => 'Owner Budiman',
                    'email'       => 'owner@budimanfarm.com',
                    'no_hp'       => '082255667788',
                    'peternakan'  => 'Budiman Farm',
                ],
                // …tambahkan sesuai kebutuhan
            ];

            foreach ($ownerData as $data) {
                // Buat user owner
                $owner = new User();
                $owner->name     = $data['nama'];
                $owner->email    = $data['email'];
                $owner->phone    = $data['no_hp'];
                $owner->password = Hash::make('password123');
                $owner->role     = 'owner';
                $owner->save();

                // Buat slug unik
                $baseSlug = Str::slug($data['peternakan']) ?: 'peternakan';
                $slug = $baseSlug;
                $count = 1;
                while (Peternakan::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $count++;
                }

                // Buat peternakan
                $peternakan = new Peternakan();
                $peternakan->nama       = $data['peternakan'];
                $peternakan->slug       = $slug;
                $peternakan->owner_id   = $owner->id;
                $peternakan->is_active  = true;
                $peternakan->save();

                // Update owner dengan peternakan_id
                $owner->peternakan_id = $peternakan->id;
                $owner->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

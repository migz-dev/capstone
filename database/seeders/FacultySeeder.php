<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Evangeline M. Teruel',
            'Norbert Lewin F. Soliven',
            'Joseph S. Alipio',
            'Amparo M. Angeles',
            'Mae Cherryrose P. Antiporda',
            'Conchita B. Aromin',
            'Ofelia G. Balingit',
            'Mary Grace S. Basa',
            'Rhodaro M. Benedicto',
            'Jofet E. Bonayon',
            'Anthony F. Borja',
            'Betty P. Caeg',
            'Beverly Trina S, Carbonel',
            'Elsa V. Castro',
            'Violeta G. Cautivar',
            'Michelle G. Condado',
            'Dennnis Michael O. Cosme',
            'Dex Bryan A. De Leon',
            'Ryann A. De Silve',
            'John Paulo R. Del Rosario',
            'MA. Ina H. Dela Rosa',
            'Lara Marjorie N. Dellosa',
            'Karren C. Deocampo',
            'Wella Joy P. Diola',
            'Leilani O. Estacio',
            'Mary Ann C. Fernandez',
            'Ana Mae S. Galindo',
            'Sherryl L. Gil',
            'Marlon M. Lacerna JR',
            'Judith B. Leaño',
            'Angelica P. Leoveras',
            'Mary June B. Machate',
            'Edwin B. Malinic',
            'Katherine D. Mapula',
            'Mary Rose G. Mar',
            'Aida C. Medina',
            'Menchie L. Medina',
            'Argie R. Mendoza',
            'Renovo A. Mirador',
            'Annalize Morales',
            'Clarence Marie S. Nava',
            'Jennifer Rose G. Pacaanas',
            'Imelda B. Padrelanan',
            'Ariaane Joy A. Pangan',
            'Benjie G. Peras',
            'Cora P. Quinto',
            'Annalyn A. Ramos',
            'Mark Angelo A. Ramos',
            'Maria Iris V. Rodolfo',
            'Victoria A. Sta. Maria',
            'Julie F. Sumagaysay',
            'Aireen B. Trinindad',
            'Marites F. Tuazon',
            'Jonathan R. Vallarta',
            'Mary Ann C. Villalon',
            'Lorelie Villanueva',
        ];

        $now = now();
        $usedEmails = [];
        $usedIds = [];

        $rows = [];
        foreach ($names as $name) {
            // Make a clean, readable base like "evangeline.m.teruel"
            $base = Str::of($name)
                ->lower()
                ->replaceMatches('/[^a-z0-9]+/i', ' ')
                ->squish()
                ->replace(' ', '.')
                ->toString();

            // Guarantee unique email if two names collapse to the same slug
            $email = "{$base}@sys.test.ph";
            $suffix = 1;
            while (in_array($email, $usedEmails, true)) {
                $email = "{$base}{$suffix}@sys.test.ph";
                $suffix++;
            }
            $usedEmails[] = $email;

            // Unique 11-digit faculty_id
            do {
                $facultyId = (string) random_int(10000000000, 99999999999);
            } while (in_array($facultyId, $usedIds, true));
            $usedIds[] = $facultyId;

            $rows[] = [
                'full_name'   => $name,
                'email'       => $email,
                'profile_image' => null,
                'faculty_id'  => $facultyId,
                'password'    => Hash::make('Miguel2004'),
                'id_file_path'=> null,
                'status'      => 'approved',   // change to 'pending' if you prefer the default
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        DB::table('faculty')->insert($rows);
    }
}

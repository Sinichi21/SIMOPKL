<?php

namespace Database\Seeders;

use App\Models\Awardee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AwardeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Awardee::create([
            'fullname' => 'Kukuh Satrio Wicaksono',
            'username' => 'Kukuh Satrio',
            'nim' => '1906399045',
            'degree' => 'S1',
            'phone_number' => '0815555072',
            'user_id' => 2,
            'study_program_id' => 77,
            'year' => 2021
        ]);

        Awardee::create([
            'fullname' => 'Nirwana Pratiwi',
            'username' => 'Nirwana',
            'nim' => '1906399051',
            'degree' => 'S1',
            'phone_number' => '0813555079',
            'user_id' => 3,
            'study_program_id' => 77,
            'year' => 2022
        ]);

        Awardee::create([
            'fullname' => 'Adi Gunawan',
            'username' => 'Adi',
            'nim' => '2106806611',
            'degree' => 'S2',
            'phone_number' => '089905550377',
            'user_id' => 4,
            'study_program_id' => 82,
            'year' => 2023
        ]);

        Awardee::create([
            'fullname' => 'I Komang Ananta Aryadinata',
            'username' => 'Ananta',
            'nim' => '2006621184',
            'degree' => 'S2',
            'phone_number' => '0838555043',
            'user_id' => 5,
            'study_program_id' => 81,
            'year' => 2024
        ]);

        Awardee::create([
            'fullname' => 'Timotius Victory',
            'username' => 'Timotius',
            'nim' => '1906457556',
            'degree' => 'S2',
            'phone_number' => '0838555727',
            'user_id' => 6,
            'study_program_id' => 80,
            'year' => 2024
        ]);

        Awardee::create([
            'fullname' => 'Dony Martinus Sihotang',
            'username' => 'Dony',
            'nim' => '1906458312',
            'degree' => 'S3',
            'phone_number' => '0838555634',
            'user_id' => 7,
            'study_program_id' => 83,
            'year' => 2024
        ]);

        Awardee::create([
            'fullname' => 'Endina Putri Purwandari',
            'username' => 'Endina',
            'nim' => '1906437794',
            'degree' => 'S3',
            'phone_number' => '0854555113',
            'user_id' => 8,
            'study_program_id' => 83,
            'year' => 2024
        ]);

        Awardee::create([
            'fullname' => 'Indra Hermawan',
            'username' => 'Indra',
            'nim' => '1706010451',
            'degree' => 'S3',
            'phone_number' => '0878555050',
            'user_id' => 9,
            'study_program_id' => 83,
            'year' => 2024
        ]);

        Awardee::create([
            'fullname' => 'Mubarik Ahmad',
            'username' => 'Mubarik',
            'nim' => '1906341763',
            'degree' => 'S3',
            'phone_number' => '0838555672',
            'user_id' => 10,
            'study_program_id' => 83,
            'year' => 2024
        ]);
    }
}

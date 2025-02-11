<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatriculaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $combinations = [];
        $createdAt = $updatedAt = now();

        $id = 1;
        for ($alunoId = 1; $alunoId <= 20; $alunoId++) {
            for ($cursoId = 1; $cursoId <= 4; $cursoId++) {
                $combinations[] = [
                    "id" => $id,
                    "aluno_id" => $alunoId,
                    "curso_id" => $cursoId,
                    "created_at" => $createdAt,
                    "updated_at" => $updatedAt
                ];
                $id++;
            }
        }

        DB::table("matriculas")->insert(
            $combinations
        );
    }
}

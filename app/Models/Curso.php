<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;
    
    protected $fillable = ['titulo', 'descricao'];

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    public function alunos()
    {
        return $this->belongsToMany(Aluno::class, 'matriculas');
    }

    public function getDescricaoTruncadaAttribute()
    {
        return strlen($this->descricao) > 100 ? substr($this->descricao, 0, 100) . '...' : $this->descricao;
    }
}

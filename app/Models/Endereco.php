<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $fillable = ['rua', 'cep', 'numero'];

    public function origem()
    {
        return $this->morphTo();
    }

    public function bairro()
    {
        return $this->belongsTo(Bairro::class);
    }
}

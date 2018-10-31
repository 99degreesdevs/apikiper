<?php

namespace App\Modelos\Oportunidad;

use Illuminate\Database\Eloquent\Model;

class ColaboradorOportunidad extends Model
{
    protected $table = 'colaborador_oportunidad';
    protected $primaryKey = 'id_colaborador_oportunidad';
    protected $fillable = [
        'id_colaborador_oportunidad',
        'id_colaborador',
        'id_oportunidad'
    ];

    public function colaborador(){
        return $this->belongsTo('App\Modelos\User','id_colaborador','id');
    }

    public function oportunidad(){
        return $this->belongsTo('App\Modelos\Oportunidad\Oportunidad','id_oportunidad','id_oportunidad');

    }

    public function colaboradorDetalle(){
        return $this->hasOne('App\Modelos\Colaborador\DetalleColaborador','id_colaborador','id_colaborador');
    }
}
<?php

namespace App\Modelos\Oportunidad;

use Illuminate\Database\Eloquent\Model;

use Alsofronie\Uuid\UuidModelTrait;

 class Oportunidad extends Model
 {
    use UuidModelTrait;

    protected $table = 'oportunidades';
    protected $primaryKey = 'id_oportunidad';
    protected $fillable = [
        'id_oportunidad',
        'nombre_oportunidad'
    ];

    public function status_oportunidad(){
        return $this->hasOne('App\Modelos\Oportunidad\StatusOportunidad','id_oportunidad','id_oportunidad');
    }

    public function detalle_oportunidad(){
        return $this->hasOne('App\Modelos\Oportunidad\DetalleOportunidad','id_oportunidad','id_oportunidad');
    }

    public function servicio_oportunidad(){
        return $this->hasMany('App\Modelos\Oportunidad\ServicioOportunidad','id_oportunidad','id_oportunidad');
    }

    public function colaborador_oportunidad(){
        return $this->hasMany('App\Modelos\Oportunidad\ColaboradorOportunidad','id_oportunidad','id_oportunidad');
    }

    public function prospecto(){
        return $this->hasMany('App\Modelos\Oportunidad\ProspectoOportunidad','id_oportunidad','id_oportunidad');
    }

    public function eventos(){
        return $this->hasMany('App\Modelos\Extras\EventoOportunidad','id_oportunidad','id_oportunidad');
    }

    public function etiquetas_oportunidad(){
        return $this->hasMany('App\Modelos\Oportunidad\EtiquetasOportunidad','id_oportunidad','id_oportunidad');
    }

    public function archivos_oportunidad(){
        return $this->hasMany('App\Modelos\Oportunidad\ArchivosOportunidadColaborador','id_oportunidad','id_oportunidad');
    }

    public function recordatorios(){
        return $this->hasMany('App\Modelos\Extras\RecordatorioOportunidad','id_oportunidad','id_oportunidad');
    }

    public function scopeGetOneOportunidad($query,$id){
        return $query->with('detalle_oportunidad')
                ->with('status_oportunidad.status')
                ->with('servicio_oportunidad')
                ->with('colaborador_oportunidad.colaborador.detalle')
                ->with('prospecto.prospecto.detalle_prospecto')
                ->where('id_oportunidad',$id)->first();
    }

    public function scopeGetOportunidadEtiquetas($query,$id){
        return $query->with('etiquetas_oportunidad.etiqueta')->where('id_oportunidad',$id)->first();
    }
    public function scopeGetOportunidadArchivos($query,$id){
        return $query->with('archivos_oportunidad')->where('id_oportunidad',$id)->first();
    }
    public function scopeGetOportunidadRecordatorios($query,$id){
        return $query->with('recordatorios.detalle')->where('id_oportunidad',$id)->first();
    }
    public function scopeGetOportunidadEventos($query,$id){
        return $query->with('eventos.detalle')->where('id_oportunidad',$id)->first();
    }

 }

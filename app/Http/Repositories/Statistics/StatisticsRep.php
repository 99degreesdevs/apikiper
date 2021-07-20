<?php

namespace App\Http\Repositories\Statistics;

use App\Modelos\Oportunidad\Oportunidad;
use App\Modelos\Prospecto\Prospecto;
use App\Modelos\Oportunidad\CatStatusOportunidad;
use App\Http\Services\Funnel\FunnelService;
use App\Http\Services\UtilService;
use App\Http\Services\Statistics\StatisticsService;
use DB;

class StatisticsRep
{
    public static function ProspectosVsOportunidades($start_date, $end_date, $user_id=NULL)
    {
        $response = array();
        
        //Filter Days Prospectos
        $prospectos         = StatisticsRep::getProspectos($start_date, $end_date, $user_id, 'get');
        $prospectos_total   = StatisticsRep::getProspectos($start_date, $end_date, $user_id, 'count');

        //Filter Days Oportunidades
        $oportunidades = StatisticsRep::getOportunidades($start_date, $end_date, $user_id, 'get');

        //Oportunidades Cerradas
        $oportunidades_cerradas = StatisticsRep::getOportunidadesCerradas($start_date, $end_date, $user_id, 'count');

        //Oportunidades Cerradas by Fuente
        $oportunidades_by_fuente = StatisticsRep::getOportunidadesCerradasByFuente($start_date, $end_date, $user_id);

        $response['prospectos_filter_dates']        = StatisticsService::makeDatesRangeArray($prospectos, $start_date, $end_date);
        $response['oportunidades_filter_dates']     = StatisticsService::makeDatesRangeArray($oportunidades, $start_date, $end_date);
        $response['oportunidades_cerradas']         = $oportunidades_cerradas;
        $response['oportunidades_by_fuente']        = $oportunidades_by_fuente;
        $response['porcentaje_exito']               = number_format(($oportunidades_cerradas * 100) / $prospectos_total, 2);

        return $response;
    }

    public static function FunnelOportunidades($start_date, $end_date, $user_id=NULL)
    {
        
        $oportunidades = array();
        $oportunidades['status'] =  CatStatusOportunidad::all();
        
        if(!empty($oportunidades['status'])){
            
            foreach ($oportunidades['status'] as $key => $status) {
                $oportunidades['status'][$key]['oportunidades'] = StatisticsRep::oportunidadesByStatus($status['id_cat_status_oportunidad'], $start_date, $end_date, $user_id);
            }
        }
        return $oportunidades;
    }

    public static function oportunidadesByStatus($status_id, $start_date, $end_date, $user_id=NULL)
    {

        $oportunidades = Oportunidad::join('colaborador_oportunidad','colaborador_oportunidad.id_oportunidad','oportunidades.id_oportunidad')
                        ->join('users','colaborador_oportunidad.id_colaborador','users.id')
                        ->join('status_oportunidad','colaborador_oportunidad.id_oportunidad','status_oportunidad.id_oportunidad')
                        ->join('detalle_oportunidad','colaborador_oportunidad.id_oportunidad','detalle_oportunidad.id_oportunidad')
                        ->whereNull('oportunidades.deleted_at')
                        ->where('oportunidades.created_at',  '>=', $start_date)
                        ->where('oportunidades.created_at',  '<=', $end_date)
                        ->where('status_oportunidad.id_cat_status_oportunidad','=',$status_id);
        
        if(!is_null($user_id)){
            $oportunidades = $oportunidades->where('colaborador_oportunidad.id_colaborador', '=', $user_id);
        }

        $oportunidades = $oportunidades->count();

        return $oportunidades;

    }

    public static function ProspectosCerradosByColaborador($start_date, $end_date, $user_id=NULL)
    {
        $response               = array();
        $range_type             = UtilService::getDatesRangeForFilter($start_date, $end_date);
        $ranges                 = UtilService::getRangesFromRangeType($start_date, $end_date, $range_type);
        $oportunidades_cerradas = array();
        
        switch ($range_type) {
            case 'days':
                $oportunidades_cerradas[]   = [ 'start_date'    => $start_date,
                                                'end_date'      => $end_date,
                                                'oportunidades' => StatisticsRep:: getOportunidadesCerradas($start_date, $end_date, $user_id, 'count')];
                break;
            case 'weeks':
                foreach ($ranges as $key => $range) {
                    $oportunidades_cerradas[]   = [ 'start_date'    => $range['start_date'],
                                                    'end_date'      => $range['end_date'],
                                                    'oportunidades' => StatisticsRep:: getOportunidadesCerradas($range['start_date'], $range['end_date'], $user_id, 'count')];
                }
                break;
            case 'months':
                foreach ($ranges as $key => $range) {
                    $oportunidades_cerradas[]   = [ 'start_date'    => $range['start_date'],
                                                    'end_date'      => $range['end_date'],
                                                    'oportunidades' => StatisticsRep:: getOportunidadesCerradas($range['start_date'], $range['end_date'], $user_id, 'count')];
                }
                break;
            case 'years':
                foreach ($ranges as $key => $range) {
                    $oportunidades_cerradas[]   = [ 'start_date'    => $range['start_date'],
                                                    'end_date'      => $range['end_date'],
                                                    'oportunidades' => StatisticsRep:: getOportunidadesCerradas($range['start_date'], $range['end_date'], $user_id, 'count')];
                }
                break;
            default:
                $oportunidades_cerradas[]   = [ 'start_date'    => $start_date,
                                                'end_date'      => $end_date,
                                                'oportunidades' => StatisticsRep:: getOportunidadesCerradas($start_date, $end_date, $user_id, 'count')];
                break;
        }
        return $oportunidades_cerradas;
    }

    public static function getProspectos($start_date, $end_date, $user_id, $action='get')
    {
        $prospectos  =   Prospecto::select(DB::raw('DATE(prospectos.created_at) as date'), DB::raw('count(*) as total'))
                                    ->join('colaborador_prospecto', 'colaborador_prospecto.id_prospecto', 'prospectos.id_prospecto')
                                    ->where('prospectos.created_at', '>=', $start_date)
                                    ->where('prospectos.created_at', '<=', $end_date);
        
        if(!is_null($user_id)){
            $prospectos = $prospectos->where('colaborador_prospecto.id_colaborador', $user_id);
        }

        if ($action == 'count') {
            $prospectos = $prospectos->count();
        }else{
            $prospectos = $prospectos->groupBy('date')->get();
        }

        return $prospectos;
    }

    public static function getOportunidades($start_date, $end_date, $user_id, $action='get')
    {
        $oportunidades   =   Oportunidad::select(DB::raw('DATE(oportunidades.created_at) as date'), DB::raw('count(*) as total'))
                ->join('colaborador_oportunidad', 'colaborador_oportunidad.id_oportunidad', 'oportunidades.id_oportunidad')                            
                ->where('oportunidades.created_at', '>=', $start_date)
                ->where('oportunidades.created_at', '<=', $end_date);
        
        if(!is_null($user_id)){
            $oportunidades  =  $oportunidades->where('colaborador_oportunidad.id_colaborador', $user_id);
        }

        if ($action == 'count') {
            $oportunidades =  $oportunidades->groupBy('date')->count();
        }else{
            $oportunidades =  $oportunidades->groupBy('date')->get();
        }

        return $oportunidades;
    }

    public static function getOportunidadesCerradas($start_date, $end_date, $user_id, $action='get')
    {
       $oportunidades_cerradas =   Oportunidad::join('status_oportunidad', 'status_oportunidad.id_oportunidad', 'oportunidades.id_oportunidad')
                                                ->join('colaborador_oportunidad', 'colaborador_oportunidad.id_oportunidad', 'oportunidades.id_oportunidad')                            
                                                ->where('status_oportunidad.id_cat_status_oportunidad', 2)
                                                ->where('oportunidades.created_at', '>=', $start_date)
                                                ->where('oportunidades.created_at', '<=', $end_date);
        if(!is_null($user_id)){
            $oportunidades_cerradas  =  $oportunidades_cerradas->where('colaborador_oportunidad.id_colaborador', $user_id);
        }
        
        if ($action == 'count') {
            $oportunidades_cerradas =  $oportunidades_cerradas->count();
        }else{
            $oportunidades_cerradas =  $oportunidades_cerradas->get();
        }
        
        return $oportunidades_cerradas;
    }

    public static function getOportunidadesCerradasByFuente($start_date, $end_date, $user_id)
    {
        $oportunidades_by_fuente =   Oportunidad::select('cat_fuentes.id_fuente', 
                                                        DB::raw('count(*) as total_oportunidades'),
                                                        'cat_fuentes.nombre',
                                                        'cat_fuentes.url')
                                                ->join('status_oportunidad', 'status_oportunidad.id_oportunidad', 'oportunidades.id_oportunidad')
                                                ->join('colaborador_oportunidad', 'colaborador_oportunidad.id_oportunidad', 'oportunidades.id_oportunidad')
                                                ->join('oportunidad_prospecto', 'oportunidad_prospecto.id_oportunidad', 'oportunidades.id_oportunidad')
                                                ->join('prospectos', 'prospectos.id_prospecto', 'oportunidad_prospecto.id_prospecto')
                                                ->join('cat_fuentes', 'cat_fuentes.id_fuente', 'prospectos.fuente')
                                                ->where('status_oportunidad.id_cat_status_oportunidad', 2)
                                                ->where('oportunidades.created_at', '>=', $start_date)
                                                ->where('oportunidades.created_at', '<=', $end_date);
        
        if(!is_null($user_id)){
            $oportunidades_by_fuente  =  $oportunidades_by_fuente->where('colaborador_oportunidad.id_colaborador', $user_id);
        }

        
        $oportunidades_by_fuente =  $oportunidades_by_fuente->groupBy('cat_fuentes.id_fuente')
                                                            ->orderBy('total_oportunidades', 'DESC')
                                                            ->get();

        return $oportunidades_by_fuente;
    }
}

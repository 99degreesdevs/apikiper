<?php 

namespace App\Http\Controllers\DataViews;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Http\Requests;

use App\Modelos\Prospecto\Prospecto;
use App\Modelos\User;

use DB;
use Mail;

class DataViewsController extends Controller
{
    public function dashboard(){
        //Oportunidades Cotizadas
        //Oportunidades Cerradas
        //Prospectos sin contactar
        //Colaboradores
        //Ingresos
        //Origen Prospecto
        //Historial

        $oportuniades_cerradas = DB::table('oportunidades')
                                    ->join('status_oportunidad','oportunidades.id_oportunidad','status_oportunidad.id_oportunidad')
                                    ->select('oportunidades.*')->where('status_oportunidad.id_cat_status_oportunidad','=',2)->count();

        $oportunidades_cotizadas =  DB::table('oportunidades')
                                    ->join('status_oportunidad','oportunidades.id_oportunidad','status_oportunidad.id_oportunidad')
                                    ->select('oportunidades.*')->where('status_oportunidad.id_cat_status_oportunidad','=',1)->count();

        $colaboradores = DB::table('users')
                                ->join('colaborador_oportunidad','colaborador_oportunidad.id_colaborador','users.id')
                                ->join('status_oportunidad',function($join){
                                    $join->on('colaborador_oportunidad.id_oportunidad','=','status_oportunidad.id_oportunidad')
                                    ->where('status_oportunidad.id_cat_status_oportunidad','=',2);
                                })
                                ->join('detalle_oportunidad',function($join){
                                    $join->on('colaborador_oportunidad.id_oportunidad','=','detalle_oportunidad.id_oportunidad');
                                    
                                })->orderBy('detalle_oportunidad.valor','desc')->limit(5)->get();
                                

        $origen_prospecto = DB::table('prospectos')
                                ->select(DB::raw('count(*) as fuente_count, fuente'))->groupBy('fuente')->get();

        $prospectos_sin_contactar = DB::table('prospectos')
                                ->join('status_prospecto','prospectos.id_prospecto','status_prospecto.id_prospecto')
                                ->where('status_prospecto.id_cat_status_prospecto','=',1)->count();
        
                                
        return response()->json([
            'message'=>'Success',
            'error'=>false,
            'data'=>[
                'oportunidades_cerradas'=>$oportuniades_cerradas,
                'oportunidades_cotizadas'=>$oportunidades_cotizadas,
                'prospectos_sin_contactar'=>$prospectos_sin_contactar,
                'colaboradores'=>$colaboradores,
                'ingresos'=>'',
                'origen_prospecto'=>$origen_prospecto
            ]
        ]);

    }

    public function prospectos(){
        $total_prospectos = Prospecto::all()->count();
        $nocontactados_prospectos = DB::table('prospectos')
                                    ->join('status_prospecto','prospectos.id_prospecto','status_prospecto.id_prospecto')
                                    ->where('status_prospecto.id_cat_status_prospecto','=',1)->count();
        $prospectos_fuente = DB::table('prospectos')
                                    ->select(DB::raw('count(*) as fuente_count, fuente'))->groupBy('fuente')->get();
        
        $prospectos_t= DB::table('prospectos')
                            ->join('detalle_prospecto','prospectos.id_prospecto','detalle_prospecto.id_prospecto')
                            ->join('status_prospecto','prospectos.id_prospecto','status_prospecto.id_prospecto')
                            
                            ->select('prospectos.id_prospecto',
                                    'prospectos.nombre',
                                    'prospectos.apellido',
                                    'prospectos.correo',
                                    'detalle_prospecto.telefono',
                                    'prospectos.fuente',
                                    'prospectos.created_at')->get();
        
        $prospectos = Prospecto::with('detalle_prospecto')
                                ->with('status_prospecto.status')->get();

        return response()->json([
            'message'=>'Success',
            'error'=>false,
            'data'=>[
                'proespectos'=>$prospectos,
                'prospectos_total'=>$total_prospectos,
                'prospectos_nocontactados'=> $nocontactados_prospectos,
                'prospectos_fuente'=> $prospectos_fuente
            ]
        ]);
    }
}
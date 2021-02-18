<?php 

namespace App\Listeners;

use App\Events\NewAssigment;
use App\Modelos\User;
use App\Modelos\Prospecto\ColaboradorProspecto;
use App\Modelos\Prospecto\CatFuente;
use App\Modelos\Prospecto;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewLead;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Twilio\Rest\Client;
use Mailgun;
use DB;
use Illuminate\Support\Arr;

class Data{}
class NewAssigmentListener
{
    public function __construct()
    {
        $accountSid = env('TWILIO_ACCOUNT_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');
        $this->sendingNumber = env('TWILIO_NUMBER');

        $this->twilioClient = new Client($accountSid, $authToken);
    }

    public function handle($event) 
    {    
        $desarrollo = $event->evento['desarrollo'];
        $date = Carbon::now();
        // Kiper Landmark
        if ($desarrollo === 'landmark') {
            echo('Kiper Landmark'. PHP_EOL);
            $assigment_landmark = [
                'ejecutivo_0'=>[
                    'id'=>'4f0b2f78-c6a0-4bab-9054-ede329cb5ce9',
                    'nombre'=>'Berenice Iturbide',
                    'fechas' => [
                        // Viernes 19 Febrero 15 a 19
                        // Martes 23 Febrero 10 a 15
                        [Carbon::create(2021,2,19,15,0,1),Carbon::create(2021,2,19,19,0,0)],
                        [Carbon::create(2021,2,23,10,0,0),Carbon::create(2021,2,23,15,0,0)],
                    ]
                ],
                'ejecutivo_1' => [
                    'id'=>'3d7bff59-b743-4949-bb51-51716e73a942',
                    'nombre'=>'Álvaro Sainz',
                    'fechas'=>[
                        // Martes 16 Febrero 15 a 19
                        // Miercoles 24 Febrero 10 a 15
                        // Lunes 01 Marzo 15 a 19
                        [Carbon::create(2021,2,16,15,0,1),Carbon::create(2021,2,16,19,0,0)],
                        [Carbon::create(2021,2,24,10,0,0),Carbon::create(2021,2,24,15,0,0)],
                        [Carbon::create(2021,3,1,15,0,1),Carbon::create(2021,3,1,19,0,0)],
                    ]
                    
                ],
                'ejecutivo_2'=>[
                    'id'=>'d32f23d4-d56b-4ccd-a892-a51c5399ee80',
                    'nombre'=>'Daniel Mosqueira',
                    'fechas'=>[
                        // Martes 16 Febrero 10 a 15
                        // Jueves 25 Febrero 15 a 19
                        [Carbon::create(2021,2,16,10,0,0),Carbon::create(2021,2,16,15,0,0)],
                        [Carbon::create(2021,2,25,15,0,1),Carbon::create(2021,2,25,19,0,0)],
                    ]       
                ],
                'ejecutivo_3'=>[
                    'id'=>'6fba4841-31cf-40f8-af8e-7a5544b81557',
                    'nombre'=>'José Jaime Flores',
                    'fechas'=>[
                        // Lunes 15 Febrero 15 a 19
                        // Viernes 26 Febrero 10 a 15
                        [Carbon::create(2021,2,15,15,0,1),Carbon::create(2021,2,15,19,0,0)],
                        [Carbon::create(2021,2,26,10,0,0),Carbon::create(2021,2,26,15,0,0)],                    
                    ]       
                ],
                'ejecutivo_4'=>[
                    'id'=>'51b9ddd4-262d-4d96-a8f9-655b10a38b86',
                    'nombre'=>'Gloria Macias',
                    'fechas'=>[
                        // Miercoles 17 Ferbero 10 a 15
                        // Viernes 26 Febrero 15 a 19
                        [Carbon::create(2021,2,17,10,0,0),Carbon::create(2021,2,17,15,0,0)],
                        [Carbon::create(2021,2,26,15,0,1),Carbon::create(2021,2,26,19,0,0)],                            
                    ]       
                ], 
                'ejecutivo_5'=>[
                    'id'=>'820fccc7-f20a-4505-8848-874d3a5d0944',
                    'nombre'=>'Laura Flores',
                    'fechas'=>[
                        // Miercoles 17 Febrero 15 a 19
                        // Jueves 25 Febrero 10 a 15
                        [Carbon::create(2021,2,17,15,0,1),Carbon::create(2021,2,17,19,0,0)],
                        [Carbon::create(2021,2,25,10,0,0),Carbon::create(2021,2,25,15,0,0)],
                    ]       
                ],
                'ejecutivo_6'=>[
                    'id'=>'be1bbb44-8acf-41db-b61e-b1d53b7c8d42',
                    'nombre'=>'Mónica Sánchez',
                    'fechas'=>[
                        // Jueves 18 Febrero 10 a 15
                        // Miercoles 24 de Febrero 15 a 19
                        [Carbon::create(2021,2,18,10,0,0),Carbon::create(2021,2,18,15,0,0)],
                        [Carbon::create(2021,2,24,15,0,1),Carbon::create(2021,2,24,19,0,0)], 
                    ]       
                ],
                'ejecutivo_7'=>[
                    'id'=>'16ab8dac-4083-4b8a-8d39-2c923688c810',
                    'nombre'=>'José Rentería',
                    'fechas'=>[
                        // Jueves 18 de Febrero 15 a 19
                        // Lunes 22 Febrero 10 a 15
                        [Carbon::create(2021,2,18,15,0,1),Carbon::create(2021,2,18,19,0,0)],
                        [Carbon::create(2021,2,22,10,0,0),Carbon::create(2021,2,22,15,0,0)], 
                    ]       
                ],
                'ejecutivo_8'=>[
                    'id'=>'c6092094-bd54-445e-848d-4bd3fb79bd7e',
                    'nombre'=>'Mauricio Montaño',
                    'fechas'=>[
                        // Viernes 19 Ferebro 10 a 15
                        // Martes 23 Febrero 15 a 19
                        [Carbon::create(2021,2,19,10,0,0),Carbon::create(2021,2,19,15,0,0)],
                        [Carbon::create(2021,2,23,15,0,1),Carbon::create(2021,2,23,19,0,0)],
                    ]       
                ],
                'ejecutivo_9'=>[
                    'id'=>'a5b1ff68-8a09-4392-a517-57f1da9a61f0',
                    'nombre'=>'Patricia Rivera',
                    'fechas'=>[
                        // Lunes 15 Febrero 10 a 15
                        // Lunes 22 Febrero 15 a 19
                        // Lunes 01 Marzo 10 a 15
                        [Carbon::create(2021,2,15,10,0,0),Carbon::create(2021,2,15,15,0,0)],
                        [Carbon::create(2021,2,22,15,0,1),Carbon::create(2021,2,22,19,0,0)],
                        [Carbon::create(2021,3,1,10,0,0),Carbon::create(2021,3,1,15,0,0)],
                    ]       
                ],
            ];

            // Se realiza análisis de listado de ejecutivo vs fechas configuradas
            foreach($assigment_landmark as $index=>$item){
                ${"assigment_".$index} = $assigment_landmark[$index]['fechas'];
                foreach(${"assigment_".$index} as $key=>$value){
                    if($date->between(${"assigment_".$index}[$key][0],${"assigment_".$index}[$key][1],true)){
                        echo('Se asigno a: '.$assigment_landmark[$index]['nombre']. PHP_EOL);
                        $this->assign($assigment_landmark[$index]['id'], $event->evento['prospecto'],$desarrollo);
                    }
                }
            }
        } 
        // Kiper GFA
        else {
            echo('Kiper GFA');
            $polanco = [
                        'c3d94d64-e966-44a8-9a03-6ed97e79688b',
                        '5ba84206-494d-45d4-b186-4e2c19c4c5fb',
                        '09e78cf0-1bac-46ed-8945-5adb0f642840',
                        '776150e5-0f8f-414f-a987-9e4d52522105'
                    ];
            $napoles = [
                        'c3d94d64-e966-44a8-9a03-6ed97e79688b',
                        '5ba84206-494d-45d4-b186-4e2c19c4c5fb',
                        '09e78cf0-1bac-46ed-8945-5adb0f642840',
                        'fae9e0c4-78b5-478b-ba19-cf58a2593c21',
                        '776150e5-0f8f-414f-a987-9e4d52522105'
    
            ];
            $random_broker_p=Arr::random($polanco);
            $random_broker_n=Arr::random($napoles);
            if($desarrollo === 'polanco'){
                
                $prospectos_today = DB::table('prospectos')
                                ->join('etiquetas_prospectos','etiquetas_prospectos.id_prospecto','prospectos.id_prospecto')
                                ->join('etiquetas','etiquetas.id_etiqueta','etiquetas_prospectos.id_etiqueta')
                                ->where('etiquetas.nombre','like','%polanco%')
                                ->where('prospectos.deleted_at',null)
                                ->where('prospectos.fuente','!=',3)
                                ->groupBy('prospectos.id_prospecto')
                                ->get();
                if($prospectos_today->count() > 0){
                    $remainder = $prospectos_today->count() % 2;
                    echo 'Colaborador '.$random_broker_p.' ';
                    echo 'Total de leads '.$prospectos_today->count();
                    $this->assign($random_broker_p,$event->evento['prospecto'],$desarrollo);
                }
            }else if($desarrollo === 'napoles'){
                $prospectos_today = DB::table('prospectos')
                                ->join('etiquetas_prospectos','etiquetas_prospectos.id_prospecto','prospectos.id_prospecto')
                                ->join('etiquetas','etiquetas.id_etiqueta','etiquetas_prospectos.id_etiqueta')
                                ->where('etiquetas.nombre','like','%napoles%')
                                ->where('prospectos.deleted_at',null)
                                ->where('prospectos.fuente','!=',3)
                                ->groupBy('prospectos.id_prospecto')
                                ->get();
                if($prospectos_today->count() > 0){
                    $remainder = $prospectos_today->count() % 2;
                    echo 'Colaborador '.$random_broker_n.' ';
                    echo 'Total de leads '.$prospectos_today->count();
                    $this->assign($random_broker_n,$event->evento['prospecto'],$desarrollo);
                }
            }
        }
    }

    public function assign($id,$prospecto,$desarrollo)
    {
        $colaborador = User::where('id',$id)->first();
        $pivot_col_pros = new ColaboradorProspecto();
        $pivot_col_pros->id_colaborador = $colaborador->id;
        $pivot_col_pros->id_prospecto = $prospecto->id_prospecto;
        $pivot_col_pros->save();
        $prospecto->desarrollo = $desarrollo;
        $this->sendMail($id, $prospecto);
        //$this->sendSMS($id, $prospecto);
    }

    public function sendMail($id, $prospecto)
    {
        $data = new Data;
        $data->colaborador = User::where('id',$id)->first();
        $data->desarrollo = $prospecto->desarrollo;
        $data->prospecto = $prospecto;
        $data->fuente =CatFuente::find($data->prospecto->fuente);
        Mail::to($data->colaborador->email)->send(new NewLead($data));
    }

    // public function sendSMS($id,$prospecto){
    //     $colaborador = User::where('id',$id)->first();
    //         if(isset($colaborador->detalle) && $colaborador->detalle->celular){
    //             $this->twilioClient->messages->create(
    //             '+52'.$colaborador->detalle->celular,
    //             array(
    //                 "from" => $this->sendingNumber,
    //                 "body" => 'Kiper Leads | Nombre: '.$prospecto->nombre.' '.$prospecto->apellido.' Correo: '.$prospecto->correo.' Telefono: '.$prospecto->detalle_prospecto->telefono
    //             ));
    //         }
    // }
}
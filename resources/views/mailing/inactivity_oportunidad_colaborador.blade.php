
<html>
  Hola, {{$msg['colaborador']}}!,
  <br>
  <br>
  La oportunidad {{$msg['nombre_oportunidad']}} ha estado inactiva por mas de {{$msg['inactivity_period']}} horas.
  <br>
  <br>
  <a href="{{env('FRONT_END_URL')}}/#/detalle-oportunidad/{{$msg['id_oportunidad']}}">
    <button>
      Ver oportunidad
    </button>
  </a>
  <br>
  <br>
  -Kiper

</html>
                

<html>
  Hola, {{$msg['colaborador']}}!,
  <br>
  <br>
  El prospecto {{$msg['nombre_prospecto']}} ha estado inactivo por mas de {{$msg['inactivity_period']}} horas.
  <br>
  <br>
  <a href="{{env('FRONT_END_URL')}}/#/perfil-prospecto/{{$msg['id_prospecto']}}">
    <button>
      Ver prospecto
    </button>
  </a>
  <br>
  <br>
  -Kiper

</html>
                
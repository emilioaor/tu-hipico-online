----------------------------------
    SISTEMA TU HIPICO ONLINE
----------------------------------
{{ strtoupper($ticket->run->hippodrome->name) }}
TICKET: {{ $ticket->public_id }}
FECHA: {{ date_format($ticket->created_at, 'd-m-Y') }}
CARRERA: {{ strtoupper($ticket->run->public_id) }}
CAJA: {{ strtoupper($ticket->user->name) }}

DETALLE    N°/TAB  TOT/TAB   GAN
----------------------------------
@foreach($ticket->ticketDetails as $detail)
{{ substr($detail->horse->name, 0, 9) }}   {{ $detail->tables }}       {{ $detail->tables * $detail->horse->runs()->find($ticket->run_id)->pivot->static_table }}     {{ $detail->gain_amount }}
@endforeach
----------------------------------
SUBTOTAL           {{ $ticket->totalForTables() }}    {{ $ticket->totalForGains() }}
----------------------------------
TOTAL                  {{ $ticket->totalActiveAmount() }}

REVISE SU TICKET
VALIDO POR 5 DIAS


----------------------------------
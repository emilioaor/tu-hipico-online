<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte diario</title>
    <style>
        td {
            height: 30px;
        }
        h2 {
            margin-bottom: 5px!important;
        }
        h3 {
            margin: 0!important;
        }
        p {
            margin: 0!important;
        }
        table {
            margin-top: 15px!important;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <main>
        <h2>Sistema Tu Hipico Online</h2>
        <h3>Reporte del día</h3>
        <p><strong>Desde: </strong> {{ $start->format('d-m-Y') }}</p>
        <p><strong>Hasta: </strong> {{ $end->format('d-m-Y') }}</p>

        <table width="100%">
            <thead>
                <tr>
                    <th>Ticket</th>
                    <th>Creado</th>
                    <th>Estatus</th>
                    <th>Carrera</th>
                    <th>Taquilla</th>
                    <th style="text-align: center">Jugado</th>
                    <th style="text-align: center">Ganado</th>
                </tr>
            </thead>

            <tbody>
                @foreach($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->public_id }}</td>
                        <td>{{ date_format($ticket->created_at, 'd-m-Y h:m:s') }}</td>
                        <td>{{ $ticket->status }}</td>
                        <td>{{ $ticket->run->public_id }}</td>
                        <td>{{ $ticket->user->name }}</td>
                        <td style="text-align: center">{{ number_format($ticket->totalActiveAmount(), '2', ',', '.') }}</td>
                        <td style="text-align: center">{{ number_format($ticket->payAmount(), '2', ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <th>TOTAL</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: center">{{ number_format($total, '2', ',', '.') }}</th>
                    <th style="text-align: center">{{ number_format($totalPay, '2', ',', '.') }}</th>
                </tr>
                <tr>
                    <th>BALANCE</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th colspan="2" style="text-align: center">
                        {{ number_format($balance, '2', ',', '.') }}
                    </th>
                </tr>
            </tfoot>
        </table>

    </main>
</body>
</html>
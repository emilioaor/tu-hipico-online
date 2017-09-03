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
                    <th>Monto</th>
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
                        <td>{{ number_format($ticket->totalActiveAmount(), '2', ',', '.') }}</td>
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
                    <th>{{ number_format($total, '2', ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

    </main>
</body>
</html>
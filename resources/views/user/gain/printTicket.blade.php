@section('css')
    <style>
        @media print {

            @page {
                margin: 0;
                padding: 0;
                size: 75mm 130mm;
            }

            .print-hide {
                display: none !important;
            }

            .print-show {
                display: block !important;
            }

            .page-header {
                display: none;
            }

            .breadcrumb {
                display: none;
            }

            .btn {
                display: none !important;
            }

            #print-title {
                font-size: 200%;
                border-top: solid 1px;
                border-bottom: solid 1px;
            }

            #hippo {
                font-size: 150%;
            }

            #header-print p {
                margin : 0;
            }
            
            .table {
                margin-top: 20px;
            }

            .table td {
                height: 40px !important;
            }

            #footer-print {
                margin-top: 30px;
            }

            #footer-print p {
                margin : 0;
                font-size: 120%;
            }

            #total {
                font-size: 130%;
            }
        }

    </style>
@endsection

<div id="header-print" class="">
    <h1 id="print-title" class="text-center">Sistema Tu Hipico Online</h1>
    <p id="hippo"><strong>{{ strtoupper($ticket->run->hippodrome->name) }}</strong></p>
    <p><strong>TICKET:</strong> {{ $ticket->public_id }}</p>
    <p><strong>FECHA:</strong> {{ date_format($ticket->created_at, 'd-m-Y h:m') }}</p>
    <p><strong>CARRERA:</strong> {{ strtoupper($ticket->run->public_id) }}</p>
    <p><strong>CAJA:</strong> {{ strtoupper($ticket->user->name) }}</p>
</div>

<table class="table">
    <thead>
        <tr id="head">
            <th width="32%">CABALLO</th>
            <th width="17%" class="text-center">PRE/TAB</th>
            <th width="17%" class="text-center">CAN/TAB</th>
            <th width="17%" class="text-center">TOT/TAB</th>
            <th width="17%" class="text-center">GAN</th>
        </tr>
    </thead>
    <tbody id="body">
        @foreach($ticket->ticketDetails as $detail)
            <tr>
                <td width="32%">
                    @if($detail->status === App\TicketDetail::STATUS_ACTIVE)
                        {{ str_limit($detail->horse->name, 15) }}
                    @elseif($detail->status === App\TicketDetail::STATUS_NULL)
                        <del>{{ str_limit($detail->horse->name, 15) }}</del>
                    @endif
                </td>
                <td width="17%" class="text-center">
                    @if($detail->status === App\TicketDetail::STATUS_ACTIVE)
                        {{ $priceTable = $detail->horse->runs()->find($ticket->run_id)->pivot->static_table }}
                    @elseif($detail->status === App\TicketDetail::STATUS_NULL)
                        <del>{{ $priceTable = $detail->horse->runs()->find($ticket->run_id)->pivot->static_table }}</del>
                    @endif
                </td>
                <td width="17%" class="text-center">
                    @if($detail->status === App\TicketDetail::STATUS_ACTIVE)
                        {{ $detail->tables }}
                    @elseif($detail->status === App\TicketDetail::STATUS_NULL)
                        <del>{{ $detail->tables }}</del>
                    @endif
                </td>
                <td width="17%" class="text-center">
                    @if($detail->status === App\TicketDetail::STATUS_ACTIVE)
                        {{ $detail->tables * $priceTable }}
                    @elseif($detail->status === App\TicketDetail::STATUS_NULL)
                        <del>{{ $detail->tables * $priceTable }}</del>
                    @endif
                </td>
                <td width="17%" class="text-center">
                    @if($detail->status === App\TicketDetail::STATUS_ACTIVE)
                        {{ $detail->gain_amount }}
                    @elseif($detail->status === App\TicketDetail::STATUS_NULL)
                        <del>{{ $detail->gain_amount }}</del>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th width="66%" colspan="3">SUBTOTAL</th>
            <th width="17%" class="text-center">{{ number_format($ticket->totalForTables(), '2', ',', '.') }}</th>
            <th width="17%" class="text-center">{{ number_format($ticket->totalForGains(), '2', ',', '.') }}</th>
        </tr>
    </tfoot>
</table>

<table width="100%">
    <thead>
    <tr id="total">
        <th width="66%">TOTAL</th>
        <th width="34%" class="text-center">{{ number_format($ticket->totalActiveAmount(), '2', ',', '.') }}</th>
    </tr>
    </thead>
</table>

<div id="footer-print">
    <p>REVISE SU TICKET</p>
    <p>VALIDO POR 5 DIAS</p>
</div>

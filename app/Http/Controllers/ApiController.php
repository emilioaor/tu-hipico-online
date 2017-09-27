<?php

namespace App\Http\Controllers;

use App\PrintSpooler;
use App\User;
use Illuminate\Http\Request;
use App\Ticket;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{

    /**
     * Obtiene el ticket en texto plano para imprimir
     *
     * @param $ticketCode
     * @return $this
     */
    public function getTicketText($ticketCode) {
        $ticketId = explode('-', $ticketCode);

        if ($ticketId[0] == 'HIP') {
            $ticket = Ticket::find($ticketId[1]);

            return view('text.ticket')->with(['ticket' => $ticket]);
        } elseif ($ticketId[0] == 'ANI') {

            //  Consultar contenido del ticket en el sistema de animalitos
            $ch = curl_init("http://animalito.local/api/$ticketCode/ticketText"); //url hacia la api de animalito

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

            $apiResponse = curl_exec($ch);

            curl_close($ch);

            if($apiResponse) {
                return $apiResponse;
            }
        }

        return new Response(null, 400);
    }


    /**
     * Obtiene todos los tickets en cola de impresion
     * para una taquilla especifica
     *
     * @param $printCode
     * @return JsonResponse
     */
    public function getPrintSpooler($printCode) {

        $user = User::where('print_code', $printCode)->first();

        $pendingTickets = PrintSpooler::select('print_spooler.id')
            ->join('tickets', 'tickets.id', '=', 'ticket_id')
            ->where('user_id', $user ? $user->id : null)
            ->where('print_spooler.status', PrintSpooler::STATUS_PENDING)
            ->get()
        ;

        $response = null;

        foreach ($pendingTickets as $id) {

            if (is_null($response)) {
                $response = '';
            }

            $printSpooler = PrintSpooler::find($id['id']);
            $printSpooler->status = PrintSpooler::STATUS_COMPLETE;
            //$printSpooler->save();

            $response .= 'HIP-' . $printSpooler->ticket_id . "\n";
        }

        //  Consultar los tickets en el sistema de animalitos
        $ch = curl_init("http://animalito.local/api/$printCode/printSpooler"); //url hacia la api de animalito

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        $apiResponse = curl_exec($ch);

        curl_close($ch);

        if($apiResponse) {
            $response .= $apiResponse;
        }

        return $response;
    }
}

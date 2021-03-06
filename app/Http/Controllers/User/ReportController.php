<?php

namespace App\Http\Controllers\User;

use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PDF;

class ReportController extends Controller
{

    /**
     * Carga vista principal para reporte diario
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        return view('user.report.daily.index');
    }


    /**
     * Genera reporte diario en pdf
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateDailyReport(Request $request) {

        if (empty($request->date_start) || empty($request->date_end)) {
            $this->sessionMessage('Debe especificar fecha de inicio y fin', 'alert-danger');
            return redirect()->route('user.report.daily');
        }

        $dateStart = new \DateTime($request->date_start);
        $dateEnd = new \DateTime($request->date_end);

        $dateStart->setTime(00,00,00);
        $dateEnd->setTime(23,59,59);

        $tickets = Ticket::where('created_at', '>=', $dateStart)
            ->where('created_at', '<=', $dateEnd)
            ->orderBy('created_at', 'DESC')
        ;

        if (Auth::user()->level !== User::LEVEL_ADMIN) {
            $tickets->where('user_id', Auth::user()->id);
        }

        $tickets = $tickets->get();
        $total = $totalPay = 0;
        foreach ($tickets as $ticket) {
            $total += $ticket->totalActiveAmount();
            $totalPay += $ticket->payAmount();
        }
        $balance = $total - $totalPay;

        $pdf = PDF::loadView('pdf.dailyReport', [
            'tickets' => $tickets,
            'start' => $dateStart,
            'end' => $dateEnd,
            'total' => $total,
            'totalPay' => $totalPay,
            'balance' => $balance,
        ])
            ->setPaper('a4', 'landscape');

        return $pdf->stream();
    }
}

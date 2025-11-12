<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminReportController extends Controller
{
    protected function ensureAdmin()
    {
        if (! auth()->check() || ! in_array(auth()->user()->role, ['admin','manager'])) {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        $this->ensureAdmin();

        $start = $request->query('start');
        $end = $request->query('end');

        $reservationsQuery = Reservation::with(['user','venue','event'])->orderByDesc('start_time');
        if ($start) { $reservationsQuery->where('start_time', '>=', $start); }
        if ($end) { $reservationsQuery->where('end_time', '<=', $end); }
        $reservations = $reservationsQuery->get();

        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin.reports.index', compact('users', 'reservations', 'start', 'end'));
    }

    public function exportCsv(Request $request)
    {
        $this->ensureAdmin();
        $dataset = $request->query('dataset', 'reservations');
        $filename = $dataset.'-report-'.now()->format('Ymd_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($dataset) {
            $out = fopen('php://output', 'w');

            if ($dataset === 'users') {
                fputcsv($out, ['User ID','Name','Email','Role','Created']);
                foreach (User::orderBy('user_id')->get() as $u) {
                    fputcsv($out, [$u->user_id, $u->name, $u->email, $u->role, optional($u->created_at)->toDateTimeString()]);
                }
            } else { // reservations
                fputcsv($out, ['Reservation ID','User','Venue','Event','Start','End','Status','Total (KSh)']);
                $rows = Reservation::with(['user','venue','event'])->orderBy('reservation_id')->get();
                foreach ($rows as $r) {
                    fputcsv($out, [
                        $r->reservation_id,
                        optional($r->user)->name,
                        optional($r->venue)->venue_name,
                        optional($r->event)->event_name,
                        optional($r->start_time)->toDateTimeString(),
                        optional($r->end_time)->toDateTimeString(),
                        $r->status,
                        number_format((float)($r->total_cost ?? 0), 2),
                    ]);
                }
            }

            fclose($out);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $this->ensureAdmin();

        $reservations = Reservation::with(['user','venue','event'])->orderBy('start_time')->get();
        $users = User::orderBy('name')->get();

        // Try Dompdf via barryvdh/laravel-dompdf if available
        if (class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.pdf', compact('users','reservations'))
                ->setPaper('a4', 'portrait');
            $filename = 'report-'.now()->format('Ymd_His').'.pdf';
            return $pdf->download($filename);
        }

        // Fallback: show printable HTML if PDF library is not installed
        return view('admin.reports.pdf', compact('users','reservations'))
            ->with('pdfFallback', true);
    }
}

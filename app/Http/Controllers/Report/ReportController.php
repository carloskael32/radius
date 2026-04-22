<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Rdacct\Radacct;
use App\Models\Rdacct\Radpostauth;
use App\Models\Client\Client;
use App\Models\Rcheck\Radgroupreply;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

            // datos para la tabla de reportes
        $clients = Client::orderBy('id', 'desc')->get();
        $rgreply = Radgroupreply::where('groupname', '!=', 'inactivo')->get();


        $bandwidthData = $this->getBandwidthConsumption();
        $topConsumers = $this->getTopConsumers();
        $monthlyComparison = $this->getMonthlyComparison();
        $disconnectionData = $this->getDisconnectionAnalysis();
        $activeSessionsCount = $this->getActiveSessionsCount();
        $failedAuthCount = $this->getFailedAuthCount();
        $nasStats = $this->getNasStats();

        return Inertia::render("Report/Index", [
            'bandwidthData' => $bandwidthData,
            'topConsumers' => $topConsumers,
            'monthlyComparison' => $monthlyComparison,
            'disconnectionData' => $disconnectionData,
            'activeSessionsCount' => $activeSessionsCount,
            'failedAuthCount' => $failedAuthCount,
            'nasStats' => $nasStats,
            'clients' => $clients,
            'grupos' => $rgreply,
        ]);
    }

    /**
     * Obtener consumo de ancho de banda total
     */
    public function getBandwidthConsumption()
    {
        $totalInput = Radacct::sum('acctinputoctets', Carbon::today()) ?? 0;
        $totalOutput = Radacct::sum('acctoutputoctets', Carbon::today()) ?? 0;

        return [
            'totalInput' => $this->formatBytes($totalInput),
            'totalInputBytes' => $totalInput,
            'totalOutput' => $this->formatBytes($totalOutput),
            'totalOutputBytes' => $totalOutput,
            'combined' => $this->formatBytes($totalInput + $totalOutput),
            'combinedBytes' => $totalInput + $totalOutput,
        ];
    }

    /**
     * Obtener estadísticas de ancho de banda por usuario (diario, semanal, mensual)
     * y por rango de fechas.
     */
    public function getUserBandwidthStats(Request $request)
    {
        $username = $request->get('username', null);
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Radacct::select(
            DB::raw('username'),
            DB::raw('SUM(acctinputoctets) as total_input'),
            DB::raw('SUM(acctoutputoctets) as total_output'),
            DB::raw('COUNT(*) as sessions')
        );

        if ($startDate && $endDate) {
            $query->whereBetween('acctstarttime', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        } elseif ($startDate) {
            $query->where('acctstarttime', '>=', Carbon::parse($startDate)->startOfDay());
        } elseif ($endDate) {
            $query->where('acctstarttime', '<=', Carbon::parse($endDate)->endOfDay());
        }

        if ($username) {
            $query->where('username', $username);
        }

        $stats = $query->groupBy('username')
            ->orderBy('total_output', 'DESC')
            ->get()
            ->map(function ($stat) {
                return [
                    'username' => $stat->username,
                    'input' => $this->formatBytes($stat->total_input),
                    'inputBytes' => $stat->total_input,
                    'output' => $this->formatBytes($stat->total_output),
                    'outputBytes' => $stat->total_output,
                    'combined' => $this->formatBytes($stat->total_input + $stat->total_output),
                    'combinedBytes' => $stat->total_input + $stat->total_output,
                    'sessions' => $stat->sessions,
                ];
            });

        return response()->json([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'username' => $username,
            'data' => $stats,
        ]);
    }

    /**
     * Obtener estadísticas de desconexiones con filtros
     */
    public function getDisconnectionStats(Request $request)
    {
        $username = $request->get('username');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Radacct::select(
            'username',
            'acctstoptime as disconnect_time',
            'acctterminatecause',
            'nasipaddress',
            DB::raw('UNIX_TIMESTAMP(acctstoptime) - UNIX_TIMESTAMP(acctstarttime) as session_time_seconds')
        )
            ->whereNotNull('acctstoptime');

        if ($startDate && $endDate) {
            $query->whereBetween('acctstoptime', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        } elseif ($startDate) {
            $query->where('acctstoptime', '>=', Carbon::parse($startDate)->startOfDay());
        } elseif ($endDate) {
            $query->where('acctstoptime', '<=', Carbon::parse($endDate)->endOfDay());
        }

        if ($username) {
            $query->where('username', $username);
        }

        $disconnections = $query->orderBy('acctstoptime', 'DESC')
            ->limit(1000) // Limitar resultados para performance
            ->get()
            ->map(function ($disconnection) {
                return [
                    'id' => $disconnection->disconnect_time . '-' . $disconnection->username, // ID único
                    'username' => $disconnection->username,
                    'disconnect_time' => $disconnection->disconnect_time,
                    'acctterminatecause' => $disconnection->acctterminatecause,
                    'session_time' => $this->formatSeconds($disconnection->session_time_seconds ?? 0),
                    'nasipaddress' => $disconnection->nasipaddress,
                ];
            });

        return response()->json([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'username' => $username,
            'data' => $disconnections,
        ]);
    }

    /**
     * Obtener Top 10 usuarios que más consumen (últimos 7 días)
     */
    public function getTopConsumers()
    {
        $topUsers = Radacct::select(
            DB::raw('username'),
            DB::raw('SUM(acctinputoctets) as total_input'),
            DB::raw('SUM(acctoutputoctets) as total_output'),
            DB::raw('COUNT(*) as sessions'),
            DB::raw('MAX(acctstarttime) as last_session')
        )
            ->groupBy('username')
            ->whereBetween('acctstarttime', [
                Carbon::now()->subDays(7)->startOfDay(),
                Carbon::now()->endOfDay(),
            ])
            ->orderBy('total_output', 'DESC')
            ->limit(10)
            ->get()
            ->map(function ($user, $index) {
                $client = Client::where('username', $user->username)->first();
                return [
                    'rank' => $index + 1,
                    'username' => $user->username,
                    'fullName' => $client?->nombre_completo ?? 'N/A',
                    'input' => $this->formatBytes($user->total_input),
                    'inputBytes' => $user->total_input,
                    'output' => $this->formatBytes($user->total_output),
                    'outputBytes' => $user->total_output,
                    'combined' => $this->formatBytes($user->total_input + $user->total_output),
                    'combinedBytes' => $user->total_input + $user->total_output,
                    'sessions' => $user->sessions,
                    'lastSession' => $user->last_session,
                ];
            });

        return $topUsers;
    }

    /**
     * Obtener comparativo de consumo mes a mes
     */
    public function getMonthlyComparison()
    {
        $comparison = Radacct::select(
            DB::raw('YEAR(acctstarttime) as year'),
            DB::raw('MONTH(acctstarttime) as month'),
            DB::raw('SUM(acctinputoctets) as total_input'),
            DB::raw('SUM(acctoutputoctets) as total_output'),
            DB::raw('COUNT(*) as sessions'),
            DB::raw('COUNT(DISTINCT username) as unique_users')
        )
            ->whereNotNull('acctstarttime')
            ->groupBy('year', 'month')
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->limit(12)
            ->get()
            ->map(function ($record) {
                $date = Carbon::createFromDate($record->year, $record->month, 1);
                return [
                    'period' => $date->format('M Y'),
                    'month' => $record->month,
                    'year' => $record->year,
                    'input' => $this->formatBytes($record->total_input),
                    'inputBytes' => $record->total_input,
                    'output' => $this->formatBytes($record->total_output),
                    'outputBytes' => $record->total_output,
                    'combined' => $this->formatBytes($record->total_input + $record->total_output),
                    'combinedBytes' => $record->total_input + $record->total_output,
                    'sessions' => $record->sessions,
                    'uniqueUsers' => $record->unique_users,
                ];
            })
            ->reverse()
            ->values();

        return $comparison;
    }

    /**
     * Obtener análisis de desconexiones
     */
    public function getDisconnectionAnalysis()
    {
        // Causas de corte hoy
        $disconnectReasons = Radacct::select(
            DB::raw('acctterminatecause'),
            DB::raw('COUNT(*) as count')
        )
            ->whereNotNull('acctterminatecause')
            ->whereDate('acctstoptime', Carbon::today())
            ->groupBy('acctterminatecause')
            ->orderBy('count', 'DESC')
            ->get();

        // Usuarios que se desconectan más veces en el día
        $topDisconnectors = Radacct::select(
            'username',
            DB::raw('COUNT(*) as disconnections'),
            DB::raw('AVG(UNIX_TIMESTAMP(acctstoptime) - UNIX_TIMESTAMP(acctstarttime)) as avg_session_time')
        )
            ->whereDate('acctstarttime', Carbon::today())
            ->whereNotNull('acctstoptime')
            ->groupBy('username')
            ->orderBy('disconnections', 'ASC')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                $client = Client::where('username', $user->username)->first();
                return [
                    'username' => $user->username,
                    'fullName' => $client?->nombre_completo ?? 'N/A',
                    'disconnections' => $user->disconnections,
                    'avgSessionTime' => $this->formatSeconds($user->avg_session_time ?? 0),
                    'avgSessionSeconds' => $user->avg_session_time ?? 0,
                ];
            });

        // Tiempo promedio de sesión por usuario
        $sessionStats = Radacct::select(
            'username',
            DB::raw('COUNT(*) as total_sesiones'),
            DB::raw('AVG(UNIX_TIMESTAMP(acctstoptime) - UNIX_TIMESTAMP(acctstarttime)) as avg_session_time'),
            DB::raw('MIN(UNIX_TIMESTAMP(acctstoptime) - UNIX_TIMESTAMP(acctstarttime)) as min_session_time'),
            DB::raw('MAX(UNIX_TIMESTAMP(acctstoptime) - UNIX_TIMESTAMP(acctstarttime)) as max_session_time')
        )
            ->whereNotNull('acctstoptime')
            ->groupBy('username')
            ->orderBy('avg_session_time', 'DESC')
            ->limit(10)
            ->get()
            ->map(function ($stat) {
                $client = Client::where('username', $stat->username)->first();
                return [
                    'username' => $stat->username,
                    'fullName' => $client?->nombre_completo ?? 'N/A',
                    'totalSessions' => $stat->total_sesiones,
                    'avgSessionTime' => $this->formatSeconds($stat->avg_session_time ?? 0),
                    'minSessionTime' => $this->formatSeconds($stat->min_session_time ?? 0),
                    'maxSessionTime' => $this->formatSeconds($stat->max_session_time ?? 0),
                ];
            });

        return [
            'disconnectReasons' => $disconnectReasons,
            'topDisconnectors' => $topDisconnectors,
            'sessionStats' => $sessionStats,
        ];
    }

    /**
     * Obtener sesiones PPPoE activas
     */
    public function getActiveSessionsCount()
    {
        $activeSessions = Radacct::select(
            'username',
            'acctsessionid',
            'nasipaddress',
            'framedipaddress',
            'acctstarttime',
            DB::raw('UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(acctstarttime) as session_duration')
        )
            ->whereNull('acctstoptime')
            ->orderBy('acctstarttime', 'DESC')
            ->limit(50)
            ->get()
            ->map(function ($session) {
                $client = Client::where('username', $session->username)->first();
                return [
                    'username' => $session->username,
                    'fullName' => $client?->nombre_completo ?? 'N/A',
                    'sessionId' => $session->acctsessionid,
                    'nasIp' => $session->nasipaddress,
                    'clientIp' => $session->framedipaddress,
                    'startTime' => $session->acctstarttime,
                    'duration' => $this->formatSeconds($session->session_duration),
                    'durationSeconds' => $session->session_duration,
                ];
            });

        return [
            'count' => count($activeSessions),
            'sessions' => $activeSessions,
        ];
    }

    /**
     * Obtener historial de conexiones por usuario
     */
    public function getUserConnectionHistory(Request $request)
    {
        $username = $request->get('username');
        $limit = $request->get('limit', 50);

        $history = Radacct::where('username', $username)
            ->select(
                'radacctid',
                'username',
                'acctsessionid',
                'nasipaddress',
                'framedipaddress',
                'acctstarttime',
                'acctstoptime',
                'acctterminatecause',
                'acctinputoctets',
                'acctoutputoctets',
                DB::raw('UNIX_TIMESTAMP(acctstoptime) - UNIX_TIMESTAMP(acctstarttime) as session_duration')
            )
            ->whereNotNull('acctstoptime')
            ->orderBy('acctstarttime', 'DESC')
            ->limit($limit)
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->radacctid,
                    'sessionId' => $session->acctsessionid,
                    'nasIp' => $session->nasipaddress,
                    'clientIp' => $session->framedipaddress,
                    'startTime' => $session->acctstarttime,
                    'stopTime' => $session->acctstoptime,
                    'duration' => $this->formatSeconds($session->session_duration),
                    'cause' => $session->acctterminatecause,
                    'input' => $this->formatBytes($session->acctinputoctets),
                    'output' => $this->formatBytes($session->acctoutputoctets),
                ];
            });

        return response()->json([
            'username' => $username,
            'total' => count($history),
            'data' => $history,
        ]);
    }

    /**
     * Obtener intentos fallidos de autenticación
     */
    public function getFailedAuthAttempts(Request $request)
    {
        $days = $request->get('days', 7);
        $limit = $request->get('limit', 50);

        $failedAttempts = Radpostauth::select(
            'username',
            'reply',
            'authdate',
            DB::raw('COUNT(*) as attempts')
        )
            ->where('reply', 'Reject')
            ->whereBetween('authdate', [
                Carbon::now()->subDays($days),
                Carbon::now()
            ])
            ->groupBy('username', 'reply', 'authdate')
            ->orderBy('authdate', 'DESC')
            ->limit($limit)
            ->get();

        // Usuarios con más intentos fallidos
        $topFailed = Radpostauth::select(
            'username',
            DB::raw('COUNT(*) as failed_attempts')
        )
            ->where('reply', 'Reject')
            ->whereBetween('authdate', [
                Carbon::now()->subDays($days),
                Carbon::now()
            ])
            ->groupBy('username')
            ->orderBy('failed_attempts', 'DESC')
            ->limit(10)
            ->get();

        return response()->json([
            'period_days' => $days,
            'failed_attempts' => $failedAttempts,
            'top_failed_users' => $topFailed,
        ]);
    }

    /**
     * Obtener recuento de intentos fallidos
     */
    public function getFailedAuthCount()
    {
        $today = Radpostauth::where('reply', 'Reject')
            ->whereDate('authdate', Carbon::today())
            ->count();

        $yesterday = Radpostauth::where('reply', 'Reject')
            ->whereDate('authdate', Carbon::yesterday())
            ->count();

        return [
            'today' => $today,
            'yesterday' => $yesterday,
        ];
    }

    /**
     * Obtener sesiones simultáneas por usuario
     */
    public function getSimultaneousSessions()
    {
        $simultaneousSessions = Radacct::select(
            'username',
            DB::raw('COUNT(*) as active_sessions')
        )
            ->whereNull('acctstoptime')
            ->groupBy('username')
            ->having(DB::raw('COUNT(*)'), '>', 1)
            ->orderBy('active_sessions', 'DESC')
            ->get();

        return response()->json([
            'count' => count($simultaneousSessions),
            'data' => $simultaneousSessions,
        ]);
    }

    /**
     * Obtener nuevos clientes registrados esta semana
     */
    public function getNewClientsThisWeek()
    {
        $newClients = Client::where('created_at', '>=', Carbon::now()->startOfWeek())
            ->select('username', 'nombre_completo', 'email', 'telefono', 'created_at')
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json([
            'count' => count($newClients),
            'data' => $newClients,
        ]);
    }

    /**
     * Obtener clientes con estado inactivo
     */
    public function getInactiveClients()
    {
        $inactiveClients = Client::where('estado', 'inactivo')
            ->select('username', 'nombre_completo', 'email', 'telefono', 'estado', 'created_at')
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json([
            'count' => count($inactiveClients),
            'data' => $inactiveClients,
        ]);
    }

    /**
     * Obtener clientes sin plan asignado
     */
    public function getClientsWithoutPlan()
    {
        $clientsWithoutPlan = Client::where(function ($query) {
            $query->whereNull('plan')
                ->orWhere('plan', '');
        })
            ->select('username', 'nombre_completo', 'email', 'telefono', 'plan', 'created_at')
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json([
            'count' => count($clientsWithoutPlan),
            'data' => $clientsWithoutPlan,
        ]);
    }

    /**
     * Obtener estadísticas de NAS/Nodos
     */
    public function getNasStats()
    {
        $nasStats = Radacct::select(
            'nasipaddress',
            DB::raw('COUNT(*) as total_sessions'),
            DB::raw('COUNT(DISTINCT username) as unique_users'),
            DB::raw('SUM(acctinputoctets) as total_input'),
            DB::raw('SUM(acctoutputoctets) as total_output')
        )
            ->whereNotNull('nasipaddress')
            ->groupBy('nasipaddress')
            ->orderBy('total_sessions', 'DESC')
            ->get()
            ->map(function ($nas) {
                return [
                    'nasIp' => $nas->nasipaddress,
                    'totalSessions' => $nas->total_sessions,
                    'uniqueUsers' => $nas->unique_users,
                    'totalInput' => $this->formatBytes($nas->total_input),
                    'totalOutput' => $this->formatBytes($nas->total_output),
                    'combined' => $this->formatBytes($nas->total_input + $nas->total_output),
                ];
            });

        // NAS con más desconexiones inesperadas
        $nasDisconnections = Radacct::select(
            'nasipaddress',
            DB::raw('COUNT(*) as unexpected_disconnects')
        )
            ->whereIn('acctterminatecause', ['Lost-Carrier', 'Connection-Lost'])
            ->groupBy('nasipaddress')
            ->orderBy('unexpected_disconnects', 'DESC')
            ->limit(5)
            ->get();

        return [
            'stats' => $nasStats,
            'disconnections' => $nasDisconnections,
        ];
    }

    /**
     * Obtener NAS por usuario conectado
     */
    public function getNasStatsByUser(Request $request)
    {
        $username = $request->get('username');

        if (!$username) {
            return response()->json(['data' => []]);
        }

        $nasByUser = Radacct::select(
            'nasipaddress',
            DB::raw('SUM(acctinputoctets) as total_input'),
            DB::raw('SUM(acctoutputoctets) as total_output'),
            DB::raw('COUNT(*) as sessions')
        )
            ->where('username', $username)
            ->whereNotNull('nasipaddress')
            ->groupBy('nasipaddress')
            ->orderBy('total_output', 'DESC')
            ->get()
            ->map(function ($nas) use ($username) {
                return [
                    'nasIp' => $nas->nasipaddress,
                    'username' => $username,
                    'totalInput' => $this->formatBytes($nas->total_input),
                    'totalOutput' => $this->formatBytes($nas->total_output),
                    'combined' => $this->formatBytes($nas->total_input + $nas->total_output),
                    'sessions' => $nas->sessions,
                ];
            });

        return response()->json(['data' => $nasByUser]);
    }

    /**
     * Convertir bytes a formato legible
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Convertir segundos a formato legible (HH:MM:SS)
     */
    private function formatSeconds($seconds)
    {
        if (!$seconds) return '0s';

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        $result = [];
        if ($hours > 0) $result[] = $hours . 'h';
        if ($minutes > 0) $result[] = $minutes . 'm';
        if ($secs > 0) $result[] = $secs . 's';

        return implode(' ', $result);
    }

    /**
     * Exportar tabla a Excel
     */
    public function exportToExcel(Request $request)
    {
        $reportType = $request->get('type');
        $data = [];
        $filename = '';

        switch ($reportType) {
            case 'bandwidth':
                $data = $this->prepareBandwidthExcelData($request);
                $filename = 'reporte_ancho_banda_' . date('Y-m-d') . '.xlsx';
                break;
            case 'top_consumers':
                $data = $this->getTopConsumers();
                $filename = 'reporte_top_usuarios_' . date('Y-m-d') . '.xlsx';
                break;
            case 'disconnections':
                $disconnResponse = $this->getDisconnectionStats($request);
                $data = $disconnResponse->getData()->data ?? [];
                $filename = 'reporte_desconexiones_' . date('Y-m-d') . '.xlsx';
                break;
            case 'active_sessions':
                $active = $this->getActiveSessionsCount();
                $data = $active['sessions'] ?? [];
                $filename = 'reporte_sesiones_activas_' . date('Y-m-d') . '.xlsx';
                break;
            case 'failed_auth':
                $data = Radpostauth::where('reply', 'Reject')
                    ->whereDate('authdate', Carbon::today())
                    ->orderBy('authdate', 'DESC')
                    ->get();
                $filename = 'reporte_auth_fallidas_' . date('Y-m-d') . '.xlsx';
                break;
            case 'nas_stats':
                $data = $this->prepareNasStatsExcelData();
                $filename = 'reporte_nas_' . date('Y-m-d') . '.xlsx';
                break;
            case 'nas_individual':
                $data = $this->prepareNasIndividualExcelData($request);
                $filename = 'reporte_nas_individual_' . date('Y-m-d') . '.xlsx';
                break;
        }

        return $this->generateExcelFile($data, $filename);
    }

    /**
     * Preparar datos de ancho de banda para Excel
     */
    private function prepareBandwidthExcelData(Request $request)
    {
        $username = $request->get('username', null);
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Radacct::select(
            DB::raw('username'),
            DB::raw('SUM(acctinputoctets) as total_input'),
            DB::raw('SUM(acctoutputoctets) as total_output'),
            DB::raw('COUNT(*) as sessions')
        );

        if ($startDate && $endDate) {
            $query->whereBetween('acctstarttime', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        } elseif ($startDate) {
            $query->where('acctstarttime', '>=', Carbon::parse($startDate)->startOfDay());
        } elseif ($endDate) {
            $query->where('acctstarttime', '<=', Carbon::parse($endDate)->endOfDay());
        }

        if ($username) {
            $query->where('username', $username);
        }

        return $query->groupBy('username')
            ->orderBy('total_output', 'DESC')
            ->get()
            ->map(function ($stat) {
                return [
                    'Usuario' => $stat->username,
                    'Descargado' => $this->formatBytes($stat->total_input),
                    'DescargadoBytes' => $stat->total_input,
                    'Subido' => $this->formatBytes($stat->total_output),
                    'SubidoBytes' => $stat->total_output,
                    'TotalCombinado' => $this->formatBytes($stat->total_input + $stat->total_output),
                    'TotalCombinadoBytes' => $stat->total_input + $stat->total_output,
                    'Sesiones' => $stat->sessions,
                ];
            })
            ->toArray();
    }

    /**
     * Preparar datos de estadísticas NAS para Excel
     */
    private function prepareNasStatsExcelData()
    {
        return Radacct::select(
            'nasipaddress',
            'username',
            DB::raw('SUM(acctinputoctets) as total_input'),
            DB::raw('SUM(acctoutputoctets) as total_output')
        )
            ->whereNotNull('nasipaddress')
            ->groupBy('nasipaddress', 'username')
            ->orderBy('nasipaddress')
            ->orderBy('total_output', 'DESC')
            ->get()
            ->map(function ($item) {
                return [
                    'IP NAS' => $item->nasipaddress,
                    'Usuario' => $item->username,
                    'Descargado' => $this->formatBytes($item->total_input),
                    'Subido' => $this->formatBytes($item->total_output),
                    'Total' => $this->formatBytes($item->total_input + $item->total_output),
                ];
            })
            ->toArray();
    }

    /**
     * Preparar datos de NAS individual para Excel
     */
    private function prepareNasIndividualExcelData(Request $request)
    {
        $nasIp = $request->get('nas_ip');

        if (!$nasIp) {
            return [];
        }

        return Radacct::select(
            'nasipaddress',
            'username',
            DB::raw('SUM(acctinputoctets) as total_input'),
            DB::raw('SUM(acctoutputoctets) as total_output')
        )
            ->where('nasipaddress', $nasIp)
            ->groupBy('nasipaddress', 'username')
            ->orderBy('total_output', 'DESC')
            ->get()
            ->map(function ($item) {
                return [
                    'IP NAS' => $item->nasipaddress,
                    'Usuario' => $item->username,
                    'Descargado' => $this->formatBytes($item->total_input),
                    'Subido' => $this->formatBytes($item->total_output),
                    'Total' => $this->formatBytes($item->total_input + $item->total_output),
                ];
            })
            ->toArray();
    }

    /**
     * Exportar tabla a PDF
     */
    public function exportToPdf(Request $request)
    {
        $reportType = $request->get('type');
        $title = $request->get('title', 'Reporte RADIUS');
        $data = [];

        switch ($reportType) {
            case 'bandwidth':
                $data = $this->prepareBandwidthExcelData($request);
                break;
            case 'top_consumers':
                $data = $this->getTopConsumers();
                break;
            case 'disconnections':
                $disconn = $this->getDisconnectionAnalysis();
                $data = $disconn['topDisconnectors'] ?? [];
                break;
            case 'active_sessions':
                $active = $this->getActiveSessionsCount();
                $data = $active['sessions'] ?? [];
                break;
            case 'nas_stats':
                $nas = $this->getNasStats();
                $data = $nas['stats'] ?? [];
                break;
        }

        return response()->json([
            'message' => 'PDF export initiated',
            'data' => $data,
            'type' => $reportType,
        ]);
    }

    /**
     * Generar archivo Excel
     */
    private function generateExcelFile($data, $filename)
    {
        // Por ahora retornamos JSON, la exportación real se hace desde el frontend
        // usando alguna librería como SheetJS
        return response()->json([
            'success' => true,
            'data' => $data,
            'filename' => $filename,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

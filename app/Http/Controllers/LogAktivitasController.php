<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogAktivitas as LogAktivitasModel;
use Illuminate\Support\Facades\Auth;

class LogAktivitasController extends Controller
{
     /**
      * Display a listing of activity logs
      */
     public function index()
     {
          $logs = LogAktivitasModel::orderBy('created_at', 'desc')->get();
          return view('log-aktivitas.index', compact('logs'));
     }

     /**
      * Store a new activity log
      */
     public function store($activity, $description)
     {
          LogAktivitasModel::create([
               'user_id' => Auth::id(),
               'activity' => $activity,
               'description' => $description,
               'ip_address' => request()->ip(),
               'user_agent' => request()->userAgent()
          ]);
     }

     /**
      * Delete activity log
      */
     public function destroy($id)
     {
          $log = LogAktivitasModel::findOrFail($id);
          $log->delete();

          return redirect()->back()->with('success', 'Activity log deleted successfully');
     }
}

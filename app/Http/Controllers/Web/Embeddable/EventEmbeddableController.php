<?php

namespace App\Http\Controllers\Web\Embeddable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventEmbeddableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function latestEvents(Request $request)
    {
        $tenant = $request->input('tenant');
        $start_date = $request->input('start_date');
        $finish_date = $request->input('finish_date');

        $last_month = now()->subMonth(1);
        $events = Event::when($tenant, function($query) use ($tenant) {
                        return $query->where('tenant_id', $tenant);
                    })
                    ->when($start_date, function($query) use ($start_date) {
                        return $query->where('created_at', '>=' , $start_date);
                    })
                    ->when($finish_date, function($query) use ($finish_date) {
                        return $query->where('created_at', '<=' , $finish_date);
                    })
                    ->selectRaw('
                        count(*) as total,
                        DATE(created_at) as date
                    ')
                    ->groupBy('date')
                    ->orderBy('date', 'asc')
                    ->get();
        
        $view_type = 'chart';
        if($request->has('view') && in_array($request->input('view'), ['chart', 'table'])) {
            $view_type = $request->input('view');
        }

        return view('embeddables.events.latest_events')
        ->with([
            "data" => [
                "events" => $events,
                "view_type" => $view_type
            ],
        ]);
    }

    public function eventsPerDay(Request $request)
    {
        $tenant = $request->input('tenant');
        $start_date = $request->input('start_date');
        $finish_date = $request->input('finish_date');

        $last_month = now()->subMonth(1);
        $events = Event::when($tenant, function($query) use ($tenant) {
                        return $query->where('tenant_id', $tenant);
                    })
                    ->when($start_date, function($query) use ($start_date) {
                        return $query->where('created_at', '>=' , $start_date);
                    })
                    ->when($finish_date, function($query) use ($finish_date) {
                        return $query->where('created_at', '<=' , $finish_date);
                    })
                    ->selectRaw('
                        count(*) as total,
                        cast(extract(isodow from created_at) as integer) as week_day
                    ')
                    ->groupBy('week_day')
                    ->orderBy('week_day', 'asc')
                    ->get();

        $view_type = 'chart';
        if($request->has('view') && in_array($request->input('view'), ['chart', 'table'])) {
            $view_type = $request->input('view');
        }

        return view('embeddables.events.events_per_day')
        ->with([
            "data" => [
                "events" => $events,
                "view_type" => $view_type
            ]
        ]);
    }

    public function eventsPerHour(Request $request)
    {
        $tenant = $request->input('tenant');
        $start_date = $request->input('start_date');
        $finish_date = $request->input('finish_date');

        $last_month = now()->subMonth(1);
        $events = Event::when($tenant, function($query) use ($tenant) {
                        return $query->where('tenant_id', $tenant);
                    })
                    ->when($start_date, function($query) use ($start_date) {
                        return $query->where('created_at', '>=' , $start_date);
                    })
                    ->when($finish_date, function($query) use ($finish_date) {
                        return $query->where('created_at', '<=' , $finish_date);
                    })
                    ->selectRaw('
                        count(*) as total,
                        cast(extract(hour from created_at) as integer) as hour
                    ')
                    ->groupBy('hour')
                    ->orderBy('hour', 'asc')
                    ->get();

        $view_type = 'chart';
        if($request->has('view') && in_array($request->input('view'), ['chart', 'table'])) {
            $view_type = $request->input('view');
        }


        return view('embeddables.events.events_per_hour')
        ->with([
            "data" => [
                "events" => $events,
                "view_type" => $view_type
            ]
        ]);
    }

    public function eventsPerModel(Request $request)
    {
        $tenant = $request->input('tenant');
        $start_date = $request->input('start_date');
        $finish_date = $request->input('finish_date');

        $events = Event::when($tenant, function($query) use ($tenant) {
                            return $query->where('events.tenant_id', $tenant);
                        })
                        ->when($start_date, function($query) use ($start_date) {
                            return $query->where('events.created_at', '>=' , $start_date);
                        })
                        ->when($finish_date, function($query) use ($finish_date) {
                            return $query->where('events.created_at', '<=' , $finish_date);
                        })
                        ->join('vehicles', 'events.vehicle_id', '=', 'vehicles.id')
                        ->leftJoin('car_brands', 'vehicles.card_brand_id', '=', 'car_brands.id')
                        ->selectRaw("
                        count(*) as total,
                        vehicles.model as model,
                        vehicles.year as year,
                        car_brands.name as brand
                        ")
                        ->groupBy('vehicles.model', 'vehicles.year', 'car_brands.name')
                        ->orderBy('total', 'desc')
                        ->take(10)
                        ->get();

        $view_type = 'chart';
        if($request->has('view') && in_array($request->input('view'), ['chart', 'table'])) {
            $view_type = $request->input('view');
        }

        return view('embeddables.events.events_per_model')
                ->with([
                    "data" => [
                        "events" => $events,
                        "view_type" => $view_type
                    ]
                ]);
    }

    public function eventsStatusResult(Request $request)
    {
        $tenant = $request->input('tenant');
        $start_date = $request->input('start_date');
        $finish_date = $request->input('finish_date');

        $last_month = now()->subMonth(1);
        $events = Event::when($tenant, function($query) use ($tenant) {
                            return $query->where('events.tenant_id', $tenant);
                        })
                        ->when($start_date, function($query) use ($start_date) {
                            return $query->where('events.created_at', '>=' , $start_date);
                        })
                        ->when($finish_date, function($query) use ($finish_date) {
                            return $query->where('events.created_at', '<=' , $finish_date);
                        })
                        ->leftJoin('event_results', 'events.event_result_id', '=', 'event_results.id')
                        ->selectRaw("
                        count(*) as total,
                        event_results.code as status
                        ")
                        ->groupBy('event_results.code')
                        ->orderBy('total', 'desc')
                        ->take(10)
                        ->get();

        $view_type = 'chart';
        if($request->has('view') && in_array($request->input('view'), ['chart', 'table'])) {
            $view_type = $request->input('view');
        }

        return view('embeddables.events.events_status_result')
                ->with([
                    "data" => [
                        "events" => $events,
                        "view_type" => $view_type
                    ]
                ]);
    }
}
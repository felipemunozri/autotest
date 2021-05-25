<?php

namespace App\Domain\Helpers;

use App\Models\Event;
use App\Models\EventStatus;
use Carbon\Carbon;

class EventHelper
{
    public function getSummary($tenantId, $count = false)
    {
        $monday = Carbon::now()->startOfWeek();
        $sunday = Carbon::now()->endOfWeek();
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $statusAvailable = EventStatus::query()->whereIn('code', ['in-progress', 'finished'])->pluck('id');

        $data = Event::query()//->join('vehicles', 'vehicle_id', '=', 'vehicles.id')
            ->where('tenant_id', '=',$tenantId)
            ->whereIn('event_status_id', $statusAvailable);

        $all = $count ? $data->count() : $data->get();

        $yearly = $data->whereYear('event_date', '=', $year);
        $yearly = $count ? $yearly->count() : $yearly->get();

        $monthly = $data->whereYear('event_date', '=', $year)
            ->whereMonth('event_date', '=', $month);
        $monthly = $count ? $monthly->count() : $monthly->get();

        $data = Event::query()//->join('vehicles', 'vehicle_id', '=', 'vehicles.id')
            ->where('tenant_id', '=',$tenantId);

        $weekly = $data->where('event_date', '>=', $monday)
            ->where('event_date', '<', $sunday->addDay());
        $weekly = $count ? $weekly->count() : $weekly->get();

        return (object)[
            'weekly' =>  $weekly,
            'monthly' => $monthly,
            'yearly' => $yearly,
            'all' => $all
        ];
    }
}

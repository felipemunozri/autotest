<?php


namespace App\Domain\Helpers\Reports;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class VehicleRankingHelper
{
    public static function getRankingByTenant($tenantId)
    {
      $last_month = now()->subMonth(1);
      $query = Event::where('events.tenant_id', $tenantId)
                  ->join('vehicles', 'events.vehicle_id', '=', 'vehicles.id')
                  ->leftJoin('car_brands', 'vehicles.card_brand_id', '=', 'car_brands.id')
                  ->leftJoin('event_results', 'events.event_result_id', '=', 'event_results.id')
                  ->selectRaw("
                    count(*) as total,
                    vehicles.model as model,
                    car_brands.name as brand,
                    count((CASE WHEN events.created_at > '$last_month' THEN 1 END)) as last_month,
                    count((CASE WHEN event_results.code = 'retrieved' THEN 1 END)) as retrieved,
                    count((CASE WHEN event_results.code = 'total-loss' THEN 1 END)) as total_loss,
                    count((CASE WHEN event_results.code = 'retrieved-with-damage' THEN 1 END)) as retrieved_with_damage,
                    count((CASE WHEN event_results.code = 'not-retrieved' THEN 1 END)) as not_retrieved
                  ")
                  ->groupBy('vehicles.model', 'car_brands.name')
                  ->orderBy('total', 'desc');
                  // ->toSql();
        return $query;
    }
}

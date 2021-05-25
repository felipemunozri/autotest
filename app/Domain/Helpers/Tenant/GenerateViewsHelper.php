<?php

namespace App\Domain\Helpers\Tenant;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class GenerateViewsHelper
{
  public static function generateByTenant($tenantId)
  {
    
    $tenant = Tenant::where('id', $tenantId)->first();
    $viewName = self::formatName($tenant->code);

    self::downView($viewName);
    
    DB::statement("create materialized view views.$viewName as
                  select 
                        events.origin->'address' as address,
                        CASE WHEN events.origin->'location'->'lat' is not null THEN events.origin->'location'->'lat' END as lat,
                        CASE WHEN events.origin->'location'->'lat' is not null THEN events.origin->'location'->'lng' END as lng,
                        events.detail,
                        events.reason,
                        events.observation,
                        events.event_start,
                        events.event_end,
                        events.event_date,
                        events.event_time,
                        events.created_at,
                        events.updated_at,
                        car_brands.name as vehicle_brand,
                        vehicles.model as vehicle_model,
                        vehicles.plate_number as vehicle_plate_number,
                        vehicles.color as vehicle_color,
                        vehicles.year as vehicle_year,
                        beneficiaries.name as beneficiary_name,
                        beneficiaries.lastname as beneficiary_lastname,
                        beneficiaries.dni as beneficiary_dni,
                        beneficiaries.phone_number as beneficiary_phone_number,
                        beneficiaries.email as beneficiary_email,
                        users.name as operator_name,
                        users.lastname as operator_lastname,
                        users.dni as operator_dni,
                        users.phone as operator_phone,
                        users.email as operator_email,
                        event_status.name as event_status_name,
                        event_status.code as event_status_code,
                        event_results.name as event_result_name,
                        event_results.code as event_result_code
                  from public.events as events
                  inner join public.vehicles as vehicles on events.vehicle_id = vehicles.id
                  left join public.car_brands as car_brands on vehicles.card_brand_id = car_brands.id
                  inner join public.beneficiaries as beneficiaries on events.beneficiary_id = beneficiaries.id
                  inner join public.users as users on events.received_by = users.id
                  left join public.event_status as event_status on events.event_status_id = event_status.id
                  left join public.event_results as event_results on events.event_result_id = event_results.id
                  where events.tenant_id = '$tenantId'");

  }

  public static function generateAllTenant()
  {
    $tenants = Tenant::all();
    
    $tempTenant;
    for ($i=0; $i < count($tenants) ; $i++) { 
      $tempTenant = $tenants[$i];
      self::generateByTenant($tempTenant->id);
    }

  }

  private static function downView($viewName){
    DB::statement("drop materialized view if exists views.$viewName");
  }

  private static function formatName($name){
    $viewName = trim($name);
    $viewName = strtolower($viewName);
    $viewName = preg_replace('/\s+/', '_', $viewName);
    $viewName = preg_replace('/-/', '_', $viewName);
    $viewName = self::cleanUTF8($viewName);
    return $viewName;
  }

  private static function cleanUTF8($string){
    $original = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ';
    $setters = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby';
    $string = utf8_decode($string);
    $string = strtr($string, utf8_decode($original), $setters);
    return utf8_encode($string);
  }

}
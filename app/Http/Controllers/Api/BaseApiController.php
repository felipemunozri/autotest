<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Fractal\TransformerAbstract;

abstract class BaseApiController extends Controller
{
    protected $model;

    protected $user;

    public function __construct()
    {
        $this->user = $this->myUser();
    }

    protected function myUser()
    {
        return auth()->user();
    }

    /**
     * @return Builder
     */
    protected function getQuery()
    {
        return $this->model->newQuery();
    }

    /**
     * @param array $atributos
     *
     * @return Builder
     */
    protected function filtrar($atributos = [])
    {
        $query = $this->getQuery();
        foreach ($atributos as $key => $valor) {
            $query = \is_array($valor) ? $query->whereIn($key, $valor) : $query->where($key,'ILIKE',$valor);
        }

        return $query;
    }

    protected function filterDateRange(Builder $query, $field, $from, $to)
    {
        $query = $query->when($from, function($query) use ($field, $from) {
            return $query->where($field, '>=' , $from);
        })
            ->when($to, function($query) use ($field, $to) {
                return $query->where($field, '<=' , $to);
            });

        return $query;
    }

    protected function currentTenantId()
    {
        $user = auth()->user() ?? User::query()->find(1);
        $userPreferences = DB::table('user_preferences')->where('user_id', $user->id)->first();
        return $userPreferences->current_tenant_id;
    }

    protected function currentTenant()
    {
        $user = auth()->user() ?? User::query()->find(1);
        $userPreferences = DB::table('user_preferences')->where('user_id', $user->id)->first();
        $tenant = Tenant::query()->where('id', $userPreferences->current_tenant_id)->firstOrFail();

        return $tenant;
    }

    protected function country()
    {
        $tenant = $this->currentTenant();
        return Country::query()->where('id', $tenant->country_id)->firstOrFail();
    }

    protected function getInputsUser(Request $request)
    {
        return $this->validate($request, [
            'dni' => 'sometimes',
            'name' => 'required',
            'second_name' => 'sometimes',
            'lastname' => 'required',
            'second_lastname' => 'sometimes',
            'username' => 'sometimes',
            'email' => 'required',
            'password' => 'sometimes',
            'phone' => 'sometimes'
        ]);
    }
}

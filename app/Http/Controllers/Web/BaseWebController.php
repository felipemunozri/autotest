<?php

namespace App\Http\Controllers\Web;

use App\Domain\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class BaseWebController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = $this->myUser();
    }

    protected function myUser()
    {
        return auth()->user();
    }

    protected function tenant()
    {
        $userPreferences = DB::table('public.user_preferences')->where('user_id', auth()->user()->id)->first();
        return Tenant::query()->find($userPreferences->current_tenant_id);
    }

    protected function getBaseData()
    {
        return [
            'user' => $this->user,
            'tenant' => $this->tenant(),
            'unread_messages_count' => $this->unreadMessages(),
            'roles' => $this->userRoles(),
        ];
    }

    protected function userRoles()
    {
        return (new UserHelper())->profileNamesByUser(auth()->user()->id);
    }

    protected function unreadMessages()
    {
        $messages = auth()->user()->messages()->get();
        $readMessages =  auth()->user()->readMessages()->get();
        return $messages->whereNotIn('id', $readMessages->pluck('id'))->count();
    }
}

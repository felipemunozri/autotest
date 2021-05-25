<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenericResource;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Http\Request;

class UserNotificationController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new DatabaseNotification();
    }

    public function index(Request $request)
    {
        $params = $request->only(['read_at']);
        $this->user = $this->myUser();

        $include = $request->input('include', []);

        $paginate = $request->has('page');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 30);

        $unread_only = $request->input('unread_only', false);

        $data = $this->getQuery()
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', $this->user->id)
            ->with($include)
            ->orderByDesc('read_at')
            ->orderByDesc('created_at');

        if ($unread_only) {
            $data = $data->whereNull('read_at');
        }

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        return GenericResource::collection($data);
    }

    public function read(Request $request)
    {
        $this->user = $this->myUser();

        $params = $this->validate($request, [
            'notification_id' => 'required_without:read_all',
            'read_all' => 'required_without:notification_id',
        ]);

        $id = $request->input('notification_id', null);
        $read_all = $request->input('read_all', false);

        if ($id) {
            $notification = $this->getQuery()->where('id', $id)->firstOrFail();
            $notification->markAsRead();
        } else if ($read_all) {
            $this->user->unreadNotifications()->update(['read_at' => now()]);
        }

        return response()->json('successfully read', 200);
    }

    public function delete(Request $request)
    {
        $this->user = $this->myUser();
        
        $params = $this->validate($request, [
            'notification_id' => 'required_without:delete_all',
            'delete_all' => 'required_without:notification_id',
        ]);

        $id = $request->input('notification_id', null);
        $delete_all = $request->input('delete_all', false);

        if ($id) {
            $notification = $this->getQuery()->where('id', $id)->firstOrFail();
            $notification->delete();
        } else if ($delete_all) {
            $this->user->notifications()->delete();
        }
        
        return response()->json('successfully deleted', 200);
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\DatabaseNotification;

use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Device;

class VehicleActivated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $code = 'vehicle-activated';
    
    protected $redirect_url;
    protected $technical;
    protected $vehicle;
    protected $device;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $technical,Vehicle $vehicle,Device $device,string $redirect_url = null)
    {
        $this->redirect_url = $redirect_url;
        $this->technical = $technical;
        $this->vehicle = $vehicle;
        $this->vehicle->load('carBrand');
        $this->device = $device;
        $this->device->load('simCard','model');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'code' => $this->code,
            'redirect_url' => $this->redirect_url,
            'technical' => [
                'id' => $this->technical->id,
                'dni' => $this->technical->dni,
                'name' => $this->technical->name,
                'second_name' => $this->technical->second_name,
                'lastname' => $this->technical->lastname,
                'second_lastname' => $this->technical->second_lastname,
                'email' => $this->technical->email,
            ],
            'vehicle' => [
                'id' => $this->vehicle->id,
                'plate_number' => $this->vehicle->plate_number,
                'model' => $this->vehicle->model,
                'year' => $this->vehicle->year,
                'brand_name' =>$this->vehicle->carBrand ? $this->vehicle->carBrand->name : null
            ],
            'device' => [
                'id' => $this->device->id,
                'serial_number' => $this->device->serial_number,
                'model_name' => $this->device->model->name,
                'phone_number' => $this->device->simCard->phone_number
            ]
            
        ];
    }

    public function toBroadcast($notifiable)
    {
        $notification = DatabaseNotification::where('id', $this->id)->first();
        return new BroadcastMessage([
            'id' => $notification->id,
            'type' => $notification->type,
            'notifiable_type' => $notification->notifiable_type,
            'notifiable_id' => $notification->notifiable_id,
            'read_at' => $notification->read_at,
            'created_at' => $notification->created_at,
            'updated_at' => $notification->updated_at,
            'code' => $this->code,
            'redirect_url' => $this->redirect_url,
            'technical' => [
                'id' => $this->technical->id,
                'dni' => $this->technical->dni,
                'name' => $this->technical->name,
                'second_name' => $this->technical->second_name,
                'lastname' => $this->technical->lastname,
                'second_lastname' => $this->technical->second_lastname,
                'email' => $this->technical->email,
            ],
            'vehicle' => [
                'id' => $this->vehicle->id,
                'plate_number' => $this->vehicle->plate_number,
                'model' => $this->vehicle->model,
                'year' => $this->vehicle->year,
                'brand_name' =>$this->vehicle->carBrand ? $this->vehicle->carBrand->name : null
            ],
            'device' => [
                'id' => $this->device->id,
                'serial_number' => $this->device->serial_number,
                'model_name' => $this->device->model->name,
                'phone_number' => $this->device->simCard->phone_number
            ]
            
        ]);
    }

    /**
     * Get the type of the notification being broadcast.
     *
     * @return string
     */
    public function broadcastType()
    {
        return 'broadcast.message';
    }
}

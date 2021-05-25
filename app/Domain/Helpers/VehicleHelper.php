<?php


namespace App\Domain\Helpers;


use App\Models\Beneficiary;
use App\Models\Device;
use App\Models\SimCard;
use App\Models\Vehicle;

class VehicleHelper
{
    public function getDevicePhoneNumber($vehicleId)
    {
        $device = Device::query()->where('vehicle_id', $vehicleId)->first();
        /*if($device) {
            $simCard = SimCard::query()->where('device_id', $device->id)->first();
        }*/

        $simCard = SimCard::query()
            ->whereHas('device', function($query) use ($vehicleId) {
                $query->where('vehicle_id', $vehicleId);
            })
            ->first();

        if($simCard) {
            return (object)[
                'message' => 'ok',
                'data' => $simCard,
            ];
        }

        return (object)[
            'message' => 'El vehículo no tiene dispositivo con número registrado',
            'data' => null,
        ];

    }

    public function isBeneficiary($tenantId, $plateNumber, $dni)
    {
        $vehicle = Vehicle::query()->where('plate_number', $plateNumber)->first();

        if($vehicle) {
            if($vehicle->tenant_id != $tenantId) {
                return (object)[
                    'result' => false,
                    'message' => 'Vehicle does not belong to the administration',
                ];
            }

            $beneficiaries = $vehicle->beneficiaries()->get();
            if($beneficiaries->contains('dni', $dni)) {
                $beneficiary = $beneficiaries->where('dni', $dni)->first();
                return (object)[
                    'result' => true,
                    'message' => 'beneficiary founded',
                    'beneficiary' => $beneficiary,
                    'owner' => $beneficiary->pivot->owner,
                ];
            }
            return (object)[
                'result' => false,
                'message' => 'beneficiary not founded',
                'beneficiary' => null,
            ];
        }

        return (object)[
            'result' => false,
            'message' => 'vehícle not founded',
            'beneficiary' => null,
        ];
    }
}

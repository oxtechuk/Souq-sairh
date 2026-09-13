<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Employee;
use App\Models\Setting;

class OrderDistributionService
{
    const METHOD_DISABLED = 'disabled';
    const METHOD_ROUND_ROBIN = 'round_robin';
    const METHOD_LEAST_LOADED = 'least_loaded';

    public function distribute(Booking $booking): void
    {
        $method = $this->getMethod();

        if ($method === self::METHOD_DISABLED) {
            return;
        }

        $employee = match ($method) {
            self::METHOD_ROUND_ROBIN => $this->getNextRoundRobin(),
            self::METHOD_LEAST_LOADED => $this->getLeastLoaded(),
            default => null,
        };

        if ($employee) {
            $booking->update(['assigned_to' => $employee->id]);
        }
    }

    public function getMethod(): string
    {
        return Setting::where('key', 'order_distribution_method')
            ->first()?->value ?? self::METHOD_DISABLED;
    }

    private function getNextRoundRobin(): ?Employee
    {
        $eligibleEmployees = Employee::receivingOrders()
            ->orderBy('id')
            ->get(['id']);

        if ($eligibleEmployees->isEmpty()) {
            return null;
        }

        $lastAssignedId = (int) Setting::where('key', 'round_robin_last_employee_id')
            ->first()?->value;

        $nextIndex = 0;
        foreach ($eligibleEmployees as $i => $emp) {
            if ((int) $emp->id === $lastAssignedId) {
                $nextIndex = ($i + 1) % $eligibleEmployees->count();
                break;
            }
        }

        $next = $eligibleEmployees[$nextIndex];

        Setting::updateOrCreate(
            ['key' => 'round_robin_last_employee_id'],
            ['value' => (string) $next->id]
        );

        return Employee::find($next->id);
    }

    private function getLeastLoaded(): ?Employee
    {
        $eligibleEmployees = Employee::receivingOrders()->get(['id']);

        if ($eligibleEmployees->isEmpty()) {
            return null;
        }

        $employee = Employee::receivingOrders()
            ->withCount(['bookings as active_count' => function ($q) {
                $q->whereNotIn('status', ['sold', 'rejected']);
            }])
            ->orderBy('active_count')
            ->orderBy('id')
            ->first();

        return $employee;
    }
}

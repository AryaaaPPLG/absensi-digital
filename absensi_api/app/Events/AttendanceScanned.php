<?php

namespace App\Events;

use App\Models\Attendance;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttendanceScanned implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $attendance;

    /**
     * Create a new event instance.
     */
    public function __construct(Attendance $attendance)
    {
        // Load relationships needed for the dashboard
        $this->attendance = $attendance->load(['user.schoolClass', 'user.shift']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('attendance-channel'),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'nama_siswa' => $this->attendance->user->name,
            'waktu_absen' => $this->attendance->time_out ?? $this->attendance->time_in,
            'status' => $this->attendance->status,
            'metode_rfid' => strtoupper($this->attendance->method ?? 'RFID'),
            'type' => $this->attendance->time_out ? 'out' : 'in',
            'avatar' => "https://ui-avatars.com/api/?name=" . urlencode($this->attendance->user->name) . "&background=3b82f6&color=fff",
            'role' => $this->attendance->user->role,
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'AttendanceScanned';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class RoundOrder extends SnipeModel
{
    use SoftDeletes;

    protected $table = 'round_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location',
        'type',
        'checklist',
        'description',
        'attachment',
        'status',
        'technician',
        'department'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the inner location associated with the round order.
     */
    public function innerLocation()
    {
        return $this->belongsTo(InnerLocation::class, 'location');
    }

    /**
     * Get the checklist associated with the round order.
     */
    public function checklist()
    {
        return $this->belongsTo(Checklists::class, 'checklist_id');
    }

    /**
     * Get the technician user associated with the round order.
     */
    public function technicianUser()
    {
        return $this->belongsTo(User::class, 'technician');
    }

    /**
     * Get the department data associated with the round order.
     */
    public function departmentData()
    {
        return $this->belongsTo(Department::class, 'department');
    }

    /**
     * Get the display name for the round order.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return $this->innerLocation ? $this->innerLocation->name : 'Unknown Location';
    }

    /**
     * Scope a query to only include pending round orders.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include completed round orders.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
} 
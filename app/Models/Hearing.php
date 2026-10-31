<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hearing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
        'conducted_by',
        'hearing_date',
        'next_hearing_date',
        'order_details',
        'sms_sent',
    ];

    /**
     * The attributes that should be cast to native types.
     * 
     * Casting to 'date' allows you to use Carbon methods on these fields 
     * directly in your blade files, e.g., $hearing->hearing_date->format('d M, Y')
     *
     * @var array
     */
    protected $casts = [
        'hearing_date' => 'date',
        'next_hearing_date' => 'date',
        'sms_sent' => 'boolean',
    ];

    /* ---------------------------------------------------------
     * Eloquent Relationships
     * --------------------------------------------------------- */

    /**
     * Get the file/case that this hearing belongs to.
     */
    public function file()
    {
        return $this->belongsTo(File::class);
    }

    /**
     * Get the user (e.g., AC Land) who conducted this hearing.
     */
    public function conductor()
    {
        return $this->belongsTo(User::class, 'conducted_by');
    }
}
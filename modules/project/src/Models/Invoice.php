<?php
namespace Modules\Project\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Project\Models\Project;
use Modules\Project\Models\Client;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invoices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'invoice_type',
        'cost_category',
        'currency',
        'amount',
        'payment_method',
        'transaction_reference',
        'invoice_date',
        'due_date',
        'proof_of_payment',
        'progress_level',
        'project_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'invoice_date' => 'date:Y-m-d',
        'due_date' => 'date:Y-m-d',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'is_overdue',
        'formatted_amount',
        'status_badge',
        'days_until_due',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the project that owns the invoice.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the client through the project.
     */
    public function client()
    {
        return $this->hasOneThrough(
            Client::class,
            Project::class,
            'id', // Foreign key on projects table
            'id', // Foreign key on clients table
            'project_id', // Local key on invoices table
            'client_id' // Local key on projects table
        );
    }

    // ==================== SCOPES ====================

    /**
     * Scope a query to only include income invoices.
     */
    public function scopeIncome($query)
    {
        return $query->where('invoice_type', 'income');
    }

    /**
     * Scope a query to only include cost invoices.
     */
    public function scopeCost($query)
    {
        return $query->where('invoice_type', 'cost');
    }

    /**
     * Scope a query to only include pending invoices.
     */
    public function scopePending($query)
    {
        return $query->where('progress_level', 'pending');
    }

    /**
     * Scope a query to only include paid invoices.
     */
    public function scopePaid($query)
    {
        return $query->where('progress_level', 'paid');
    }

    /**
     * Scope a query to only include overdue invoices.
     */
    public function scopeOverdue($query)
    {
        return $query->where('progress_level', 'overdue');
    }

    /**
     * Scope a query to only include cancelled invoices.
     */
    public function scopeCancelled($query)
    {
        return $query->where('progress_level', 'cancelled');
    }

    /**
     * Scope a query to get overdue invoices.
     */
    public function scopeIsOverdue($query)
    {
        return $query->where('due_date', '<', now())
                     ->where('progress_level', '!=', 'paid')
                     ->where('progress_level', '!=', 'cancelled');
    }

    /**
     * Scope a query to filter by payment method.
     */
    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope a query to filter by cost category.
     */
    public function scopeByCostCategory($query, $category)
    {
        return $query->where('cost_category', $category);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('invoice_date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to filter by due date range.
     */
    public function scopeDueDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('due_date', [$startDate, $endDate]);
    }

    // ==================== ACCESSORS & MUTATORS ====================

    /**
     * Check if the invoice is overdue.
     */
    public function getIsOverdueAttribute()
    {
        return $this->due_date && 
               $this->due_date->isPast() && 
               $this->progress_level !== 'paid' && 
               $this->progress_level !== 'cancelled';
    }

    /**
     * Get formatted amount with currency.
     */
    public function getFormattedAmountAttribute()
    {
        return $this->currency . ' ' . number_format($this->amount, 2);
    }

    /**
     * Get status badge color.
     */
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'paid' => 'success',
            'overdue' => 'danger',
            'cancelled' => 'secondary',
        ];

        return $colors[$this->progress_level] ?? 'secondary';
    }

    /**
     * Get days until due date.
     */
    public function getDaysUntilDueAttribute()
    {
        if ($this->due_date) {
            $days = now()->diffInDays($this->due_date, false);
            return $days < 0 ? 0 : $days;
        }
        return null;
    }

    /**
     * Get human-readable status.
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending',
            'paid' => 'Paid',
            'overdue' => 'Overdue',
            'cancelled' => 'Cancelled',
        ];

        return $labels[$this->progress_level] ?? ucfirst($this->progress_level);
    }

    /**
     * Get human-readable invoice type.
     */
    public function getInvoiceTypeLabelAttribute()
    {
        $labels = [
            'income' => 'Income (Receivable)',
            'cost' => 'Cost (Payable)',
        ];

        return $labels[$this->invoice_type] ?? ucfirst($this->invoice_type);
    }

    /**
     * Get human-readable payment method.
     */
    public function getPaymentMethodLabelAttribute()
    {
        $labels = [
            'bank_transfer' => 'Bank Transfer',
            'cash' => 'Cash',
            'cheque' => 'Cheque',
            'online_payment' => 'Online Payment',
        ];

        return $labels[$this->payment_method] ?? ucfirst($this->payment_method);
    }

    /**
     * Get human-readable cost category.
     */
    public function getCostCategoryLabelAttribute()
    {
        $labels = [
            'employee_cost' => 'Employee Cost',
            'infrastructure_cost' => 'Infrastructure Cost',
            'third_party_services' => 'Third-Party Services',
            'other' => 'Other',
        ];

        return $labels[$this->cost_category] ?? ucfirst($this->cost_category);
    }

    /**
     * Get proof of payment URL.
     */
    public function getProofOfPaymentUrlAttribute()
    {
        if ($this->proof_of_payment) {
            return asset('storage/' . $this->proof_of_payment);
        }
        return null;
    }

    // ==================== CUSTOM METHODS ====================

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid()
    {
        $this->progress_level = 'paid';
        $this->save();
    }

    /**
     * Mark invoice as overdue.
     */
    public function markAsOverdue()
    {
        $this->progress_level = 'overdue';
        $this->save();
    }

    /**
     * Mark invoice as cancelled.
     */
    public function markAsCancelled()
    {
        $this->progress_level = 'cancelled';
        $this->save();
    }

    /**
     * Check if invoice is paid.
     */
    public function isPaid()
    {
        return $this->progress_level === 'paid';
    }

    /**
     * Check if invoice is pending.
     */
    public function isPending()
    {
        return $this->progress_level === 'pending';
    }

    /**
     * Check if invoice is overdue.
     */
    public function isOverdue()
    {
        return $this->is_overdue;
    }

    /**
     * Check if invoice is cancelled.
     */
    public function isCancelled()
    {
        return $this->progress_level === 'cancelled';
    }

    /**
     * Get invoice summary.
     */
    public function getSummary()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->invoice_type_label,
            'amount' => $this->formatted_amount,
            'status' => $this->status_label,
            'invoice_date' => $this->invoice_date->format('Y-m-d'),
            'due_date' => $this->due_date->format('Y-m-d'),
            'is_overdue' => $this->is_overdue,
            'days_until_due' => $this->days_until_due,
            'payment_method' => $this->payment_method_label,
            'transaction_reference' => $this->transaction_reference,
            //'project' => $this->project?->project_name,
			'project' => isset($this->project) ? $this->project->project_name : null,
			'proof_of_payment' => $this->proof_of_payment_url,
        ];
    }

    /**
     * Check if invoice has proof of payment.
     */
    public function hasProofOfPayment()
    {
        return !is_null($this->proof_of_payment);
    }

    /**
     * Calculate days overdue.
     */
    public function getDaysOverdue()
    {
        if ($this->is_overdue) {
            return $this->due_date->diffInDays(now());
        }
        return 0;
    }

    /**
     * Check if invoice is for income.
     */
    public function isIncome()
    {
        return $this->invoice_type === 'income';
    }

    /**
     * Check if invoice is for cost.
     */
    public function isCost()
    {
        return $this->invoice_type === 'cost';
    }

    /**
     * Auto-update overdue status.
     */
    public static function updateOverdueStatus()
    {
        return self::where('due_date', '<', now())
                   ->where('progress_level', 'pending')
                   ->update(['progress_level' => 'overdue']);
    }
}







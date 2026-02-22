<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\CompanyDetail;

trait BelongsToCompany
{
    protected static function bootBelongsToCompany()
    {
        // Add Global Scope to filter records by company_id, 
        // unless the logged in user is a Superadmin or running in console without auth
        static::addGlobalScope('company', function (Builder $builder) {
            if (Auth::check() && !Auth::user()->hasRole('Superadmin')) {
                $builder->where($builder->getQuery()->from . '.company_id', Auth::user()->company_id);
            }
        });

        // Auto-assign company_id when creating models
        static::creating(function ($model) {
            // Also need to check if the class allows mass assignment via fillable/guarded
            // or if we just manually set the attribute
            
            if (Auth::check() && !Auth::user()->hasRole('Superadmin') && empty($model->company_id)) {
                $model->company_id = Auth::user()->company_id;
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(CompanyDetail::class, 'company_id');
    }
}

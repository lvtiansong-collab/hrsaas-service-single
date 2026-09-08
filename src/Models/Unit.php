<?php

namespace Wiltechsteam\FoundationServiceSingle\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';

    public $timestamps = false;

    public $keyType = 'string';

    public $primaryKey = 'id';

    public $incrementing = false;

    protected $guarded = [];

    public function setIdAttribute($value)
    {
        $this->attributes['id'] = strtoupper($value);
    }

    public function setCreatedDateAttribute($value)
    {
        $this->attributes['created_date'] = date('Y-m-d H:i:s', strtotime($value));
    }

    public function setCreatedByAttribute($value)
    {
        $this->attributes['created_by'] = strtoupper($value);
    }

    public function setLastModifiedDateAttribute($value)
    {
        $this->attributes['last_modified_date'] = date('Y-m-d H:i:s', strtotime($value));
    }

    public function setLastModifiedByAttribute($value)
    {
        $this->attributes['last_modified_by'] = strtoupper($value);
    }

    public function setParentIdAttribute($value)
    {
        $this->attributes['parent_id'] = strtoupper($value);
    }

    public function setLeaderIdAttribute($value)
    {
        $this->attributes['leader_id'] = strtoupper($value);
    }

    public function setLocationCodeAttribute($value)
    {
        $this->attributes['location_code'] = strtoupper($value);
    }

    public function setIsActiveAttribute($value)
    {
        $this->attributes['is_active'] = $value === true ? 1 : 0;
    }
}

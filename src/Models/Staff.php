<?php

namespace Wiltechsteam\FoundationServiceSingle\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staffs';

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

    public function setUserIdAttribute($value)
    {
        $this->attributes['user_id'] = strtoupper($value);
    }

    public function setUserNameAttribute($value)
    {
        $this->attributes['user_name'] = strtoupper($value);
    }

    public function setCompanyIdAttribute($value)
    {
        $this->attributes['company_id'] = strtoupper($value);
    }

    public function setDepartmentIdAttribute($value)
    {
        $this->attributes['department_id'] = strtoupper($value);
    }

    public function setGroupIdAttribute($value)
    {
        $this->attributes['group_id'] = strtoupper($value);
    }

    public function setPositionIdAttribute($value)
    {
        $this->attributes['position_id'] = strtoupper($value);
    }

    public function setSuperiorIdAttribute($value)
    {
        $this->attributes['superior_id'] = strtoupper($value);
    }

    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = date('Y-m-d', strtotime($value));
    }

    public function setHiredDateAttribute($value)
    {
        $this->attributes['hired_date'] = date('Y-m-d', strtotime($value));
    }

    public function setTerminatedDateAttribute($value)
    {
        $this->attributes['terminated_date'] = date('Y-m-d', strtotime($value));
    }

}

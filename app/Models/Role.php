<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use App\Models\Permissions;

    class Role extends Model
    {
        use HasFactory;
        protected $fillable = ['name', 'description'];

        public function users()
        {
            return $this->belongsToMany(User::class);
        }
        public function permissions()
        {
            return $this->belongsToMany(Permissions::class);
        }

        public function assignPermission($permission)
        {
            return $this->permissions()->syncWithoutDetaching($permission);
        }

        public function revokePermission($permission)
        {
            return $this->permissions()->detach($permission);
        }
    }

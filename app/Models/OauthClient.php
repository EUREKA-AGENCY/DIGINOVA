<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OauthClient extends Model
{
    use HasFactory;

    protected $table = 'oauth_clients';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'secret',
        'redirect_uri',
    ];

    public function tokens()
    {
        return $this->hasMany(ApiToken::class, 'client_id', 'id');
    }
}


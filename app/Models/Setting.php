<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    public function setLogoAttribute()
    {
        $file = request()->file('logo');
        $destinationPath = 'images/logo/';
        $filename = $file->getClientOriginalName();
        $file->move($destinationPath, $filename);
        $this->attributes['logo'] = $filename;
    }
    public function getLogoAttribute()
    {
        return asset('images/logo/').'/'.$this->attributes['logo'];
    }
    protected $fillable = [
       'licence', 'about', 'block','quran','hadeth','zakah','talk_about','festival','contact','percent','percent_ratio','twitter','instagram','facebook','snap','youtube','whatsapp','android','ios'
    ];
}

<?php

namespace App\Http\View\Composers;

use App\Models\Kandangs;
use Illuminate\View\View;

class KandangComposer
{
      public function compose(View $view)
      {
            $view->with('kandangs', Kandangs::all());
      }
}

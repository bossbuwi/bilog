<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Type;
use App\Http\Resources\TypeResource as TypeResource;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::all();
        return TypeResource:: collection($types);
    }
}

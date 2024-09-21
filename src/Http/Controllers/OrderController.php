<?php
namespace Peyas\PreOrderForm\Http\Controllers;
use Illuminate\Routing\Controller;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'OrderController@index']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{

    public function __construct(Exchange $exchange)
    {
        $this->exchange = $exchange;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['success' => 'API de conversão de moedas.'];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->exchange->validateValue(), $this->exchange->callback()); //Validate data method PUT.

        $request = $request->all();
        $amount = $request['amount'];
        $from = $request['from'];
        $to = $request['to'];

        $output = $this->exchange->transformValue($amount, $from, $to);
        return response()->json($output);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Exchange  $exchange
     * @return \Illuminate\Http\Response
     */
    public function show(Exchange $exchange)
    {
        //
    }
}

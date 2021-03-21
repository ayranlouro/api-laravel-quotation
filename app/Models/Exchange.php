<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class Exchange extends Model
{
    use HasFactory;

    public function transformValue($amount, $from, $to)
    {
        //BRL-USD
        //USD-BRL
        //BRL-EUR
        //EUR-BRL
        $obj = new Exchange();
        if ($from && $to) {
            if ($from === 'BRL' && $to === 'USD') {
                return $obj->RealToDolar($amount);
            } else if ($from === 'USD' && $to === 'BRL') {
                return $obj->DolarToReal($amount);
            } elseif ($from === 'BRL' && $to === 'EUR') {
                return $obj->RealToEuro($amount);
            } else {
                return $obj->EuroToReal($amount);
            }
        } else {
            return 'Faltou argumentos!';
        }
    }

    public function RealToDolar($amount)
    {
        $response = Http::get('https://economia.awesomeapi.com.br/json/all/BRL-USD')->json()['BRL'];

        $name = $response['name'];
        $bid = $response['bid'];
        $bid = number_format($bid, 2);
        $input = $response['code'];
        $to = $response['codein'];
        $value = $amount * $bid;
        $value = number_format($value, 2);
        $sign = '$';
        $sign = $sign . $value;

        return ['Name' => $name, 'Convert' => $input, 'To' => $to, 'Bid price' => $bid, 'Amount' => $amount, 'Value' => $sign];
    }

    public function DolarToReal($amount)
    {
        $response = Http::get('https://economia.awesomeapi.com.br/json/all/USD-BRL')->json()['USD'];

        $name = $response['name'];
        $bid = $response['bid'];
        $bid = number_format($bid, 2);
        $input = $response['code'];
        $to = $response['codein'];
        $value = $amount * $bid;
        $value = number_format($value, 2);
        $sign = 'R$';
        $sign = $sign . $value;

        return ['Name' => $name, 'Convert' => $input, 'To' => $to, 'Bid price' => $bid, 'Amount' => $amount, 'Value' => $sign];
    }

    public function RealToEuro($amount)
    {  
        $response = Http::get('https://economia.awesomeapi.com.br/json/all/BRL-EUR')->json()['BRL'];

        $name = $response['name'];
        $bid = $response['bid'];
        $bid = number_format($bid, 2);
        $input = $response['code'];
        $to = $response['codein'];
        $value = $amount * $bid;
        $value = number_format($value, 2);
        $sign = '€';
        $sign = $sign . $value;

        return ['Name' => $name, 'Convert' => $input, 'To' => $to, 'Bid price' => $bid, 'Amount' => $amount, 'Value' => $sign];
    }

    public function EuroToReal($amount)
    {
        $response = Http::get('https://economia.awesomeapi.com.br/json/all/EUR-BRL')->json()['EUR'];

        $name = $response['name'];
        $bid = $response['bid'];
        $bid = number_format($bid, 2);
        $input = $response['code'];
        $to = $response['codein'];
        $value = $amount * $bid;
        $value = number_format($value, 2);
        $sign = 'R$';
        $sign = $sign . $value;

        return ['Name' => $name, 'Convert' => $input, 'To' => $to, 'Bid price' => $bid, 'Amount' => $amount, 'Value' => $sign];
    }

    public function validateValue()
    {
        return [
            'amount' => 'required',
            'from' => 'required|min:3',
            'to' => 'required|min:3',
        ];
    }

    public function callback()
    {
        return [
            'required' => 'O campo :attribute deve ser preenchido',
            'min' => 'O campo deve conter a sigla da nota, ex: USD',
        ];
    }
}

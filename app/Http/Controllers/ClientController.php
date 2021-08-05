<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Repositories\OrderRepository;
use App\Repositories\ClientRepository;
use App\Repositories\ProductRepository;

class ClientController extends Controller
{
    private $clientRepository;
    private $productRepository;
    private $orderRepository;

    public function __construct( ClientRepository $clientRepository, ProductRepository $productRepository, OrderRepository $orderRepository ){
        $this->clientRepository = $clientRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('createClient',['clients' => $this->clientRepository->getAll() ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('createClient',['clients' => $this->clientRepository->getAll() ] );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        //die;

        $this->validate($request, [
            'description' => 'required',
            'city' => 'required',
            'post_code' => 'required',
            'country' => 'required',
            'street' => 'required',
            'parcel_number' => 'required',
            'contact_number' => 'required'
        ]);

        //  Store data in database
        $client = new Client;
        $client->description = $request->description;
        $client->city = $request->city;
        $client->post_code = $request->post_code;
        $client->country = $request->country;
        $client->street = $request->street;
        $client->parcel_number = $request->parcel_number;
        $client->contact_number = $request->contact_number;
        $client->save();

        return back()->with('success', 'Klient dodany.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('editClient',['client' => $this->clientRepository->find($id) ] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $client =$this->clientRepository->find($id);
        $client->description = $request->description;
        $client->city = $request->city;
        $client->post_code = $request->post_code;
        $client->country = $request->country;
        $client->save();
        //
        return back()->with('success', 'Zmiany zapisane.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = $this->clientRepository->find($id);
        $client->delete();
        return redirect('/dashboard')->with('success', 'Klient został usunięty.');
    }
}

<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use App\ContactDirectory;
use Illuminate\Http\Request;

/**
 * Show Contact Lists
 */

Route::get('/', function () {

    $cd         = ContactDirectory::getInstance();
    $contacts   = $cd->getContacts();
    $favourites = $cd->getFavourites();
    return view('lists', [
        'contacts'   => $contacts,
        'favourites' => $favourites
    ]);
});

/**
 * Add New Contact
 */
Route::post('/contact', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'forename'  => 'required',
        'surname'   => 'required',
        'address'   => 'required',
        'telephone' => 'required',
        'email'     => 'required|email',

    ]);

    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }

    $cd = ContactDirectory::getInstance();
    $cd->addContact($_POST);
    return redirect('/');
});

/**
 * Add New Favourite
 */
Route::post('/favourite', function () {
    $cd = ContactDirectory::getInstance();
    $cd->addFavouriteContact($_POST['contact']);
    return redirect('/');
});
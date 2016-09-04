@extends('layouts.app')

@section('content')

    <fieldset>
        <div>
            <h2>Create a new Contact</h2>
        </div>
        <div class="newContact">
            @include('common.errors')
            <form method="post" id="add_contact_form" action="{{ url('contact') }}">
                <table>
                    <tbody>
                    <tr>
                        <th><label for="forename">Forename</label></th>
                        <td><input type="text" name="forename" id="forename" /></td>
                    </tr>
                    <tr>
                        <th><label for="surname">Surname</label></th>
                        <td><input type="text" name="surname" id="surname" /></td>
                    </tr>
                    <tr>
                        <th><label for="email">Email</label></th>
                        <td><input type="text" name="email" id="email" /></td>
                    </tr>
                    <tr>
                        <th><label for="telephone">Telephone</label></th>
                        <td><input type="text" name="telephone" id="telephone" /></td>
                    </tr>
                    <tr>
                        <th><label for="address">Address</label></th>
                        <td><textarea name="address" id="address"></textarea></td>
                    </tr>
                    </tbody>
                </table>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button id="add_contact" type="submit">Add Contact</button>
            </form>

        </div>
    </fieldset>
    <fieldset>
        <div id="search">
            <input type="text" name="lookup" id="lookup" class="lookup" placeholder="search for a contact" />
        </div>
        <div class="left">
            <h2>Contact List</h2>
            <ul id="contactList">
                @if (count($contacts) > 0)
                    @foreach ($contacts as $contact)
                        <li class="contact">
                            <table>
                                <tbody>
                                <tr>
                                    <th>Forename:</th>
                                    <td><?php echo $contact->forename; ?></td>
                                </tr>
                                <tr>
                                    <th>Surname:</th>
                                    <td><?php echo $contact->surname; ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?php echo $contact->email; ?></td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td><?php echo $contact->address; ?></td>
                                </tr>
                                <tr>
                                    <th>Telephone:</th>
                                    <td><?php echo $contact->telephone; ?></td>
                                </tr>
                                </tbody>
                            </table>
                            <form method="post" action="{{ url('favourite') }}">
                                <input type="hidden" name="contact" value="<?php echo htmlentities(json_encode($contact)) ?>" />
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="add-to-fav">Add to my favourites</button>
                            </form>
                        </li>
                    @endforeach
                @endif
            </ul>

        </div>
        <div class="right">
            <h2>My Contacts</h2>
            <ul id="myContacts" class="contactList">
                @if (count($favourites) > 0)
                    @foreach ($favourites as $contact)
                        <li class="contact">
                            <table>
                                <tbody>
                                <tr>
                                    <th>Forename:</th>
                                    <td><?php echo $contact->forename; ?></td>
                                </tr>
                                <tr>
                                    <th>Surname:</th>
                                    <td><?php echo $contact->surname; ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?php echo $contact->email; ?></td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td><?php echo $contact->address; ?></td>
                                </tr>
                                <tr>
                                    <th>Telephone:</th>
                                    <td><?php echo $contact->telephone; ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </fieldset>


@endsection
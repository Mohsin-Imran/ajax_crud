<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact; // Import your model (if saving data in a database)

class ContactController extends Controller
{

    public function fetchContacts()
    {
        $contacts = Contact::all();
        return view('index', get_defined_vars());
    }
    public function store(Request $request)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'number' => 'required|numeric',
        ]);

        // Save data to the database (optional, based on your requirement)
        // Ensure you have a model named Contact and a table named 'contacts'
        $contact = new Contact();
        $contact->name = $validatedData['name'];
        $contact->email = $validatedData['email'];
        $contact->number = $validatedData['number'];
        $contact->save();

        // Return a JSON response
        return response()->json(['message' => 'Form submitted successfully!']);
    }

    public function edit($id)
    {
        $contact = Contact::find($id);
        return response()->json(['contact' => $contact]);
    }


    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'number' => 'required|numeric',
        ]);

        $contact = Contact::find($id);
        $contact->name = $validatedData['name'];
        $contact->email = $validatedData['email'];
        $contact->number = $validatedData['number'];
        $contact->save();

        return response()->json(['message' => 'Form submitted successfully!']);
    }


    public function delete($id)
    {
        $contact = Contact::find($id);
        $contact->delete();
        return response()->json(['contact' => $contact]);
    }
}

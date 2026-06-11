<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    // Public endpoint for checkout
    public function index()
    {
        return response()->json(BankAccount::where('is_active', true)->get());
    }

    // Admin endpoint for list
    public function adminIndex()
    {
        return response()->json(BankAccount::all());
    }

    // Admin create
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'branch' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $account = BankAccount::create($validated);
        return response()->json($account, 201);
    }

    // Admin update
    public function update(Request $request, $id)
    {
        $account = BankAccount::findOrFail($id);

        $validated = $request->validate([
            'bank_name' => 'sometimes|required|string|max:255',
            'account_name' => 'sometimes|required|string|max:255',
            'account_number' => 'sometimes|required|string|max:255',
            'branch' => 'sometimes|required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $account->update($validated);
        return response()->json($account);
    }

    // Admin delete
    public function destroy($id)
    {
        $account = BankAccount::findOrFail($id);
        $account->delete();
        return response()->json(['message' => 'Bank account deleted']);
    }

    // Admin toggle active
    public function toggleActive($id)
    {
        $account = BankAccount::findOrFail($id);
        $account->is_active = !$account->is_active;
        $account->save();
        return response()->json($account);
    }
}

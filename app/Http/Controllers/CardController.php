<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index(Request $request)
    {
        $query = Card::query();
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cards = $query->paginate(10);

        return view('cards.index', compact('cards'));
    }

    public function create()
    {
        return view('cards.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $serialNumber = $this->generateSerialNumber();
        $secretNumber = $this->generateSecretNumber();

        Card::create([
            'amount' => $request->amount,
            'serial_number' => $serialNumber,
            'secret_number' => $secretNumber,
            'status' => 'new',
        ]);

        return redirect()->route('cards.index')->with('success', 'Card created successfully.');
    }

    public function edit(Card $card)
    {
        return view('cards.edit', compact('card'));
    }

    public function update(Request $request, Card $card)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $card->update([
            'amount' => $request->amount,
        ]);

        return redirect()->route('cards.index')->with('success', 'Card updated successfully.');
    }

    public function destroy(Card $card)
    {
        $card->delete();

        return redirect()->route('cards.index')->with('success', 'Card deleted successfully.');
    }

    private function generateSerialNumber()
    {
        return strtoupper(bin2hex(random_bytes(10)));
    }

    private function generateSecretNumber()
    {
        return strtoupper(bin2hex(random_bytes(7)));
    }
    
    public function print($id)
    {
        $card = Card::findOrFail($id);
        return view('cards.print', compact('card'));
    }

}

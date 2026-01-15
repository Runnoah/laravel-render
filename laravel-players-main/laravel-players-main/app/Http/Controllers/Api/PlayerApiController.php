<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Services\PlayerService;
use Illuminate\Http\Request;

class PlayerApiController extends Controller
{
    protected $playerService;

    public function __construct(PlayerService $playerService)
    {
        $this->playerService = $playerService;
    }

    public function index()
    {
        return response()->json($this->playerService->getAllPlayers());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:players,name',
            'ranking' => 'required|integer|min:1',
        ]);

        $player = $this->playerService->createPlayer($request->all());
        return response()->json($player, 201);
    }

    public function show(Player $player)
    {
        return response()->json($player);
    }

    public function update(Request $request, Player $player)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:players,name,' . $player->id,
            'ranking' => 'sometimes|required|integer|min:1',
        ]);

        $player = $this->playerService->updatePlayer($player, $request->all());
        return response()->json($player);
    }

    public function destroy(Player $player)
    {
        $this->playerService->deletePlayer($player);
        return response()->json(['message' => 'Eliminado']);
    }
}
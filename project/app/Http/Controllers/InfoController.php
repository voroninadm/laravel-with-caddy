<?php

namespace App\Http\Controllers;

use App\Http\Requests\InfoRequest;
use App\Jobs\StoreUserInfoJob;
use App\Models\Info;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class InfoController extends Controller
{
    private int $PAGINATION_INFO_COUNT = 10;

    public function info(string $sector): Response
    {
        $current_sector = Sector::where('name', $sector)->first();
        $info_data = auth()->user()->sectors()->withCount('infos', 'unreadInfos')->get();
        $sector_data = Info::where('sector_id', $current_sector->id)
            ->orderBy('created_at', 'desc')
            ->paginate($this->PAGINATION_INFO_COUNT);

        return Inertia::render('Info/Info', [
            'current_sector' => $current_sector,
            'info_data' => $info_data,
            'sector_data' => $sector_data
        ]);
    }

    public function store(InfoRequest $request): RedirectResponse
    {
        $sector = Sector::find($request->sector_id);
        $sector_users = $sector->users->where('id', '!=', auth()->id());
        $message = Info::create($request->validated());
        $message->relatedInfo()->create([
            'info_id' => $message->id,
            'user_id' => auth()->id(),
            'sector_id' => $message->sector_id,
            'is_read' => true
        ]);

        StoreUserInfoJob::dispatch($sector_users, $message)->onQueue('default');
            return redirect()->back();
    }

    public function update(InfoRequest $request, Info $info)
    {
        $info->update($request->validated());
        return redirect()->back();

    }

    public function read(Info $info): RedirectResponse
    {
        $info->relatedInfo()->where('user_id', Auth::id())->updateOrCreate(
            ['info_id' => $info->id],
            [
                'user_id' => auth()->id(),
                'sector_id' => $info->sector_id,
                'is_read' => true
            ]
        );
        return redirect()->back();

    }

    public function finish(Info $info): RedirectResponse
    {
        $info->update(['is_done' => true]);
        return redirect()->back();
    }

    public function destroy(Info $info): RedirectResponse
    {
        $info->delete();
        return redirect()->back();
    }
}

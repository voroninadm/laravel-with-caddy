<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class WorkerController extends Controller
{
    /**
     * Display the registration view.
     */
    public function workers(): Response
    {
        $workers = Worker::orderBy('name')->paginate(20);
        return Inertia::render('Users/WorkersPage', [
            'workers' => $workers
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:' . Worker::class,
            'job' => 'required|string|max:255',
            'category' => 'string|nullable|max:255',
        ]);

        Worker::create([
            'name' => $request->name,
            'job' => $request->job,
            'category' => $request->category,
            'password' => '$2y$10$72eV4C5J5zIct.ZswYYp2.ItH1UnvkhhjRMaw/8N7yAL46dsLJvC6',
            'is_blocked' =>  $request->is_blocked
        ]);

        Cache::forget('currentWorkersGroup');
        Cache::forget('allWorkersGroup');
        Cache::forget('allWorkersIdsNames');

        return redirect()->back();
    }

    public function edit (Worker $id)
    {
        return Inertia::render('Users/UsersProfilePage', [
            'worker' => $id
        ]);
    }

    public function update (Request $request)
    {
        $request->validate([
            'worker_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'job' => 'string|nullable|max:255',
            'category' => 'string|nullable|max:255',
            'is_blocked' => 'required|boolean'
        ]);
        $worker = Worker::find($request->worker_id);
        $worker->update([
            'name' => $request->input('name'),
            'job' => $request->input('job'),
            'category' => $request->input('category'),
            'is_blocked'=> $request->is_blocked
        ]);
        Cache::forget('currentWorkersGroup');
        Cache::forget('allWorkersGroup');
        Cache::forget('allWorkersIdsNames');

        return redirect()->route('workers.show');
    }

    public function destroy(Worker $id)
    {
        $id->delete();

        Cache::forget('currentWorkersGroup');
        Cache::forget('allWorkersGroup');
        Cache::forget('allWorkersIdsNames');

        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Services\SubscriberService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class SubscriberController extends Controller
{
    private SubscriberService $subscriberService;

    public function __construct(SubscriberService $subscriberService)
    {
        $this->subscriberService = $subscriberService;
    }

    public function index(): View
    {
        return view('subscribers.index');
    }

    public function create(): ?Response
    {
        return null;
    }

    public function edit(Subscriber $subscriber): ?Response
    {
        return null;
    }
}

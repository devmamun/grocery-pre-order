<?php

namespace Mamun\ShopPreOrder\Jobs;

use App\Models\User;
use Mamun\ShopPreOrder\Mail\PreOrderConfirmation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class AdminEmailJob implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $preOrder;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($preOrder)
    {
        $this->preOrder = $preOrder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        User::where('role', 'admin')->get()->each(function ($user) {
            Mail::to($user->email)->send(new PreOrderConfirmation($this->preOrder, 'admin'));
        });
    }
}

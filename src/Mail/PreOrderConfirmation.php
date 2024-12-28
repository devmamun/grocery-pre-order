<?php

namespace Mamun\ShopPreOrder\Mail;

use Mamun\ShopPreOrder\Models\PreOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PreOrderConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public PreOrder $preOrder;
    public string $recipientType;

    public function __construct(PreOrder $preOrder, string $recipientType)
    {
        $this->preOrder = $preOrder;
        $this->recipientType = $recipientType;
    }

    public function build()
    {
        return $this->view('grocery::emails.preorderconfirmation')
                    ->with([
                        'preOrder' => $this->preOrder,
                        'recipientType' => $this->recipientType,
                    ]);
    }
}

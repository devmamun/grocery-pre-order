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

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PreOrder $preOrder, string $recipientType)
    {
        $this->preOrder = $preOrder;
        $this->recipientType = $recipientType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('grocery::emails.preorderconfirmation')
                    ->with([
                        'preOrder' => $this->preOrder,
                        'recipientType' => $this->recipientType,
                    ]);
    }
}

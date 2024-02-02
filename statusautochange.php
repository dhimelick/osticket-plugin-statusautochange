<?php
require_once(INCLUDE_DIR . 'class.signal.php');
require_once(INCLUDE_DIR . 'class.plugin.php');
require_once(INCLUDE_DIR . 'class.ticket.php');
require_once(INCLUDE_DIR . 'class.osticket.php');
require_once(INCLUDE_DIR . 'class.config.php');
require_once('config.php');

class StatusAutoChangePlugin extends Plugin {

    var $config_class = 'StatusAutoChangePluginConfig';

    /**
     * The entrypoint of the plugin, keep short, always runs.
     */
    function bootstrap() {
        Signal::connect('threadentry.created', array($this, 'onTicketUpdated'));
    }
    /**
     * Change status when a customer updates a ticket.
     */
    function onTicketUpdated(ThreadEntry $entry) {
        global $cfg;
        if (!$cfg instanceof OsticketConfig) {
            error_log("StatusAutoChange plugin called too early.");
            return;
        }

        if (!$entry instanceof MessageThreadEntry) {
            return;
        }

        // verify this is a ticket thread, not a task thread
        $threadType = Thread::objects()->filter([
			'id' => $entry->getThreadId()
		])->values_flat('object_type')->first() [0];
		if ($threadType != "T") {
			return;
		}

        $ticket = $this->getTicket($entry);
        if (!$ticket instanceof Ticket) {
            return;
        }

        $first_entry = $ticket->getMessages()[0];
        if ($entry->getId() == $first_entry->getId()) {
            return;
        }

        $new_status = TicketStatus::lookup($this->getConfig()->get('clientReplyStatus'));
        if (!is_null($new_status) && $ticket->getStatusId() != $new_status->getId()) {
            $ticket->setStatus($new_status);
        }
    }

    /**
     * Fetches a ticket from a ThreadEntry.
     *
     * From https://github.com/clonemeagain/osticket-slack.
     */
    function getTicket(ThreadEntry $entry) {
        $ticket_id = Thread::objects()->filter([
            'id' => $entry->getThreadId()
        ])->values_flat('object_id')->first() [0];
        return Ticket::lookup(array('ticket_id' => $ticket_id));
    }
}
